<?php

use App\Models\User;
use Mary\Traits\Toast;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Department;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Traits\CreateOrUpdate;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Title('Mahasiswa')] class extends Component {
    use Toast, CreateOrUpdate, WithPagination;

    public string $search = '';
    public bool $modal = false;
    public int $perPage = 10;
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public $pageModel;
    public Collection $faculties;
    public Collection $departments;

    //var
    public ?int $department_id = null;
    public ?int $faculty_id = null;
    public string $name = '';
    public string $nim = '';
    public string $gender = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $password = '';
    public bool $status = true;

    public function mount()
    {
        $this->pageModel = new Student();
        $this->searchFaculty();
        $this->searchDepartment();
    }

    public function searchFaculty(string $value = '')
    {
        $selectedOption = Faculty::where('id', $this->faculty_id)->get();

        return $this->faculties = Faculty::query()
            ->where('status', true)
            ->where(function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%");
            })
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

    public function searchDepartment(string $value = '')
    {
        $selectedOption = Department::where('id', $this->department_id)->get();

        return $this->departments = Department::query()
            ->where('faculty_id', $this->faculty_id)
            ->where('status', true)
            ->where(function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%");
            })
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);
    }

    public function save(): void
    {
        $this->setModel(new $this->pageModel);

        $this->saveOrUpdate(
            validationRules: [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|string',
                'status' => 'required|boolean',
                'department_id' => 'required|exists:departments,id',
                'address' => 'required|string',
                'phone' => 'required|string',
                'gender' => 'required|string',
                'nim' => 'required|string',
            ],

            beforeSave: function ($model, $prop) {
                $user = $prop->recordId ? User::find($model->user_id) : new User();
                $user->name = $prop->name;
                $user->email = $prop->email;
                if($prop->password != '') $user->password = $prop->password;
                $user->save();

                if ($prop->recordId == null) {
                    $user->assignRole('mahasiswa');
                }

                $model->user_id = $user->id;
            },
        );

        $this->modal = false;
    }

    public function delete(): void
    {
        $this->setModel(new User());
        $this->recordId = $this->pageModel->where('id', $this->recordId)->first()->user->id;

        $this->deleteData();

        $this->modal = false;
    }

    public function datas(): LengthAwarePaginator
    {
        return $this->pageModel->query()
            ->with('department.faculty', 'department', 'user')
            ->withAggregate('department', 'name')
            // ->withAggregate('faculty', 'name as faculty_name')
            ->withAggregate('user', 'name')
            ->where(function ($query) {
                $query->where('nim', 'like', "%{$this->search}%")
                    ->orWhereHas('department', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('department.faculty', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        return [
            ['key' => 'nim', 'label' => 'NIM', 'class' => 'w-64'],
            ['key' => 'user_name', 'label' => 'Nama', 'class' => 'w-64'],
            ['key' => 'department_name', 'label' => 'Prodi', 'class' => 'w-64'],
            ['key' => 'gender', 'label' => 'JK', 'class' => 'w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-64'],
            ['key' => 'created_at', 'label' => 'Dibuat pada', 'class' => 'w-64'],
        ];
    }

    public function with(): array
    {
        return [
            'datas' => $this->datas(),
            'headers' => $this->headers()
        ];
    }

}; ?>

@script
    <script>
        $js('create', () => {
            $wire.modal = true;
            $wire.recordId = null;
            $wire.name = '';
            $wire.email = '';
            $wire.password = '';
            $wire.department_id = null;
            $wire.faculty_id = null;
            $wire.address = '';
            $wire.phone = '';
            $wire.gender = 'Laki-laki';
            $wire.nim = '';
            $wire.status = true;
        });

        $js('edit', (data) => {
            // console.log(data);

            $wire.modal = true;
            $wire.recordId = data.id;
            $wire.name = data.user.name;
            $wire.email = data.user.email;
            $wire.password = '';
            $wire.faculty_id = data.department.faculty.id;
            $wire.searchDepartment();
            $wire.department_id = data.department.id;
            $wire.address = data.address;
            $wire.phone = data.phone;
            $wire.gender = data.gender;
            $wire.nim = data.nim;
            $wire.status = data.status == 1;
        });
    </script>
@endscript

<div>
    <x-header title="Mahasiswa" separator>
        <x-slot:actions>
            <x-button label="Create" @click="$js.create" responsive class="btn-primary" icon="fas.plus" />
        </x-slot:actions>
    </x-header>

    <div class="flex justify-end items-center gap-5">
        <x-input placeholder="Search..." wire:model.live="search" clearable icon="o-magnifying-glass" />
    </div>

    <!-- TABLE  -->
    <x-card class="mt-4" shadow>
        <x-table :headers="$headers" :rows="$datas" :sort-by="$sortBy" per-page="perPage" :per-page-values="[10, 25, 50, 100]"
            with-pagination @row-click="$js.edit($event.detail)"
            show-empty-text empty-text="Tidak Ada Data!">
            @scope('cell_status', $data)
                <x-status :status="$data->status" />
            @endscope
            @scope('cell_created_at', $data)
                <x-date-formatter :date="$data->created_at" format="d F Y" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" title="Form Mahasiswa" box-class="lg:w-full h-fit max-w-[800px]" without-trap-focus>
        <x-form wire:submit="save" no-separator>

            <div class="grid lg:grid-cols-2 gap-4">
                <div>
                    <x-input label="NIM" wire:model="nim" type="number" required />
                </div>
                <div>
                    <x-input label="Name" wire:model="name" required />
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-4">
                <div>
                    <x-input label="Email" wire:model="email" type="email" required />
                </div>
                <div>
                    <x-password label="Password" wire:model="password" right/>
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-4" x-data="{show: false}" x-effect="show = $wire.faculty_id != null">
                <div>
                    <x-choices
                    label="Fakultas"
                    wire:model="faculty_id"
                    :options="$faculties"
                    placeholder="Pilih Fakultas..."
                    search-function="searchFaculty"
                    @change-selection="$wire.searchDepartment(), $wire.department_id = null"
                    clearable
                    single
                    searchable />
                </div>
                <div x-show="show">
                    <x-choices
                    label="Program Studi"
                    wire:model="department_id"
                    :options="$departments"
                    placeholder="Pilih Program Studi..."
                    search-function="searchDepartment"
                    clearable
                    single
                    searchable />
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-4">
                <div>
                    <x-input label="No. Telepon" wire:model="phone" required />
                </div>
                <div>
                    <x-select label="Jenis Kelamin" wire:model="gender" :options="[
                        ['id' => 'Laki-laki', 'name' => 'Laki-laki'],
                        ['id' => 'Perempuan', 'name' => 'Perempuan'],
                        ]" required />
                </div>
            </div>
            <div>
                <x-textarea label="Alamat" wire:model="address" rows="3" required />
            </div>

            <div class="mt-3">
                <x-toggle label="Status" wire:model="status" hint="Nyala jika aktif" />
            </div>

            <x-slot:actions>
                <div x-data="{buttonDelete: false}" x-effect="buttonDelete = $wire.recordId != null">
                    <x-button x-show="buttonDelete" label="Delete" wire:click="delete" class="btn-error" wire:confirm="Are you sure?" />
                </div>
                <x-button label="Save" type="submit" spinner="save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
