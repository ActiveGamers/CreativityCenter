@props([
    'items' => [],
    'class' => null,
    'variant' => 'hero',
])

@php
    /* `hero` renders on the dark petrol band of <x-page-hero />, `light` on the
       regular page surfaces. The default keeps the historical behaviour. */
    $onHero = $variant !== 'light';
@endphp

@if (filled($items))
    <nav aria-label="مسیر صفحه" {{ $attributes->merge(['class' => $class]) }}>
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs {{ $onHero ? 'text-ink-300' : 'text-slate-500 dark:text-ink-300' }}">
            @foreach ($items as $item)
                <li class="flex items-center gap-2">
                    @unless ($loop->first)
                        <x-icon name="arrow" class="size-3.5 shrink-0 opacity-40" />
                    @endunless

                    @if (filled(data_get($item, 'href')))
                        <a href="{{ data_get($item, 'href') }}"
                           class="transition-colors {{ $onHero ? 'hover:text-brand-300' : 'hover:text-brand-600 dark:hover:text-brand-300' }}">{{ data_get($item, 'label') }}</a>
                    @else
                        <span class="{{ $onHero ? 'text-white/90' : 'text-ink-900 dark:text-white' }}" aria-current="page">{{ data_get($item, 'label') }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
