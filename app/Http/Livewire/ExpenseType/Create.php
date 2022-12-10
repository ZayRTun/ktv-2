<?php

namespace App\Http\Livewire\ExpenseType;

use App\Models\ExpenseType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $expenseType;
    public $showExpenseTypeCreateForm = false;

    protected $listeners = ['createExpenseType'];

    protected $rules = [
        'expenseType.expense_type_name' => 'required|string',
    ];

    public function create()
    {
        $this->expenseType->created_user_id = auth()->id();

        $this->validate();

        $this->expenseType->save();

        $this->emit('reloadExpenseTypes');
        $this->showExpenseTypeCreateForm = false;
    }

    public function createExpenseType()
    {
        $this->authorize('add expense');

        $this->resetValidation();
        $this->reset();
        $this->expenseType = new ExpenseType();
        $this->showExpenseTypeCreateForm = true;
    }

    public function render()
    {
        return view('livewire.expense-type.create');
    }
}
