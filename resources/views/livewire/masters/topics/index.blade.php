<?php

use App\Models\Topic;
use Mary\Traits\Toast;
use App\Models\Faculty;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Traits\CreateOrUpdate;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Title('Tema')] class extends Component {
    use Toast, CreateOrUpdate, WithPagination;

    public string $search = '';
    public bool $modal = false;
    public int $perPage = 10;
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public $pageModel;
    public Collection $faculties;

    //var
    public ?int $faculty_id = null;
    public string $name = '';
    public string $description = '';
    public bool $status = true;

    public function mount()
    {
        $this->pageModel = new Topic();
        $this->searchFaculty();
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

    public function save(): void
    {
        $this->setModel(new $this->pageModel);

        $this->saveOrUpdate(
            validationRules: [
                'faculty_id' => 'required|exists:faculties,id',
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|boolean'
            ],
        );

        $this->modal = false;
    }

    public function delete(): void
    {
        $this->setModel(new $this->pageModel);

        $this->deleteData();

        $this->modal = false;
    }

    public function datas(): LengthAwarePaginator
    {
        return $this->pageModel->query()
            ->with('faculty')
            ->withAggregate('faculty', 'name')
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('faculty', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        return [
            ['key' => 'faculty_name', 'label' => 'Fakultas', 'class' => 'w-64'],
            ['key' => 'name', 'label' => 'Nama', 'class' => 'w-64'],
            ['key' => 'description', 'label' => 'Deskripsi', 'class' => 'w-64'],
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
            $wire.faculty_id = null;
            $wire.name = '';
            $wire.description = '';
            $wire.status = true;
        });

        $js('edit', (data) => {
            $wire.modal = true;
            $wire.recordId = data.id;
            $wire.faculty_id = data.faculty.id;
            $wire.name = data.name;
            $wire.description = data.description;
            $wire.status = data.status == 1;
        });
    </script>
@endscript

<div>
    <x-header title="Tema" separator>
        <x-slot:actions>
            @can('create-topic')
            <x-button label="Create" @click="$js.create" responsive class="btn-primary" icon="fas.plus" />
            @endcan
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

    <x-modal wire:model="modal" title="Form Tema" box-class="w-full h-fit max-w-[600px]" without-trap-focus>
        <x-form wire:submit="save" no-separator>

            <div>
                <x-choices
                label="Fakultas"
                wire:model="faculty_id"
                :options="$faculties"
                placeholder="Pilih Fakultas..."
                search-function="searchFaculty"
                clearable
                single
                searchable />
            </div>

            <div>
                <x-input label="Name" wire:model="name"  />
            </div>

            <div>
                <x-textarea label="Deskripsi" wire:model="description" rows="3" />
            </div>

            <div class="mt-3">
                <x-toggle label="Status" wire:model="status" hint="Nyala jika aktif" />
            </div>

            <x-slot:actions>
                @can('delete')
                    <div x-data="{buttonDelete: false}" x-effect="buttonDelete = $wire.recordId != null">
                        <x-button x-show="buttonDelete" label="Delete" wire:click="delete" class="btn-error" wire:confirm="Are you sure?" />
                    </div>
                @endcan
                <x-button label="Save" type="submit" spinner="save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
