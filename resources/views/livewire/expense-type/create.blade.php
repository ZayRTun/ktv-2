<x-modal wire:model="showExpenseTypeCreateForm" size="sm">
    <x-slot name="modalHeader">
        <h1 class="text-2xl font-semibold">
            Add an Expense Type
        </h1>
    </x-slot>

    <form wire:submit.prevent="create" class="space-y-4">
        <div class="grid grid-cols-1 gap-x-6">
            <div class="space-y-8 sm:space-y-5">
                <div class="space-y-6 sm:space-y-1">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8">
                        <x-form-input-comp class="col-span-8" wire:model.defer="expenseType.expense_type_name"
                                           label="Expense Type Name*" for="expenseType.expense_type_name" type="text" />
                    </div>
                </div>
            </div>
        </div>
        <x-slot name="modalAction">
            <button type="submit" wire:click="create"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Create
            </button>
        </x-slot>
    </form>
</x-modal>
