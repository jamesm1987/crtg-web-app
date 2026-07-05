<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public string $model;
    public array $columns;
    public array $searchable = [];
    public string $editRoute;
    public string $createRoute;
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public ?string $confirmingDeleteId = null;

    public function sortBy(string $field): void
    {

        if (! in_array($field, $this->columns)) {
            return;
        }

        if ($this->sortField === $field) {
            
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';

        } else {

            $this->sortField = $field;
            $this->sortDirection = 'asc';

        }
    }

    public function confirmDelete(string $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete($id): void
    {
        $record = $this->model::findOrFail($this->confirmingDeleteId);
        $this->authorize('delete', $record);
        $record->delete();

        $this->confirmingDeleteId = null;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function items()
    {
        return $this->model::query()
            ->when($this->search && $this->searchable, function ($q) {
                $q->where(function ($q) {
                    foreach ($this->searchable as $field) {
                        $q->orWhere($field, 'like', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }
};
?>

<div>
    <div class="flex justify-between mb-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." />
        <flux:button :href="route($createRoute)" wire:navigate variant="primary">Create</flux:button>
    </div>

    <flux:table>
        <flux:table.columns>
            @foreach ($columns as $column)
                <flux:table.column
                    sortable
                    :sorted="$sortField === $column"
                    :direction="$sortDirection"
                    wire:click="sortBy('{{ $column }}')"
                >
                    {{ ucfirst(str_replace('_', ' ', $column)) }}
                </flux:table.column>
            @endforeach
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->items as $item)
                <flux:table.row :key="$item->id">
                    @foreach ($columns as $column)
                        <flux:table.cell>{{ $item->$column }}</flux:table.cell>
                    @endforeach
                    <flux:table.cell>
                        <flux:button size="sm" :href="route($editRoute, $item)" wire:navigate>Edit</flux:button>
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete('{{ $item->id }}')">Delete</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                No records found matching your selection.
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{ $this->items->links() }}

    <flux:modal wire:model="confirmingDeleteId" name="confirm-delete">
        <flux:heading>Delete this record?</flux:heading>
        <flux:button wire:click="delete" variant="danger">Confirm</flux:button>
    </flux:modal>
</div>