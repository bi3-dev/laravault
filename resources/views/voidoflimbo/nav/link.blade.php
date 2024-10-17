@props(['active', 'title'])

@php
    $classes = ' inline-flex text-center items-center focus:outline-none transition duration-150 ease-in-out';
    $classes .= $active ?? false ? ' bg-slate-700 ' : ' hover:bg-slate-800 hover:scale-75 rounded-2xl ';
@endphp

<div class="relative inline-flex justify-center"
     id="{{ $title }}"
     x-data="{ popover: false }"
     x-on:mouseenter="popover = true"
     x-on:mouseleave="popover = false">
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
    <div class="absolute rounded-2xl border-2 bg-black px-3 py-2 text-center text-white"
         style=" z-index: 9999;"
         x-on:mouseenter="popover = false"
         x-anchor.bottom.offset.5="document.getElementById('{{ $title }}')"
         x-show="popover"
         x-cloak>
        {{ $title ?? 'Dashboard' }}
    </div>
</div>
