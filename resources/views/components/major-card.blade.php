@props(['major'])

@php
    $title = data_get($major, 'title', '');
    $slug = data_get($major, 'slug', '');
    $subtitle = data_get($major, 'subtitle', '');
    $summary = data_get($major, 'summary', '');
    $icon = data_get($major, 'icon', 'chip');
    $accent = data_get($major, 'accent', 'brand');
    $coursesCount = data_get($major, 'courses_count', null);
    $seats = data_get($major, 'seats', null);

    $tones = [
        'brand' => ['chip' => 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300', 'bar' => 'bg-brand-500'],
        'accent' => ['chip' => 'bg-accent-50 text-accent-600 dark:bg-accent-950/60 dark:text-accent-300', 'bar' => 'bg-accent-500'],
        'ink' => ['chip' => 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-100', 'bar' => 'bg-ink-500'],
        'success' => ['chip' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-300', 'bar' => 'bg-emerald-500'],
    ];

    $tone = $tones[$accent] ?? $tones['brand'];

    $href = filled($slug) && \Illuminate\Support\Facades\Route::has('majors.show')
        ? route('majors.show', $slug)
        : url('/');
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'surface-card group relative flex flex-col gap-5 overflow-hidden p-6 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-7']) }}>
    <span class="absolute inset-x-0 top-0 h-1 {{ $tone['bar'] }}" aria-hidden="true"></span>

    <span class="flex items-start gap-4">
        <span class="grid size-14 shrink-0 place-items-center rounded-2xl {{ $tone['chip'] }}">
            <x-icon :name="$icon" class="size-7" />
        </span>

        <span class="flex min-w-0 flex-col gap-1.5">
            <span class="font-display text-lg font-extrabold leading-snug text-ink-900 text-balance-fa transition-colors group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-300">
                {{ $title }}
            </span>

            @if (filled($subtitle))
                <span class="text-xs font-medium text-brand-600 dark:text-brand-300">{{ $subtitle }}</span>
            @endif
        </span>
    </span>

    @if (filled($summary))
        <span class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $summary }}</span>
    @endif

    <span class="grid gap-3 border-t border-ink-900/8 pt-5 text-sm sm:grid-cols-2 dark:border-white/10">
        @if (filled($coursesCount))
            <span class="flex items-center gap-2.5 text-ink-700 dark:text-ink-100">
                <x-icon name="chart" class="size-4.5 shrink-0 text-brand-600 dark:text-brand-300" />
                <span>
                    <span class="font-semibold"><x-pages.fa-number :value="$coursesCount" /></span>
                    درس آموزشی
                </span>
            </span>
        @endif

        @if (filled($seats))
            <span class="flex items-center gap-2.5 text-ink-700 dark:text-ink-100">
                <x-icon name="user" class="size-4.5 shrink-0 text-brand-600 dark:text-brand-300" />
                <span>
                    ظرفیت اعلام‌شده:
                    <span class="font-semibold"><x-pages.fa-number :value="$seats" /></span>
                    نفر
                </span>
            </span>
        @endif
    </span>

    <span class="mt-auto inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 dark:text-brand-300">
        <span>مشاهده برنامه درسی رشته</span>
        <x-icon name="arrow" class="size-4 shrink-0 transition-transform duration-300 group-hover:-translate-x-1" />
    </span>
</a>
