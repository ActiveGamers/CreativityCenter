@props([
    'icon' => 'spark',
    'label' => null,
    'value' => null,
    'href' => null,
    'ltr' => false,
])

@php
    $content = $slot->isNotEmpty() ? trim($slot) : (string) $value;
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3.5']) }}>
    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
        <x-icon :name="$icon" class="size-5" />
    </span>

    <div class="flex min-w-0 flex-col gap-1">
        @if (filled($label))
            <span class="text-xs font-medium text-slate-500 dark:text-ink-300">{{ $label }}</span>
        @endif

        <span class="text-sm font-medium leading-7 text-ink-900 dark:text-white">
            @if (filled($href))
                <a href="{{ $href }}" class="transition-colors hover:text-brand-600 dark:hover:text-brand-300">{{ $content }}</a>
            @elseif ($ltr)
                <bdi dir="ltr" class="tabular-nums">{{ $content }}</bdi>
            @else
                {{ $content }}
            @endif
        </span>
    </div>
</div>
