@props([
    'tone' => 'neutral',
    'icon' => null,
])

@php
    $tones = [
        'accent' => 'border-accent-200 bg-accent-50 text-accent-700 dark:border-accent-500/25 dark:bg-accent-950/60 dark:text-accent-300',
        'brand' => 'border-brand-200 bg-brand-50 text-brand-700 dark:border-brand-500/25 dark:bg-brand-950/60 dark:text-brand-300',
        'neutral' => 'border-slate-200 bg-slate-100 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/25 dark:bg-emerald-950/60 dark:text-emerald-300',
    ];

    $classes = $tones[$tone] ?? $tones['neutral'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-medium ' . $classes]) }}>
    @if (filled($icon))
        <x-icon :name="$icon" class="size-3.5 shrink-0" />
    @endif

    <span>{{ $slot }}</span>
</span>
