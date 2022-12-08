<?php

namespace App\Http\Livewire\ExpenseType;

use App\Models\ExpenseType;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'reloadExpenseTypes' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.expense-type.index', [
            'expenseTypes' => ExpenseType::withCount('expenses')
                ->get()
        ]);
    }
}
