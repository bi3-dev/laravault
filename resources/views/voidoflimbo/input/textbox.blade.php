@props(['id', 'type'])

<div class="flex">
    @isset($type)
        <span class="flex translate-x-1 items-center rounded-l-md bg-white px-3 text-center font-normal text-black">
            {{ $slot }}
        </span>
    @endisset
    <input id={{ $id }}
           type="{{ $type }}"
           {{ $attributes->merge(['class' => ' border-white rounded-md pl-3 text-white bg-black']) }} />
</div>
