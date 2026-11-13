@props([
    'title' => null,
    'description' => null,
    'kicker' => 'در کنار شما هستیم',
    'primaryLabel' => null,
    'primaryHref' => null,
    'secondaryLabel' => null,
    'secondaryHref' => null,
])

@php
    $majorsUrl = \Illuminate\Support\Facades\Route::has('majors.index') ? route('majors.index') : url('/');
    $contactUrl = \Illuminate\Support\Facades\Route::has('contact.index') ? route('contact.index') : url('/');

    $primaryHref = $primaryHref ?: $majorsUrl;
    $secondaryHref = $secondaryHref ?: $contactUrl;

    $primaryLabel = $primaryLabel ?: 'رشته‌ها';
    $secondaryLabel = $secondaryLabel ?: 'تماس با ما';
@endphp

{{-- Dark petrol band. The `dark` class forces the dark palette inside the
     band, whatever the visitor's colour scheme is. --}}
<section {{ $attributes->merge(['class' => 'dark relative isolate overflow-hidden bg-ink-900 text-white']) }}>
    <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-32 end-[-10%] -z-10 h-80 w-[70%] hero-wash opacity-70" aria-hidden="true"></div>

    <div class="container-page flex flex-col items-start gap-8 py-14 lg:flex-row lg:items-center lg:justify-between lg:py-18">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-accent-300">
                <span class="h-px w-6 shrink-0 bg-accent-500"></span>
                <span>{{ $kicker }}</span>
            </span>

            <h2 class="mt-4 font-display text-2xl font-extrabold leading-[1.4] text-balance-fa sm:text-3xl lg:text-4xl">
                {{ $title }}
            </h2>

            @if (filled($description))
                <p class="mt-4 text-sm leading-8 text-ink-200/90 sm:text-base">{{ $description }}</p>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="flex shrink-0 flex-wrap items-center gap-3">{{ $slot }}</div>
        @else
            <div class="flex shrink-0 flex-wrap items-center gap-3">
                <x-button :href="$primaryHref" variant="primary" size="md" icon="arrow">{{ $primaryLabel }}</x-button>
                <x-button :href="$secondaryHref" variant="outline" size="md">{{ $secondaryLabel }}</x-button>
            </div>
        @endif
    </div>
</section>
