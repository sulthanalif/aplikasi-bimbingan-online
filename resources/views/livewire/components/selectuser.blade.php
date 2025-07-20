<?php

use App\Models\User;
use Mary\Traits\Toast;
use App\Traits\LogFormatter;
use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use Toast, LogFormatter;

    public Collection $users;
    public ?int $userId = null;

    public function mount()
    {
        $this->searchUser();
        $this->userId = auth()->user()?->id;
    }

    public function searchUser(string $value = '')
    {
        $selectedOption = User::where('id', $this->userId)->get();

        $this->users = User::query()
            ->whereIn('name', ['Prodi', 'Mahasiswa', 'Dosen', 'Super Admin'])
            ->where('name', 'like', "%{$value}%")
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

    public function switchUser($value)
    {
        try {
            Auth::loginUsingId($value);
            $this->success('Berhasil Ganti User');
            $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $th) {
            $this->error('Gagal Ganti User');
            $this->logError($th);
        }
    }
}; ?>

<div>
    <x-choices
    {{-- label="User" --}}
    wire:model="userId"
    :options="$users"
    placeholder="Pilih User..."
    search-function="searchUser"
    @change-selection="$wire.switchUser($event.detail.value)"
    clearable
    single
    searchable >
    @scope('item', $user)
        <x-list-item :item="$user" value="name" sub-value="role" />
    @endscope
    @scope('selection', $user)
        {{ $user->name }} ({{ $user->role }})
    @endscope
    </x-choices>
</div>
