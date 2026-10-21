@props([
    'title' => null,
    'description' => null,
    'items' => [],
    'eyebrow' => null,
    'facts' => [],
])

{{-- Dark petrol band with a faint blueprint grid and one soft brand wash. --}}
<section {{ $attributes->merge(['class' => 'dark relative isolate overflow-hidden bg-ink-950 text-white']) }}>
    <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

    <div class="container-page py-14 sm:py-18 lg:py-24">
        @if (filled($items))
            <x-breadcrumb :items="$items" class="mb-7" />
        @endif

        @if (filled($eyebrow))
            <p class="inline-flex items-center gap-2 text-xs font-semibold text-accent-300">
                <span class="h-px w-6 shrink-0 bg-accent-500"></span>
                <span>{{ $eyebrow }}</span>
            </p>
        @endif

        <h1 class="max-w-4xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl lg:text-5xl {{ filled($eyebrow) ? 'mt-5' : '' }}">
            {{ $title }}
        </h1>

        @if (filled($description))
            <p class="mt-5 max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">
                {{ $description }}
            </p>
        @endif

        @if (filled($facts))
            <dl class="mt-9 flex flex-wrap items-start gap-x-12 gap-y-5 border-t border-white/10 pt-6">
                @foreach ($facts as $fact)
                    <div class="flex flex-col gap-1.5">
                        <dt class="text-xs text-ink-300">{{ data_get($fact, 'label') }}</dt>
                        <dd class="font-display text-sm font-bold text-white sm:text-base">{{ data_get($fact, 'value') }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        @if ($slot->isNotEmpty())
            <div class="mt-8">{{ $slot }}</div>
        @endif
    </div>
</section>
