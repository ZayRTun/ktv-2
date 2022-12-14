<x-modal wire:model="showExpenseCreateForm" size="lg">
    @if ($showExpenseCreateForm)
        <x-slot name="modalHeader">
            <h1 class="text-2xl font-semibold">
                Add Expense
            </h1>
        </x-slot>

        <form wire:submit.prevent="create" class="space-y-4">
            <div class="grid grid-cols-1 gap-x-6">
                <div class="space-y-8 sm:space-y-5">
                    <div class="space-y-6 sm:space-y-1">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-8">

                            <x-form-datepicker-comp class="sm:col-span-2" wire:model.defer="expense_date" label="Expense Date*"
                                                    for="expense_date" />

                            {{-- <x-form-select-comp class="col-span-2" wire:model.defer="expense_type_id"
                                                :options="$expenseTypes"
                                                optionValue="id"
                                                optionDisplay="expense_type_name"
                                                label="Expense Types*"
                                                for="expense_type_id">
                                <x-slot name="labelButton">
                                    <button wire:click="$emit('createExpenseType')"
                                            type="button"
                                            class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                             viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                            </x-form-select-comp> --}}
                            <x-combobox class="col-span-2" wire:model="expenseTypeSearch" for="expenseTypes" label="Expense Types*">
                                @foreach ($expenseTypes as $expenseType)
                                    <li wire:key="{{ $expenseType->id }}"
                                        @click="$wire.setExpenseType({{ $expenseType->id }}, '{{ $expenseType->expense_type_name }}').then(show = false)"
                                        class="relative cursor-default hover:text-white hover:bg-primary select-none py-2 pl-3 pr-9 text-gray-900"
                                        role="expenseType" tabindex="-1">
                                        <span class="block truncate">{{ $expenseType->expense_type_name }}</span>
                                    </li>
                                @endforeach
                            </x-combobox>

                            <x-form-input-comp class="col-span-1" wire:model.lazy="qty" label="Qty"
                                               for="qty"
                                               type="text" />

                            <x-form-input-comp class="col-span-1" wire:model.lazy="price" label="Price*"
                                               for="price"
                                               type="text" />


                            <x-display-info-comp class="col-span-2" label="Amount"
                                                 displayValue="{{ number_format($qty * $price, 0, '.', ',') }}" />

                            <x-form-textarea-comp class="sm:col-span-8" wire:model.defer="description" label="Description" for="description"
                                                  rows="3"
                                                  type="text" />

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <x-slot name="modalAction">
            <button type="submit" wire:click="create"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Create
            </button>
        </x-slot>
    @endif
</x-modal>
