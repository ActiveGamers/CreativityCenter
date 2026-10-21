@props([
    'href' => null,
    'linkLabel' => 'برنامه‌های آموزشی',
])

@php
    $toFa = fn ($value): string => strtr((string) $value, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);

    /* The three grade levels of the school, in curriculum order. The copy
       describes the shape of the path; the syllabus itself lives on the
       education page. */
    $steps = [
        \App\Enums\GradeLevel::Tenth->value => [
            'label' => \App\Enums\GradeLevel::Tenth->label(),
            'title' => 'ورود به مبانی تخصصی',
            'description' => 'آشنایی با مبانی رشته و دروس پایه؛ ساختن پایه‌ای که سه سال بعد روی آن ساخته می‌شود.',
        ],
        \App\Enums\GradeLevel::Eleventh->value => [
            'label' => \App\Enums\GradeLevel::Eleventh->label(),
            'title' => 'تخصصی‌تر شدن',
            'description' => 'تعمیق دانش تخصصی و ورود جدی‌تر به کارگاه‌های عملی هر رشته.',
        ],
        \App\Enums\GradeLevel::Twelfth->value => [
            'label' => \App\Enums\GradeLevel::Twelfth->label(),
            'title' => 'پروژه و جمع‌بندی',
            'description' => 'پروژه‌های عملی پایانی و آمادگی برای کنکور و ورود به دنیای کار.',
        ],
    ];
@endphp

<ol class="relative grid gap-8 sm:grid-cols-3 sm:gap-6 lg:gap-8">
    {{-- The rail that ties the three years together. --}}
    <span class="pointer-events-none absolute inset-x-0 top-[1.375rem] hidden h-px bg-ink-900/12 sm:block dark:bg-white/12" aria-hidden="true"></span>

    @foreach ($steps as $step)
        <li class="relative flex flex-col items-start" data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
            <span class="relative z-10 grid size-11 shrink-0 place-items-center rounded-full border border-brand-200 bg-white font-display text-sm font-extrabold tabular-nums text-brand-600 shadow-[var(--shadow-card)] dark:border-brand-500/35 dark:bg-ink-950 dark:text-brand-300">
                {{ $toFa($loop->index + 1) }}
            </span>

            <span class="mt-5 text-xs font-semibold text-brand-600 dark:text-brand-300">پایه {{ $step['label'] }}</span>

            <h3 class="mt-2 font-display text-lg font-bold leading-8 text-ink-900 dark:text-white">
                {{ $step['title'] }}
            </h3>

            <p class="mt-3 max-w-sm text-sm leading-8 text-slate-600 dark:text-ink-200">
                {{ $step['description'] }}
            </p>
        </li>
    @endforeach
</ol>

@if (filled($href))
    <div class="mt-9">
        <x-button :href="$href" variant="ghost" size="sm" icon="arrow" iconPosition="end">{{ $linkLabel }}</x-button>
    </div>
@endif
