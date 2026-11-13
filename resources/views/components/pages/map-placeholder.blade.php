@props([
    'address' => null,
    'mapUrl' => null,
    'title' => 'نشانی هنرستان',
])

@php
    $searchUrl = filled($address)
        ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address)
        : null;
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-ink-900/10 bg-white dark:border-white/10 dark:bg-ink-950/40']) }}>
    @if (filled($mapUrl))
        <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer"
           class="flex items-center justify-between gap-4 px-5 py-4 transition-colors hover:bg-ink-50 dark:hover:bg-white/5">
            <span class="flex items-center gap-3">
                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                    <x-icon name="location" class="size-5" />
                </span>
                <span class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-slate-500 dark:text-ink-300">{{ $title }}</span>
                    <span class="text-sm font-medium leading-7 text-ink-900 dark:text-white">{{ $address }}</span>
                </span>
            </span>

            <x-icon name="arrow" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
        </a>
    @else
        {{-- No map link has been published yet: an honest placeholder instead
             of a fake map. --}}
        <div class="flex flex-col gap-4 px-5 py-6">
            <span class="flex items-center gap-3">
                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-ink-50 text-ink-500 dark:bg-white/5 dark:text-brand-300">
                    <x-icon name="location" class="size-5" />
                </span>
                <span class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-slate-500 dark:text-ink-300">{{ $title }}</span>
                    <span class="text-sm font-medium leading-7 text-ink-900 dark:text-white">{{ $address }}</span>
                </span>
            </span>

            <p class="text-xs leading-7 text-slate-500 dark:text-ink-300">
                نقشهٔ آنلاین این نشانی هنوز در وب‌سایت منتشر نشده است. برای مسیریابی، نشانی بالا را در نقشهٔ گوشی جست‌وجو کنید.
            </p>

            @if (filled($searchUrl))
                <a href="{{ $searchUrl }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex w-fit items-center gap-1.5 text-xs font-semibold text-brand-600 transition-colors hover:text-brand-500 dark:text-brand-300">
                    <span>جست‌وجوی نشانی در نقشه‌ها</span>
                    <x-icon name="arrow" class="size-3.5 shrink-0" />
                </a>
            @endif
        </div>
    @endif
</div>
