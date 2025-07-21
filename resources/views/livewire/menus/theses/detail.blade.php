<?php

use App\Models\Thesis;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use App\Traits\CreateOrUpdate;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

new #[Title('Detail Pengajuan')] class extends Component {
    use Toast, CreateOrUpdate;

    public bool $modalAction = false;

    public Thesis $thesis;

    // public string $note = '';

    public function mount(Thesis $thesis): void
    {
        $this->thesis = $thesis;
        // dd($this->thesis->discussions);
    }

    public function back(): void
    {
        $this->redirect(route('theses'), navigate: true);
    }

    public function action($action)
    {
        try {
            DB::beginTransaction();

            // $thesis = Thesis::find($this->recordId);
            $thesis = $this->thesis;
            $thesis->action_by = auth()->user()->id;
            $thesis->status = $action;
            // $thesis->note = $this->note;
            $thesis->save();

            DB::commit();
            $this->success('Pengajuan Judul '.$action, position: 'toast-bottom');
            $this->reset('modalAction');
        } catch (\Exception $th) {
            $this->error('Gagal '.$action, position: 'toast-bottom');
            $this->logError($th);
            DB::rollback();
        }
    }
}; ?>
@script
    <script>
        $js('actions', (action) => {
            $wire.modalAction = true;
            const modalAction = document.getElementById('modal-action');

            $wire.note = '';

            modalAction.innerHTML = `
                <x-form wire:submit="action('${action}')">
                    <x-slot:actions>
                        <x-button label="Ya" type="submit" class="btn-primary" spinner="action('${action}')" />
                    </x-slot:actions>
                </x-form>
            `;


        });
    </script>
@endscript

<div>
    <x-header title="Detail Pengajuan" separator>
        <x-slot:actions>
            <x-button label="Kembali" @click="$wire.back" responsive class="btn-primary" icon="fas.arrow-left" spinner="back" />
        </x-slot:actions>
    </x-header>
 
    <div class="grid lg:grid-cols-2 gap-4">
        <x-card title="Mahasiswa">
            <div class="text-sm">
                <div class="grid grid-cols-2">
                    <label class="font-medium text-gray-700">NIM</label>
                    <p class="mt-1">{{ $thesis->student->nim }}</p>
                </div>
                <div class="grid grid-cols-2">
                    <label class="font-medium text-gray-700">Nama</label>
                    <p class="mt-1">{{ $thesis->student->user->name }}</p>
                </div>
                <div class="grid grid-cols-2">
                    <label class="font-medium text-gray-700">Fakultas/Jurusan</label>
                    <p class="mt-1">{{ $thesis->student->department->faculty->name }} / {{ $thesis->student->department->name }}</p>
                </div>
            </div>
        </x-card>
        <x-card title="Judul">
            @if($thesis->status == 'pending')
                @can('action-thesis')
                <x-slot:menu>
                    <x-button label="Tolak" @click="$js.actions('rejected')" class="btn-error text-white" />
                    <x-button label="Terima" @click="$js.actions('approved')" class="btn-success text-white" />
                    {{-- <x-icon name="o-heart" class="cursor-pointer" /> --}}
                </x-slot:menu>
                @endcan
            @endif
            <div>
                <div>
                    {{-- <label class="font-medium text-gray-700">Judul</label> --}}
                    <p class="text-lg">{{ $thesis->title }}</p>
                </div>
                <div class="grid grid-cols-4 text-sm mt-4">
                    <div>
                        <label class="font-medium text-gray-700">Diajukan pada</label>
                        <p class="mt-1"><x-date-formatter :date="$thesis->created_at" format="d F Y, H:i" /></p>
                    </div>
                    @if($thesis->action_by)
                    <div>
                        <label class="font-medium text-gray-700">Di{{ $thesis->status == 'approved' ? 'terima' : 'tolak' }} oleh</label>
                        <p class="mt-1">{{ $thesis->actionBy->name }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Di{{ $thesis->status == 'approved' ? 'terima' : 'tolak' }} pada</label>
                        <p class="mt-1"><x-date-formatter :date="$thesis->updated_at" format="d F Y, H:i" /></p>
                    </div>
                    @endif
                    <div>
                        <label class="font-medium text-gray-700">Status</label>
                        <p class="mt-1"><x-status :status="$thesis->status" /></p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <x-card title="Diskusi" class="mt-4">
        <livewire:components.discussion :thesis="$thesis" />
    </x-card>

    <x-modal wire:model="modalAction" box-class="w-full h-fit" without-trap-focus>
        <div class="flex justify-center items-center">
            <x-icon name='fas.circle-exclamation' class="text-yellow-500 w-10" />
        </div>
        <p class="text-center mt-4">Apakah Anda yakin ingin menyelesaikan pengajuan ini?</p>
        <div id="modal-action">

        </div>
    </x-modal>
</div>
