<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LiveExpenseEdit extends Component
{
    use AuthorizesRequests;

    public $expense_id;
    public $expense_date;
    public $expense_type_id;
    public $description;
    public $price;
    public $qty;
    public $updated_user_id;

    public $expenseTypes;
    public $expenseTypeSearch = '';

    public $showExpenseEditForm = false;

    protected $listeners = ['editExpense'];

    protected $rules = [
        'expense_date' => 'required|date',
        'expense_type_id' => 'required|integer',
        'description' => 'nullable|string',
        'price' => 'required|numeric|between:0,999999999.99',
        'qty' => 'nullable|numeric',
        'updated_user_id' => 'required|integer',
    ];

    public function update()
    {
        $this->updated_user_id = auth()->id();

        $validated = $this->validate();

        Expense::find($this->expense_id)->update($validated);

        $this->emit('expenseUpdated');
        $this->showExpenseEditForm = false;
    }

    public function editExpense(Expense $expense)
    {
        $this->authorize('update', $expense);

        $this->resetValidation();
        $this->reset();

        $this->fill($expense);
        $this->expenseTypeSearch = $expense->expenseType?->expense_type_name;
        $this->expense_id = $expense->id;

        $this->showExpenseEditForm = true;
    }

    public function setFirstResult()
    {
        if (!empty($this->expenseTypeSearch)) {
            $expenseType = $this->expenseTypes->first();
            $this->expenseTypeSearch = $expenseType?->expense_type_name;
            $this->expense_type_id = $expenseType?->id;
        } else {
            $this->reset(['expenseTypeSearch', 'expense_type_id']);
        }
    }

    public function setExpenseType($id, $name)
    {
        $this->expenseTypeSearch = $name;
        $this->expense_type_id = $id;
    }

    public function render()
    {
        if ($this->showExpenseEditForm) {
            $this->expenseTypes = ExpenseType::select('id', 'expense_type_name')
            ->when(strlen($this->expenseTypeSearch) > 2 ? $this->expenseTypeSearch : false, function ($query) {
                $query->where('expense_type_name', 'like', '%' . $this->expenseTypeSearch . '%');
            })
            ->orderBy('expense_type_name')
            ->get();
        }

        return view('livewire.live-expense-edit');
    }
}
