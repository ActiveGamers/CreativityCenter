@props([
    'kicker' => null,
    'title' => null,
    'description' => null,
    'align' => 'start',
])

<div {{ $attributes->merge([
    'class' => 'flex flex-col gap-4 ' . ($align === 'center' ? 'items-center text-center' : 'items-start'),
]) }}>
    @if (filled($kicker))
        <span class="inline-flex items-center gap-2 text-xs font-semibold text-brand-600 dark:text-brand-300">
            <span class="h-px w-6 shrink-0 bg-brand-500"></span>
            <span>{{ $kicker }}</span>
        </span>
    @endif

    @if (filled($title))
        <h2 class="max-w-3xl font-display text-2xl font-extrabold leading-[1.45] text-ink-900 text-balance-fa sm:text-3xl lg:text-4xl dark:text-white">
            {{ $title }}
        </h2>
    @endif

    @if (filled($description))
        <p class="max-w-2xl text-sm leading-8 text-slate-600 sm:text-base dark:text-ink-200 {{ $align === 'center' ? 'mx-auto' : '' }}">
            {{ $description }}
        </p>
    @endif

    @isset ($action)
        <div class="mt-1">{{ $action }}</div>
    @endisset
</div>
