<x-modal wire:model="showStockoutCreateForm" size="lg">
    @if ($showStockoutCreateForm)
        <x-slot name="modalHeader">
            <h1 class="text-2xl font-semibold">
                Add Stockout
            </h1>
        </x-slot>

        <form wire:submit.prevent="create" class="space-y-4">
            <div class="grid grid-cols-1 gap-x-6">
                <div class="space-y-8 sm:space-y-5">
                    <div class="space-y-6 sm:space-y-1">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                            <x-form-datepicker-comp class="sm:col-span-2" wire:model.defer="stock_out_date" label="Stockout Date*"
                                                    for="stock_out_date" />

                            <x-combobox class="col-span-2" wire:model="itemSearch" for="items" label="Item*">
                                @foreach ($items as $item)
                                    <li wire:key="{{ $item->id }}"
                                        @click="$wire.setItem({{ $item->id }}, '{{ $item->item_name }}').then(show = false)"
                                        class="relative cursor-default hover:text-white hover:bg-primary select-none py-2 pl-3 pr-9 text-gray-900"
                                        role="item" tabindex="-1">
                                        <span class="block truncate">{{ $item->item_name }}</span>
                                    </li>
                                @endforeach
                            </x-combobox>

                            <x-form-select-comp class="col-span-2" wire:model.defer="stock_out_type_id" :options="$stockOutTypes"
                                                label="Stockout Types*"
                                                optionValue="id"
                                                optionDisplay="stock_out_type_name"
                                                for="stock_out_type_id" />

                            <x-form-input-comp class="col-span-2" wire:model.lazy="qty" label="Qty*"
                                               for="qty"
                                               type="text" />

                            <x-form-input-comp class="col-span-2" wire:model.lazy="price" label="Cost*"
                                               for="price"
                                               type="text" />

                            <x-display-info-comp class="col-span-2" label="Amount"
                                                 displayValue="{{ number_format($qty * $price, 0, '.', ',') }}" />

                            <x-form-textarea-comp class="sm:col-span-6" wire:model.defer="remark" label="Remark" for="remark"
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
