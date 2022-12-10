<?php

namespace App\Http\Livewire\ExpenseType;

use App\Models\ExpenseType;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Delete extends Component
{
    use AuthorizesRequests;

    public $expenseType;
    public $showExpenseTypeDeleteModal = false;

    protected $listeners = ['deleteExpenseType'];

    public function delete()
    {
        try {
            $this->expenseType->delete();
            $this->emit('reloadExpenseTypes');
            $this->showExpenseTypeDeleteModal = false;
        } catch (QueryException $queryException) {
            $this->showExpenseTypeDeleteModal = false;
            $this->dispatchBrowserEvent('failure-notify', ['title' => "Expense Type Delete Unsuccessful", 'body' => "Cannot delete expense type cause other related data exist. Please delete related data first to delete this expense type."]);
        } catch (\Exception $exception) {
            dd(get_class($exception));
        }
    }

    public function deleteExpenseType(ExpenseType $expenseType)
    {
        $this->authorize('delete expense');

        $this->expenseType = $expenseType;
        $this->showExpenseTypeDeleteModal = true;
    }

    public function render()
    {
        return view('livewire.expense-type.delete');
    }
}
