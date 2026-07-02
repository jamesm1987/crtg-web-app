<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public string $model;
    public array $columns = [];

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public function mount(string $model, array $columns)
    {
        $this->model = $model;
        $this->columns = $columns;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sort(string $column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function records()
    {
        $query = $this->model::query();

        if ($this->search) {
            $query->where(function ($q) {
                foreach ($this->columns as $index => $column) {
                    if (in_array($column, ['created_at', 'updated_at', 'deleted_at', 'id'])) {
                        continue;
                    }

                    $method = $q->getBindings() ? 'orWhere' : 'where';
                    $q->$method($column, 'like', "%{$this->search}%");
                }
            });
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);
    }
};
?>

<div>
    <div class="mb-5 max-w-xs">
        <flux:input 
            wire:model.live.debounce.300ms="search" 
            placeholder="Search..." 
            icon="magnifying-glass" 
        />
    </div>

    <x-table>
        <x-slot:header>
            @foreach($columns as $column)
                <x-table.heading 
                    sortable 
                    wire:click="sort('{{ $column }}')" 
                    :direction="$sortBy === $column ? $sortDirection : null"
                >
                    {{ Str::headline($column) }}
                </x-table.heading>
            @endforeach
        </x-slot:header>

        <x-slot:body>
            @forelse ($this->records as $record)
                <x-table.row>
                    @foreach($columns as $column)
                        {{-- Apply the consistent text alignments and colors directly to every cell --}}
                        <x-table.cell class="px-4 py-3 font-medium text-white text-left">
                            {{ $record->$column }}
                        </x-table.cell>
                    @endforeach
                </x-table.row>
            @empty
                <x-table.row-empty :colspan="count($columns)">
                    No records found matching your selection.
                </x-table.row-empty>
            @endforelse
        </x-slot:body>
    </x-table>

    <!-- Uses cached computed property value cleanly -->
    @if($this->records->hasPages())
        <nav class="mt-4" aria-label="Pagination Navigation">
            {{ $this->records->links() }}
        </nav>
    @endif
</div>