@props([
    'width' => '50%',
    'path' => 'img/',
    'filename' => 'logo',
    'format' => 'png',
])

@php
    if (isset($attributes['light'])) {
        $filename .= 'light';
    } elseif (isset($attributes['dark'])) {
        $filename .= 'dark';
    }
@endphp

<div class="my-auto flex h-max justify-center">
    <img
        src="{{ asset($path . $filename . '.' . $format) }}"
        alt="logo"
        {{ $attributes->merge(['class' => 'cursor-pointer']) }}
        width="{{ $width }}"
    >
</div>
