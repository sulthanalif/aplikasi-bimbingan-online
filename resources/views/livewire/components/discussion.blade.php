<?php

use App\Models\User;
use App\Models\Thesis;
use Mary\Traits\Toast;
use App\Models\Notification;
use App\Traits\LogFormatter;
use Livewire\Volt\Component;
use App\Events\NewNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewDiscussionMessage;

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
        $this->page++;
        $this->loadDiscussions();

        // dd($recipient, 'Mencoba mengirim notifikasi');
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

            // --- LOGIKA NOTIFIKASI BARU ---
            $currentUser = auth()->user();
            $recipient = $this->thesis->student->user->id === $currentUser->id
                ? ($this->thesis->actionBy ?? User::whereHas('roles', function ($query){
                    $query->where('name', 'prodi');
                }))->first()
                : $this->thesis->student->user;

            if ($recipient) {
                // Baris ini akan otomatis menyimpan ke DB & melakukan broadcast

                $recipient->notify(new NewDiscussionMessage($this->thesis, $currentUser));
            }

            DB::commit();
            // $this->success('Berhasil Kirim Pesan', position: 'toast-bottom');
            $this->reset('message');

            // 2. Langsung tambahkan pesan baru ke koleksi tanpa refresh
            // Ini akan memberikan pengalaman real-time yang lebih baik
            $this->loadDiscussions();
            $this->dispatch('discussion-added');

        } catch (\Exception $e) {
            $this->logError($e); // Hapus jika tidak ada method logError
            DB::rollback();
            $this->error('Gagal Kirim Pesan', position: 'toast-bottom');
        }
    }
}; ?>

<div class="space-y-4" x-data="{
    init() {

        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .listen('.new-notification', (e) => {
                @this.loadDiscussions();
            });
    }
}">
    <div id="discussion-container" class="bg-white rounded-lg p-4 h-96 overflow-y-auto space-y-4 w-full">
            <div class="flex justify-center" x-data="{ show: false }" x-effect="show = $wire.hasMorePages" x-cloak>
                <button
                    x-show="show"
                    id="load-more-button"
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    wire:target="loadMore"
                    class="bg-primary px-5 py-1 rounded-lg text-white  flex items-center gap-2"
                >
                    <p wire:loading.remove wire:target="loadMore">Load More</p>
                    <span wire:loading wire:target="loadMore">
                        <div class="flex items-center gap-2">
                            Loading...
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        </div>
                    </span>
                </button>
            </div>


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
           <x-button label="Kirim" wire:click="send" icon="fas.paper-plane" responsive class="btn-primary" spinner="send" />
       </div>
    </div>
</div>

@script
<script>
    const setupDiscussionComponent = () => {
        const container = document.getElementById('discussion-container');
        const loadMoreButton = document.getElementById('load-more-button');

        // Jika komponen tidak ada di halaman ini, hentikan fungsi.
        if (!container) {
            // console.log('Discussion container not found');
            return;
        }

        // Fungsi untuk scroll ke paling bawah
        const scrollToBottom = () => {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100); // Increased timeout for better reliability
        };

        // 1. Scroll ke bawah saat komponen dimuat
        scrollToBottom();

        // 2. Listener untuk auto-scroll setelah kirim pesan
        // Pastikan Anda dispatch event ini dari method `send()` di PHP
        // $this->dispatch('discussion-added');
        Livewire.on('discussion-added', () => {
            // Add small delay to ensure content is fully loaded
            setTimeout(scrollToBottom, 50);
        });

        // 3. Logika untuk "Load More"
        // console.log('Load more button exists:', !!loadMoreButton);

        if (loadMoreButton) {
            let observer;
            // console.log('Setting up load more button listener');

            loadMoreButton.addEventListener('click', () => {
                // console.log('Load more button clicked');
                const scrollHeightBefore = container.scrollHeight;
                const scrollTopBefore = container.scrollTop;

                // console.log('Before loading more:');
                // console.log('- scrollHeight:', scrollHeightBefore);
                // console.log('- scrollTop:', scrollTopBefore);

                // Disconnect previous observer if exists
                if (observer) {
                    observer.disconnect();
                }

                observer = new MutationObserver(() => {
                    // console.log('Mutation observer triggered');
                    const newScrollHeight = container.scrollHeight;
                    const heightDifference = newScrollHeight - scrollHeightBefore;
                    const newScrollTop = scrollTopBefore + heightDifference;

                    // console.log('After content loaded:');
                    // console.log('- new scrollHeight:', newScrollHeight);
                    // console.log('- height difference:', heightDifference);
                    // console.log('- calculated scrollTop:', newScrollTop);

                    container.scrollTop = newScrollTop;

                    // console.log('Final scrollTop set to:', container.scrollTop);

                    // Disconnect after first execution
                    observer.disconnect();
                });

                observer.observe(container, {
                    childList: true,
                    subtree: true
                });
                // console.log('Observer setup complete');
            });
        }
    };

    // Initialize on both full page load and wire navigation
    document.addEventListener('livewire:initialized', setupDiscussionComponent);
    document.addEventListener('livewire:navigated', setupDiscussionComponent);
</script>
@endscript

@push('styles')
<script src="https://cdn.tiny.cloud/1/8spzjp1181dgpfbokponfu70xrgl5raypeuiszvzkbuo8by0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

