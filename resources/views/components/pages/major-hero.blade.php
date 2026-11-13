@props([
    'major' => null,
    'items' => [],
    'stats' => [],
])

@php
    $icon = data_get($major, 'icon', 'chip');
    $accent = data_get($major, 'accent', 'brand');
    $title = data_get($major, 'title', '');
    $subtitle = data_get($major, 'subtitle', '');
    $summary = data_get($major, 'summary', '');

    $tones = [
        'brand' => 'bg-brand-500/15 text-brand-200 ring-brand-400/30',
        'accent' => 'bg-accent-500/15 text-accent-200 ring-accent-400/30',
        'ink' => 'bg-white/10 text-ink-100 ring-white/20',
        'success' => 'bg-emerald-500/15 text-emerald-200 ring-emerald-400/30',
    ];

    $tone = $tones[$accent] ?? $tones['brand'];
@endphp

{{-- The dark petrol band of the major page: the same depth language as
     <x-page-hero /> plus the identity of a single major. --}}
<section {{ $attributes->merge(['class' => 'dark relative isolate overflow-hidden bg-ink-950 text-white']) }}>
    <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

    <div class="container-page py-12 sm:py-16 lg:py-20">
        @if (filled($items))
            <x-breadcrumb :items="$items" class="mb-8" />
        @endif

        <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between lg:gap-14">
            <div class="flex max-w-3xl flex-col gap-6">
                <span class="flex items-center gap-4">
                    <span class="grid size-16 shrink-0 place-items-center rounded-2xl ring-1 {{ $tone }}">
                        <x-icon :name="$icon" class="size-8" />
                    </span>

                    <span class="flex flex-col gap-1.5">
                        @if (filled($subtitle))
                            <span class="text-xs font-semibold text-brand-300">{{ $subtitle }}</span>
                        @endif
                        <span class="text-xs text-ink-300">رشته تحصیلی هنرستان</span>
                    </span>
                </span>

                <h1 class="font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl lg:text-5xl">
                    {{ $title }}
                </h1>

                @if (filled($summary))
                    <p class="max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">{{ $summary }}</p>
                @endif
            </div>

            @if (filled($stats))
                <dl class="grid shrink-0 gap-4 sm:grid-cols-3 lg:w-72 lg:grid-cols-1">
                    @foreach ($stats as $stat)
                        <div class="rounded-xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur-sm">
                            <dt class="text-xs text-ink-300">{{ data_get($stat, 'label') }}</dt>
                            <dd class="mt-1 font-display text-lg font-extrabold text-white">
                                {{ data_get($stat, 'value') }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    </div>
</section>
