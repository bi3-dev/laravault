@props([
    'textWhite' => false,
    'type' => 'button',
])

@php
    $buttonClass = '';

    if ($textWhite) {
        $buttonClass .= ' text-white ';
    }

@endphp

<button type={{ $type }}
        {{ $attributes->merge(['class' => 'block select-none rounded-md cursor-pointer hover:scale-110 ' . $buttonClass]) }}>
    {{ $slot }}
</button>
