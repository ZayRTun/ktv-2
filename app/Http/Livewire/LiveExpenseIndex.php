<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Traits\WithPrinting;
use Livewire\Component;

class LiveExpenseIndex extends Component
{
    use WithPrinting;
    public $expenses;
    public $expenseTypes;
    public $selectedExpenseTypeId;
    public $fromDate;
    public $toDate;

    public $expenseTypeSearch = '';

    public $selectedExpenseTypeName;

    protected $listeners = [
        'expenseCreated' => '$refresh',
        'expenseUpdated' => '$refresh',
        'expenseDeleted' => '$refresh',
    ];

    public function print()
    {
        $date = now()->format('Y-m-d');
        $data = [
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'selected_type' => $this->selectedExpenseTypeName ? $this->selectedExpenseTypeName : null,
            'expenses' => $this->expenses,
        ];

        return $this->printToPDF('pdf.expenses-list-pdf', $data, $date, 'Expenses-List', 'L');
    }

    public function updatedSelectedExpenseTypeId()
    {
        $this->selectedExpenseTypeName = $this->expenseTypes->where('id', $this->selectedExpenseTypeId)->first()->expense_type_name;
    }

    public function mount()
    {
        $this->toDate = today()->toDateString();
        $this->fromDate = today()->subWeek()->toDateString();
    }


    public function setFirstResult()
    {
        if (!empty($this->expenseTypeSearch)) {
            $expenseType = $this->expenseTypes->first();
            $this->expenseTypeSearch = $expenseType?->expense_type_name;
            $this->selectedExpenseTypeId = $expenseType?->id;
        } else {
            $this->reset(['expenseTypeSearch', 'selectedExpenseTypeId']);
        }
    }

    public function setExpenseType($id, $name)
    {
        $this->expenseTypeSearch = $name;
        $this->selectedExpenseTypeId = $id;
    }

    public function render()
    {
        $this->expenseTypes = ExpenseType::select('id', 'expense_type_name')
        ->when(strlen($this->expenseTypeSearch) > 2 ? $this->expenseTypeSearch : false, function ($query) {
            $query->where('expense_type_name', 'like', '%' . $this->expenseTypeSearch . '%');
        })
        ->orderBy('expense_type_name')
        ->get();

        $this->expenses = Expense::with('expenseType')
        ->when($this->selectedExpenseTypeId, function ($query) {
            $query->where('expense_type_id', $this->selectedExpenseTypeId);
        })
        ->whereDate('expense_date', '>=', $this->fromDate)
        ->whereDate('expense_date', '<=', $this->toDate)
        ->get();

        return view('livewire.live-expense-index');
    }
}
