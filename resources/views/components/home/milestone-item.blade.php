@props([
    'item' => null,
    'scopeLabel' => '',
    'itemClass' => 'surface-card flex flex-col gap-3 p-5',
    'badgeIcon' => null,
])

@php
    $rankLabel = data_get($item, 'rank_label', '');
    $competition = data_get($item, 'competition', '');
@endphp

{{-- One achievement, shared by the ledger and the timeline variants: only
     the classes of the card and the icon of the scope badge differ. --}}
<li {{ $attributes->merge(['class' => $itemClass]) }}>
    <div class="flex flex-wrap items-center gap-2">
        @if (filled($scopeLabel))
            <x-badge tone="brand" :icon="$badgeIcon">{{ $scopeLabel }}</x-badge>
        @endif

        @if (filled($rankLabel))
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-accent-200 bg-accent-50 px-2.5 py-1 text-xs font-semibold text-accent-700 dark:border-accent-500/25 dark:bg-accent-950/60 dark:text-accent-300">
                <x-icon name="trophy" class="size-3.5 shrink-0" />
                <span>{{ $rankLabel }}</span>
            </span>
        @endif

        @if (filled($competition))
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-ink-900/10 px-2.5 py-1 text-xs font-medium text-ink-700 dark:border-white/10 dark:text-ink-100">
                <x-icon name="spark" class="size-3.5 shrink-0 text-accent-500" />
                <span>{{ $competition }}</span>
            </span>
        @endif
    </div>

    <h3 class="font-display text-base font-bold leading-8 text-balance-fa text-ink-900 dark:text-white">
        {{ data_get($item, 'title') }}
    </h3>

    @if (filled(data_get($item, 'description')))
        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ data_get($item, 'description') }}</p>
    @endif
</li>
