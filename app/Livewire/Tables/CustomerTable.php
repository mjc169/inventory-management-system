<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'name';

    public $sortAsc = false;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.tables.customer-table', [
            'users' => User::query()
                ->with('orders', 'quotations')
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
