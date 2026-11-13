@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'compact' => false,
])

<div {{ $attributes->merge(['class' => 'surface-card flex flex-col items-center gap-4 text-center ' . ($compact ? 'px-5 py-8' : 'px-6 py-14')]) }}>
    @if (filled($icon))
        <span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-ink-50 text-ink-500 dark:bg-white/5 dark:text-brand-300">
            <x-icon :name="$icon" class="size-7" />
        </span>
    @endif

    @if (filled($title))
        <h3 class="font-display text-lg font-bold text-ink-900 text-balance-fa dark:text-white">{{ $title }}</h3>
    @endif

    @if (filled($description))
        <p class="max-w-md text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $description }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-2 flex flex-wrap items-center justify-center gap-3">{{ $slot }}</div>
    @endif
</div>
