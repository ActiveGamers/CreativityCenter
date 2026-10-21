@props([
    'value' => null,
    'suffix' => null,
])

@php
    $persianDigits = [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ];

    $text = strtr((string) $value, $persianDigits);
@endphp

<span {{ $attributes->merge(['class' => 'tabular-nums']) }}>{{ $text }}@if (filled($suffix))<span class="ms-1 font-normal">{{ $suffix }}</span>@endif</span>
