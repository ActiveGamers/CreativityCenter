@props([
    'title' => null,
    'description' => null,
    'icon' => 'spark',
    'tone' => 'brand',
    'role' => null,
])

@php
    $tones = [
        'brand' => 'border-brand-200 bg-brand-50/70 dark:border-brand-500/25 dark:bg-brand-950/50',
        'accent' => 'border-accent-200 bg-accent-50/70 dark:border-accent-500/25 dark:bg-accent-950/50',
        'success' => 'border-emerald-200 bg-emerald-50/70 dark:border-emerald-500/25 dark:bg-emerald-950/50',
        'neutral' => 'border-ink-900/10 bg-ink-50/70 dark:border-white/10 dark:bg-white/5',
    ];

    $iconTones = [
        'brand' => 'bg-brand-500/10 text-brand-600 dark:text-brand-300',
        'accent' => 'bg-accent-500/12 text-accent-600 dark:text-accent-300',
        'success' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-300',
        'neutral' => 'bg-ink-900/8 text-ink-600 dark:bg-white/10 dark:text-ink-100',
    ];

    $classes = $tones[$tone] ?? $tones['brand'];
    $iconClass = $iconTones[$tone] ?? $iconTones['brand'];
@endphp

<div @if (filled($role)) role="{{ $role }}" @endif
     {{ $attributes->merge(['class' => 'flex items-start gap-3.5 rounded-xl border p-4 sm:p-5 ' . $classes]) }}>
    @if (filled($icon))
        <span class="grid size-9 shrink-0 place-items-center rounded-lg {{ $iconClass }}">
            <x-icon :name="$icon" class="size-4.5" />
        </span>
    @endif

    <div class="flex min-w-0 flex-col gap-1">
        @if (filled($title))
            <p class="text-sm font-semibold leading-7 text-ink-900 dark:text-white">{{ $title }}</p>
        @endif

        @if (filled($description))
            <p class="text-xs leading-7 text-slate-600 dark:text-ink-200">{{ $description }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="mt-2">{{ $slot }}</div>
        @endif
    </div>
</div>
