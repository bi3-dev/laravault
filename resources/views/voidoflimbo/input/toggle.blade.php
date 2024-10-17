{{-- Perfect toggle --}}
<div class="flex items-center justify-start gap-2"
     x-data="{ isOn: @entangle($attributes->wire('model')) }">
    <label class="flex cursor-pointer items-center"
           for="toggle">
        <div class="relative">
            <input class="sr-only"
                   id="toggle"
                   type="checkbox"
                   x-bind:checked="isOn"
                   x-on:click="isOn = !isOn">
            <div class="block h-4 w-7 rounded-full transition-colors"
                 :class="{ 'bg-green-500': isOn, 'bg-gray-600': !isOn }"></div>
            <div class="dot absolute left-0 top-0 h-4 w-4 rounded-full bg-white transition-transform duration-200 ease-in-out"
                 x-bind:class="{ 'translate-x-full': isOn }"></div>
        </div>
    </label>
    {{ $slot }}
</div>

{{-- Example use case 
<x-voidoflimbo.input.toggle wire:model='isPaused'
                            wire:click="sessionStorage.setItem('isPaused', !sessionStorage.getItem('isPaused'))">
     Pause Refresh
</x-voidoflimbo.input.toggle> --}}
