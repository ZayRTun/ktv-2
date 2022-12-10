<x-page-layout>
    <x-slot:staticSidebarContent>
        <x-expenses-page-links />
    </x-slot:staticSidebarContent>


    <x-content-header-section>
        <div class="sm:flex-none">
            @can('add expense')
                <button wire:click="$emit('createExpenseType')" type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 sm:w-auto">
                    Add Expense Type
                </button>
            @endcan
        </div>

    </x-content-header-section>


    <div class="max-w-2xl">

        <x-sticky-table-wrapper>
            <thead class="bg-gray-50">
                <tr class="divide-x">
                    <x-th width="50px" align="center">Sr. No</x-th>
                    <x-th width="50px" align="center">Reg No</x-th>
                    <x-th width="150px">Expense Type</x-th>
                    <x-th width="200px"></x-th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($expenseTypes as $index => $type)
                    <tr
                        class="divide-x bg-white text-gray-900">
                        <x-td-slim-with-align align="center">{{ $index + 1 }}</x-td-slim-with-align>
                        <x-td-slim-with-align align="center">{{ $type->id }}</x-td-slim-with-align>
                        <x-td>{{ $type->expense_type_name }}</x-td>
                        <x-td>
                            @can('edit expense')
                                <button type="button" wire:click="$emit('editExpenseType', {{ $type->id }})"
                                        class="text-primary hover:text-blue-900">Edit</button>
                            @endcan

                            @can('delete expense')
                                <button type="button" wire:click="$emit('deleteExpenseType', {{ $type->id }})"
                                        class="ml-3 text-primary hover:text-blue-900">Delete</button>
                            @endcan
                        </x-td>
                    </tr>
                @endforeach
            </tbody>
        </x-sticky-table-wrapper>
    </div>

    <livewire:expense-type.create />
    <livewire:expense-type.edit />
    <livewire:expense-type.delete />
</x-page-layout>
