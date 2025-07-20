<?php

use App\Models\Thesis;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use App\Traits\CreateOrUpdate;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use Toast, CreateOrUpdate;

    public Thesis $thesis;
    public string $message = '';

    public function mount(Thesis $thesis): void
    {
        $this->thesis = $thesis;
    }

    public function send(): void
    {
        try {
            DB::beginTransaction();

            $this->thesis->discussions()->create([
                'user_id' => auth()->user()?->id,
                'message' => $this->message,
            ]);

            DB::commit();
            $this->success('Berhasil Kirim Pesan', position: 'toast-bottom');
            $this->reset('message');
        } catch (\Exception $e) {
            $this->error('Gagal Kirim Pesan', position: 'toast-bottom');
            $this->logError($e);
            DB::rollback();
        }
    }
}; ?>

<div class="space-y-4">
    @foreach($thesis->discussions as $discussion)
    <div>
        {{-- {{ $discussion->user->id === auth()->user()->id ? 'right' : 'left' }} --}}
        <x-avatar placeholder="{{ strtoupper(substr($discussion->user->name, 0, 2)) }}" title="{{ ucfirst($discussion->user->name) }} - {{ ucfirst($discussion->user->role) }}" subtitle="{{ \Carbon\Carbon::parse($discussion->created_at)->locale('id')->translatedFormat('d F Y, H:i') }}" class="!w-10 {{ ($discussion->user->id === auth()->user()->id) ? 'bg-secondary' : 'bg-neutral' }}" />
        <x-card class="{{ ($discussion->user->id === auth()->user()->id) ? 'bg-secondary' : 'bg-neutral' }} mt-2">
            <div class="flex flex-col gap-2 text-white text-sm">
                {!! $discussion->message !!}
            </div>
        </x-card>
    </div>
    @endforeach
    <div class="space-y-4">
       <label class="font-medium text-gray-700">Tambahkan Pesan</label>
       <x-editor wire:model="message"
            :config="[
            'toolbar' => 'undo redo |link image accordion | styles | bold italic underline strikethrough | align | bullist numlist',
            'plugins' => 'media autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen',
            'statusbar' => false,
            'height' => 200,
            // 'menubar' => true
        ]"/>

        <div class="flex justify-end">
            <x-button label="Kirim" @click="$wire.send" responsive class="btn-primary" spinner="send" />
        </div>
    </div>
</div>

@push('styles')
<script src="https://cdn.tiny.cloud/1/8spzjp1181dgpfbokponfu70xrgl5raypeuiszvzkbuo8by0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
