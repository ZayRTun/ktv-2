@props(['label', 'for'])
<div
     x-data="{ show: false }"
     @click.outside="show = false"
     {{ $attributes->only('class') }}>
    @isset($label)
        <label for="{{ $for }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endisset
    <div @class(['relative', 'mt-1' => isset($label)])>
        <input id="{{ $for }}" type="text"
               @click="show = true"
               @keydown="show = true"
               @keydown.tab="show = false"
               @keydown.enter="$wire.setFirstResult().then(show = false)"
               @keydown.escape.window="show = false"
               x-ref="searchInput"
               autocomplete="off"
               {{ $attributes->only('placeholder') }}
               {{ $attributes->wire('model') }}
               class="w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-12 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm"
               role="combobox" aria-controls="options" aria-expanded="false">
        <button type="button"
                @click="show = !show; $focus.focus($refs.searchInput)"
                class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                      clip-rule="evenodd" />
            </svg>
        </button>

        <ul x-show="show" x-cloak
            class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            id="options" role="listbox">
            {{ $slot }}
        </ul>
    </div>
</div>
