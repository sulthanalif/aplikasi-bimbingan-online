<?php

use Mary\Traits\Toast;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

    public int $count = 0;

    public function mount()
    {
        $this->freshCount();
    }

    public function notification($title, $message, $position)
    {
        $this->toast(
            type: 'info',
            title: $title,
            description: $message,
            position: $position,
            icon: 'fas.bell'
        );
    }

    public function autoRead($notificationId)
    {
        auth()->user()->notifications()->find($notificationId)->markAsRead();
    }

    #[On('notifications-cleared')]
    public function freshCount()
    {
        // $this->success('Event "notifications-cleared" diterima!');
        $this->count = auth()->user()->notifications()->where('read_at', null)->count();
    }

}; ?>

<div
    x-data="{
        notificationCount: 0,
        init() {
            window.Echo.leave('App.Models.User.{{ auth()->id() }}');

            window.Echo.private('App.Models.User.{{ auth()->id() }}')
                .listen('.new-notification', (e) => {
                    if(window.location.href == e.url) {
                        @this.autoRead(e.id);
                    } else {
                        @this.count++;
                        @this.notification('New Notification', e.message, 'toast-top');
                    }
                });
        }
    }"
    x-effect="notificationCount = {{ $count }}"
>
    <x-button icon="o-bell" link="{{ route('notifications') }}" class="btn-ghost btn-sm indicator" responsive>
        Notifications

        <template x-if="notificationCount > 0">
            <x-badge class="badge-error text-white badge-sm indicator-item" x-text="notificationCount" />
        </template>
    </x-button>
</div>

