<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LiveExpenseCreate extends Component
{
    use AuthorizesRequests;

    public $expense_date;
    public $expense_type_id;
    public $description;
    public $price;
    public $qty;
    public $amount = 0;
    public $created_user_id;

    public $expenseTypes;
    public $expenseTypeSearch = '';

    public $showExpenseCreateForm = false;

    protected $listeners = [
        'createExpense',
        'expenseTypeCreated' => '$refresh',
    ];

    protected $rules = [
        'expense_date' => 'required|date',
        'expense_type_id' => 'required|integer',
        'description' => 'nullable|string',
        'price' => 'required|numeric|between:0,999999999.99',
        'qty' => 'required|numeric',
        'created_user_id' => 'required|integer',
    ];

    public function create()
    {
        $this->created_user_id = auth()->id();

        $validated = $this->validate();

        Expense::create($validated);

        $this->emit('expenseCreated');
        $this->showExpenseCreateForm = false;
    }

    public function createExpense()
    {
        $this->authorize('create', Expense::class);

        $this->resetValidation();
        $this->reset();

        $this->showExpenseCreateForm = true;
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
        if ($this->showExpenseCreateForm) {
            $this->expenseTypes = ExpenseType::select('id', 'expense_type_name')
            ->when(strlen($this->expenseTypeSearch) > 2 ? $this->expenseTypeSearch : false, function ($query) {
                $query->where('expense_type_name', 'like', '%' . $this->expenseTypeSearch . '%');
            })
            ->orderBy('expense_type_name')
            ->get();
        }
        return view('livewire.live-expense-create');
    }
}
