<?php

use App\Models\Thesis;
use Mary\Traits\Toast;
use App\Traits\LogFormatter;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use Toast, LogFormatter; // Trait CreateOrUpdate tidak digunakan, bisa dihapus

    public Thesis $thesis;
    public string $message = '';
    public Collection $discussions;
    public int $perpage = 10; // Menaikkan perpage agar lebih optimal
    public int $page = 1;
    public bool $hasMorePages = false;

    public function mount(Thesis $thesis): void
    {
        $this->thesis = $thesis;
        $this->loadDiscussions();
    }

    public function loadDiscussions(): void
    {
        // 1. Tambahkan `latest()` untuk mengurutkan dari yang terbaru (DESC)
        $discussions = $this->thesis->discussions()
            ->latest() // Sama dengan ->orderBy('created_at', 'desc')
            ->paginate(perPage: $this->perpage, page: $this->page);

        $items = collect($discussions->items());

        if ($this->page === 1) {
            // Balik urutan item agar yang terlama ada di awal collection
            $this->discussions = $items->reverse()->values();
        } else {
            // Gabungkan item lama dengan item baru yang sudah dibalik urutannya
            $this->discussions = $items->reverse()->values()->merge($this->discussions);
        }

        $this->hasMorePages = $discussions->hasMorePages();
    }

    public function loadMore(): void
    {
        // KIRIM SINYAL KE JS: "Akan mulai memuat..."
        $this->dispatch('loading-more');

        $this->page++;
        $this->loadDiscussions();

        // KIRIM SINYAL KE JS: "Sudah selesai memuat!"
        $this->dispatch('more-loaded');
    }

    public function send(): void
    {
        // Validasi agar pesan tidak kosong
        if (empty(trim($this->message))) {
            $this->warning('Pesan tidak boleh kosong.', position: 'toast-bottom');
            return;
        }

        try {
            DB::beginTransaction();

            $newDiscussion = $this->thesis->discussions()->create([
                'user_id' => auth()->id(), // Lebih singkat
                'message' => $this->message,
            ]);

            DB::commit();
            $this->success('Berhasil Kirim Pesan', position: 'toast-bottom');
            $this->reset('message');

            // 2. Langsung tambahkan pesan baru ke koleksi tanpa refresh
            // Ini akan memberikan pengalaman real-time yang lebih baik
            $this->loadDiscussions(); 

        } catch (\Exception $e) {
            $this->logError($e); // Hapus jika tidak ada method logError
            DB::rollback();
            $this->error('Gagal Kirim Pesan', position: 'toast-bottom');
        }
    }
}; ?>

<div class="space-y-4">
    <div id="discussion-container" class="bg-white rounded-lg p-4 h-96 overflow-y-auto space-y-4 w-full">
        @if($hasMorePages)
            <div class="flex justify-center">
                <x-button id="load-more-button" label="Load More" wire:click="loadMore" spinner="loadMore" class="btn-primary btn-sm" />
            </div>
        @endif

        @forelse($discussions as $discussion)
            <div class="flex {{ $discussion->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="flex items-center gap-3 {{ $discussion->user_id === auth()->id() ? 'justify-end flex-row-reverse' : 'justify-start' }}">
                    <x-avatar placeholder="{{ strtoupper(substr($discussion->user->name, 0, 2)) }}" class="bg-primary !w-8 !h-8" />
                    <div class="flex flex-col {{ $discussion->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <div class="font-bold text-sm">
                            {{ ucfirst($discussion->user->name) }}
                        </div>
                        <x-card class="{{ $discussion->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded-lg px-3 py-2 max-w-xs md:max-w-md">
                            <div class="text-sm">
                                {!! $discussion->message !!}
                            </div>
                        </x-card>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($discussion->created_at)->locale('id')->translatedFormat('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex h-full justify-center items-center text-gray-500">Mulai Berdiskusi...</div>
        @endforelse
    </div>

    <div class="space-y-4 pt-4">
       <label class="font-medium text-gray-700">Tambahkan Pesan</label>
       <x-editor wire:model="message"
           :config="[
               'toolbar' => 'undo redo |link image accordion | styles | bold italic underline strikethrough | align | bullist numlist',
               'plugins' => 'media autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen',
               'statusbar' => false,
               'height' => 200,
           ]"/>

       <div class="flex justify-end">
           <x-button label="Kirim" wire:click="send" responsive class="btn-primary" spinner="send" />
       </div>
    </div>
</div>

@script
<script>
    document.addEventListener('livewire:initialized', () => {
        const container = document.getElementById('discussion-container');

        // Fungsi untuk scroll ke paling bawah
        const scrollToBottom = () => {
            // Diberi sedikit jeda agar DOM sempat render
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 50);
        };

        // 1. Scroll ke bawah saat halaman pertama kali dimuat
        scrollToBottom();

        // 2. Jika Anda menggunakan metode .push() di PHP setelah mengirim pesan baru,
        //    panggil event ini dari PHP untuk auto-scroll.
        //    $this->dispatch('discussion-added');
        Livewire.on('discussion-added', () => {
            scrollToBottom();
        });

        // 3. Logika untuk menjaga posisi scroll saat "Load More"
        const loadMoreButton = document.getElementById('load-more-button');

        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', () => {
                // Simpan tinggi dan posisi scroll SAAT INI, sebelum Livewire bekerja
                const scrollHeightBefore = container.scrollHeight;
                const scrollTopBefore = container.scrollTop;

                // Buat "pengamat" yang akan menunggu perubahan pada kontainer
                const observer = new MutationObserver(() => {
                    // Setelah Livewire menambahkan elemen baru,
                    // kembalikan posisi scroll ke tempatnya semula.
                    container.scrollTop = scrollTopBefore + (container.scrollHeight - scrollHeightBefore);

                    // Hentikan pengamatan agar tidak berjalan terus-terusan
                    observer.disconnect();
                });

                // Mulai mengamati perubahan (penambahan anak elemen) pada kontainer
                observer.observe(container, {
                    childList: true,
                });
            });
        }
    });
</script>
@endscript

@push('styles')
<script src="https://cdn.tiny.cloud/1/8spzjp1181dgpfbokponfu70xrgl5raypeuiszvzkbuo8by0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

