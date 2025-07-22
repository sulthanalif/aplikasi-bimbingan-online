<?php

use Mary\Traits\Toast;
use Livewire\Volt\Component;

new class extends Component {
    use Toast;

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
}; ?>

<div
    x-data="{
        notificationCount: 0,
        init() {
            window.Echo.private('App.Models.User.{{ auth()->id() }}')
                .listen('.new-notification', (e) => {
                    this.notificationCount++;
                    {{-- console.log(e); --}}
                    @this.notification('New Notification', e.message, 'toast-top');
                });
        }
    }"
    x-effect="notificationCount = {{ auth()->user()->unreadNotifications()->count() }}"
>
    <x-button icon="o-bell" link="{{ route('notifications') }}" class="btn-ghost btn-sm indicator" responsive>
        Notifications

        <template x-if="notificationCount > 0">
            <x-badge class="badge-error text-white badge-sm indicator-item" x-text="notificationCount" />
        </template>
    </x-button>
</div>

