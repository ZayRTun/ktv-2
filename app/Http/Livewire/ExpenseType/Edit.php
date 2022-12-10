<?php

namespace App\Http\Livewire\ExpenseType;

use App\Models\ExpenseType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public $expenseType;
    public $showExpenseTypeEditForm = false;

    protected $listeners = ['editExpenseType'];

    protected $rules = [
        'expenseType.expense_type_name' => 'required|string',
    ];

    public function update()
    {
        $this->expenseType->updated_user_id = auth()->id();

        $this->validate();

        $this->expenseType->update();

        $this->emit('reloadExpenseTypes');
        $this->showExpenseTypeEditForm = false;
    }

    public function editExpenseType(ExpenseType $expenseType)
    {
		$this->authorize('edit expense');
        $this->resetValidation();
        $this->reset();
        $this->expenseType = $expenseType;
        $this->showExpenseTypeEditForm = true;
    }

    public function render()
    {
        return view('livewire.expense-type.edit');
    }
}
