@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'end',
])

@php
    $variants = [
        'primary' => 'bg-brand-500 text-white shadow-[0_1px_2px_rgb(7_30_42/0.10)] hover:bg-brand-600 focus-visible:outline-brand-600',
        'secondary' => 'bg-ink-900 text-white hover:bg-ink-800 focus-visible:outline-ink-800 dark:bg-white dark:text-ink-950 dark:hover:bg-ink-100',
        'outline' => 'border border-ink-900/20 bg-transparent text-ink-900 hover:border-brand-500 hover:text-brand-600 focus-visible:outline-brand-500 dark:border-white/25 dark:text-white dark:hover:border-brand-300 dark:hover:text-brand-200',
        'ghost' => 'bg-transparent text-ink-700 hover:bg-ink-900/5 focus-visible:outline-ink-500 dark:text-ink-100 dark:hover:bg-white/10',
    ];

    $sizes = [
        'sm' => 'h-9 gap-1.5 rounded-lg px-3.5 text-[0.8rem]',
        'md' => 'h-11 gap-2 rounded-xl px-5 text-sm',
        'lg' => 'h-13 gap-2.5 rounded-xl px-7 text-base',
    ];

    $base = 'inline-flex shrink-0 select-none items-center justify-center whitespace-nowrap font-medium transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-60';

    $classes = trim($base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']));

    $iconClasses = $size === 'sm' ? 'size-4 shrink-0' : 'size-5 shrink-0';
@endphp

@if (filled($href))
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => $classes]) }}>
        <span data-submit-spinner class="hidden size-4 shrink-0 animate-spin rounded-full border-2 border-current border-t-transparent" aria-hidden="true"></span>

        @if (filled($icon) && $iconPosition === 'start')
            <x-icon :name="$icon" :class="$iconClasses" />
        @endif

        <span data-submit-label class="inline-flex items-center gap-2">{{ $slot }}</span>

        @if (filled($icon) && $iconPosition === 'end')
            <x-icon :name="$icon" :class="$iconClasses" />
        @endif
    </a>
@else
    <button type="{{ $type }}"
            {{ $attributes->merge(['class' => $classes]) }}>
        <span data-submit-spinner class="hidden size-4 shrink-0 animate-spin rounded-full border-2 border-current border-t-transparent" aria-hidden="true"></span>

        @if (filled($icon) && $iconPosition === 'start')
            <x-icon :name="$icon" :class="$iconClasses" />
        @endif

        <span data-submit-label class="inline-flex items-center gap-2">{{ $slot }}</span>

        @if (filled($icon) && $iconPosition === 'end')
            <x-icon :name="$icon" :class="$iconClasses" />
        @endif
    </button>
@endif
