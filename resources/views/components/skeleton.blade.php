@props([
    'type' => 'text',
    'lines' => 1,
])

@php
    $lineCount = max(1, (int) $lines);

    $shimmer = 'animate-pulse bg-ink-900/8 dark:bg-white/8';
@endphp

@if ($type === 'title')
    <span class="block h-8 w-2/3 rounded-lg {{ $shimmer }}" aria-hidden="true"></span>
@elseif ($type === 'card')
    <div class="surface-card flex flex-col gap-4 p-5" aria-hidden="true">
        <span class="block aspect-[16/10] w-full rounded-lg {{ $shimmer }}"></span>
        <span class="block h-4 w-3/4 rounded {{ $shimmer }}"></span>
        <span class="block h-4 w-full rounded {{ $shimmer }}"></span>
        <span class="block h-4 w-2/3 rounded {{ $shimmer }}"></span>
    </div>
@elseif ($type === 'post')
    <div class="surface-card flex h-full flex-col overflow-hidden" aria-hidden="true">
        <span class="block h-14 w-full border-b border-ink-900/6 bg-ink-900/4 {{ $shimmer }} dark:border-white/6"></span>
        <span class="flex flex-col gap-3 p-5">
            <span class="block h-5 w-24 rounded-full {{ $shimmer }}"></span>
            <span class="block h-4 w-4/5 rounded {{ $shimmer }}"></span>
            <span class="block h-4 w-full rounded {{ $shimmer }}"></span>
            <span class="block h-4 w-2/3 rounded {{ $shimmer }}"></span>
        </span>
    </div>
@elseif ($type === 'row')
    <div class="surface-card flex items-center gap-4 p-4" aria-hidden="true">
        <span class="block size-11 shrink-0 rounded-xl {{ $shimmer }}"></span>
        <span class="flex flex-1 flex-col gap-2">
            <span class="block h-4 w-3/5 rounded {{ $shimmer }}"></span>
            <span class="block h-3 w-2/5 rounded {{ $shimmer }}"></span>
        </span>
    </div>
@elseif ($type === 'stat')
    <div class="surface-card flex flex-col gap-3 p-5" aria-hidden="true">
        <span class="block size-11 rounded-xl {{ $shimmer }}"></span>
        <span class="block h-8 w-20 rounded {{ $shimmer }}"></span>
        <span class="block h-4 w-32 rounded {{ $shimmer }}"></span>
    </div>
@else
    <span class="flex flex-col gap-2" aria-hidden="true">
        @for ($index = 0; $index < $lineCount; $index++)
            @php $width = ($lineCount > 1 && $index === $lineCount - 1) ? 'w-3/5' : 'w-full'; @endphp
            <span class="block h-4 rounded {{ $shimmer }} {{ $width }}"></span>
        @endfor
    </span>
@endif
