<label {{ $attributes->merge(['class' => 'font-bold flex gap-1 flex-col']) }}>
    <div>{{ $value }}</div>
    {{ $slot }}
</label>
