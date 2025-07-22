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

    public function readNotif($notifiable_id, $sender_id): void
    {
        // dd($notifiable_id, $sender_id);
        $notifs = auth()->user()->notifications()
            ->where('notifiable_id', (int) $notifiable_id)
            ->where('data->sender_id', (int) $sender_id)
            ->update(['read_at' => now()]);
    }

    public function readAll(): void
    {
        auth()->user()->notifications()->update(['read_at' => now()]);
        $this->loadData();
        $this->dispatch('notifications-cleared');
    }

}; ?>

@script
    <script>
        $js('click', (id, sender_id) => {
            // console.log('id', id);

            $wire.readNotif(id, sender_id);
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
        @forelse ($notifications->groupBy(function($notification) {
            return $notification->notifiable_id . '-' . $notification->data['sender_id'];
        })->map(function($group) {
            $first = $group->first();
            $first->count = $group->where('read_at', null)->count();
            return $first;
        })->values()


        as $notif)
            <x-list-item :item="$notif" class="{{ $notif->count === 0 ? '' : 'bg-primary hover:bg-primary/50 text-white' }} rounded-lg p-4" link="{{ $notif->data['url'] }}" @click="$js.click({{ $notif->notifiable_id}} , {{ $notif->data['sender_id'] }})">
                <x-slot:avatar>
                    {{ $notif->count === 0 ? '' : $notif->count }}
                </x-slot:avatar>
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
