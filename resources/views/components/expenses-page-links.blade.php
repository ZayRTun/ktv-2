<x-desktop-sidebar-section title="">
    <div wire:ignore class="space-y-5">
        <x-nav-sidebar-link :href="route('expense.index')" :active="request()->routeIs('expense.index')">Expenses</x-nav-sidebar-link>
        <x-nav-sidebar-link :href="route('expense.type.index')" :active="request()->routeIs('expense.type.index')">Expense Types</x-nav-sidebar-link>
    </div>
</x-desktop-sidebar-section>
