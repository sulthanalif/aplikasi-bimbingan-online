<?php

use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;

new #[Title('Notifikasi')] class extends Component {
    use Toast;

    public Collection $notifications;

    public function mount(): void
    {
        $this->loadData();
        // dd($this->notifications);
    }

    public function loadData(): void
    {
        $this->notifications = auth()->user()->notifications()->get();
    }

    public function readNotif($id): void
    {
        $notif = $this->notifications->find($id);
        $notif->markAsRead();
    }

    public function readAll(): void
    {
        auth()->user()->notifications()->update(['read_at' => now()]);
        $this->loadData();
    }

}; ?>

@script
    <script>
        $js('click', (id) => {
            // console.log('id', id);

            $wire.readNotif(id);
        });
    </script>
@endscript

<div x-data="{
    init() {
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .listen('.new-notification', (e) => {
                @this.loadData();
            });
    }
}">
    <x-header title="Notifikasi" separator >
        <x-slot:actions>
            <x-button label="Tandai Dibaca Semua" responsive class="btn-primary" icon="fas.check-double" spinner="readAll" @click="$wire.readAll()" />
        </x-slot:actions>
    </x-header>

    <div class="w-full space-y-4 overflow-y-auto max-h-96 bg-white rounded-lg p-4">
        @forelse ($notifications as $notif)
            <x-list-item :item="$notif" class="{{ $notif->read_at ? '' : 'bg-primary hover:bg-primary/50 text-white' }} rounded-lg p-4" link="{{ $notif->data['url'] }}" @click="$js.click('{{ $notif->id }}')">
                <x-slot:value>
                    {{ $notif->data['message'] }}
                </x-slot:value>
                <x-slot:actions>
                    <x-date-formatter :date="$notif->created_at" format="d F Y, H:i" />
                </x-slot:actions>
            </x-list-item>
        @empty

        @endforelse
    </div>
</div>
