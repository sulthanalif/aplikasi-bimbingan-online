<?php

use App\Models\Topic;
use App\Models\Thesis;
use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Traits\CreateOrUpdate;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Title('Pengajuan Judul')] class extends Component {
    use Toast, CreateOrUpdate, WithPagination;

    public string $search = '';
    public bool $modal = false;
    public int $perPage = 10;
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public $pageModel;
    public Collection $topics;

    //var
    public ?int $topic_id = null;
    public string $title = '';
    public string $student = '';
    // public bool $status = true;/


    public function mount()
    {
        $this->pageModel = new Thesis();
        $this->searchTopic();
    }

    public function searchTopic(string $value = '')
    {
        $selectedOption = Topic::where('id', $this->topic_id)->get();

        return $this->topics = Topic::query()
            ->where('status', true)
            ->when(auth()->user()->role == 'mahasiswa', function ($query) {
                $query->where('faculty_id', auth()->user()->student?->department?->faculty?->id);
            })
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
                'topic_id' => 'required|exists:topics,id',
                'title' => 'required|string',
            ],
            beforeSave: function ($model, $prop) {
                $model->student_id = auth()->user()->student?->id;
            },
        );

        $this->modal = false;
    }

    public function datas(): LengthAwarePaginator
    {
        return $this->pageModel->query()
            ->with('student', 'topic')
            ->withAggregate('topic', 'name')
            ->withAggregate('student', 'name')
            ->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhereHas('topic', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        $headers = [
            ['key' => 'title', 'label' => 'Judul', 'class' => 'w-64'],
            ['key' => 'topic_name', 'label' => 'Tema', 'class' => 'w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-64'],
            ['key' => 'created_at', 'label' => 'Dibuat pada', 'class' => 'w-64'],
        ];

        if (auth()->user()->role != 'mahasiswa') {
            $headers = array_merge([
                ['key' => 'student_name', 'label' => 'Mahasiswa', 'class' => 'w-64'],
            ], $headers);
        }

        return $headers;
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
            $wire.title = '';
            $wire.topic_id = null;
            // $wire.status = true;
        });

        $js('detail', (data) => {
            $wire.modal = true;
            $wire.recordId = data.id;
            $wire.topic_id = data.topic.id;
            $wire.title = data.title;
            // $wire.status = data.status == 1;
        });
    </script>
@endscript

<div>
    <x-header title="Pengajuan Judul" separator>
        <x-slot:actions>
            @can('create-thesis')
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
            @scope('cell_created_at', $data)
                <x-date-formatter :date="$data->created_at" format="d F Y" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" title="Form Pengajuan Judul" box-class="w-full h-fit max-w-[600px]" without-trap-focus>
        <x-form wire:submit="save" no-separator>

            <div>
                <x-choices
                label="Tema"
                wire:model="topic_id"
                :options="$topics"
                placeholder="Pilih Tema..."
                search-function="searchTopic"
                clearable
                single
                searchable />
            </div>

            <div>
                <x-textarea label="Judul" wire:model="title" rows='3'  />
            </div>

            <x-slot:actions>
                <x-button label="Submit" type="submit" spinner="save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
