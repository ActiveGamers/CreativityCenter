@props([
    'label' => null,
    'value' => null,
    'description' => null,
    'icon' => null,
    'variant' => 'default',
])

@php
    /* `feature` inverts the card into an ink plaque with an amber rule, so the
       single most important figure of a set can carry the whole band. */
    $isFeature = $variant === 'feature';

    $shell = $isFeature
        ? 'relative flex flex-col gap-3 overflow-hidden rounded-[0.875rem] border border-ink-900 bg-ink-900 p-5 text-white shadow-[var(--shadow-card)] transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-6 dark:border-accent-500/35 dark:bg-ink-950'
        : 'surface-card flex flex-col gap-3 p-5 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-6';
@endphp

<div {{ $attributes->merge(['class' => $shell]) }}>
    @if ($isFeature)
        <span class="absolute inset-x-0 top-0 h-1 bg-accent-500" aria-hidden="true"></span>
    @endif

    @if (filled($icon))
        <span class="grid size-11 shrink-0 place-items-center rounded-xl {{ $isFeature ? 'bg-accent-500/15 text-accent-300' : 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300' }}">
            <x-icon :name="$icon" class="size-5" />
        </span>
    @endif

    <span class="font-display text-3xl font-extrabold leading-none tabular-nums sm:text-4xl {{ $isFeature ? 'text-white' : 'text-ink-900 dark:text-white' }}">
        {{ $value }}
    </span>

    @if (filled($label))
        <span class="text-sm font-semibold {{ $isFeature ? 'text-ink-100' : 'text-ink-800 dark:text-ink-100' }}">{{ $label }}</span>
    @endif

    @if (filled($description))
        <p class="text-xs leading-6 {{ $isFeature ? 'text-ink-300' : 'text-slate-500 dark:text-ink-300' }}">{{ $description }}</p>
    @endif
</div>
