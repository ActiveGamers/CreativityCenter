@props([
    'value' => 0,
    'suffix' => null,
    'prefix' => null,
    'label' => null,
])

@php
    /* The final value is rendered server-side so the number is readable even
       without JavaScript; counters.js replaces it with an animated count.
       Persian digits are normalised first so the value stays numeric. */
    $latin = strtr((string) $value, [
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
        '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
        '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
    ]);

    $numeric = is_numeric($latin) ? (float) $latin : 0.0;
    $formatted = number_format($numeric);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    <span class="flex items-baseline gap-1.5 font-display text-3xl font-extrabold leading-none tabular-nums text-ink-900 sm:text-4xl dark:text-white">
        @if (filled($prefix))
            <span class="text-xl font-bold text-brand-600 dark:text-brand-300">{{ $prefix }}</span>
        @endif

        <span data-counter="{{ (int) $numeric }}">{{ $formatted }}</span>

        @if (filled($suffix))
            <span class="text-xl font-bold text-brand-600 dark:text-brand-300">{{ $suffix }}</span>
        @endif
    </span>

    @if (filled($label))
        <span class="text-sm leading-7 text-ink-700 dark:text-ink-200">{{ $label }}</span>
    @endif
</div>
