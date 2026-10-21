@props([
    'statistics' => [],
    'kicker' => null,
    'title' => null,
    'description' => null,
    'note' => null,
    'variant' => 'plinth',
    'exclude' => [],
    'award' => null,
])

@php
    /*
     * The "at a glance" band.
     *
     * `exclude` removes statistics by their label — the rows stay in the
     * database, they are only filtered out of this view, so the same band can
     * be reused with a different selection on every page.
     *
     * `award` is the highlight box of the band. It defaults to the national
     * first rank in the Altā competition.
     */
    $excludedLabels = collect($exclude)
        ->map(fn ($label): string => trim((string) $label))
        ->filter()
        ->all();

    $visibleStatistics = collect($statistics)
        ->reject(fn ($statistic): bool => in_array(trim((string) data_get($statistic, 'label', '')), $excludedLabels, true))
        ->values();

    $award = $award ?? [
        'title' => 'کسب مقام اول الفتا در کشور',
        'detail' => 'سال تحصیلی ۱۴۰۳',
        'icon' => 'spark',
    ];

    $kicker = $kicker ?? 'در یک نگاه';
    $title = $title ?? 'دستاوردهای هنرستان در یک نگاه';
    $description = $description ?? 'رتبه‌های کشوری بر پایه نتایج سه سال اخیر (۱۴۰۱ تا ۱۴۰۳) ثبت شده‌اند.';

    $isPlinth = $variant === 'plinth';
@endphp

@if ($visibleStatistics->isNotEmpty() || filled($award))
    <section {{ $attributes->merge([
        'class' => $isPlinth
            ? 'border-y border-ink-900/8 bg-[var(--surface-sunken)] py-12 dark:border-white/8 lg:py-16'
            : 'py-12 lg:py-16',
    ]) }}>
        <div class="container-page">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold text-brand-600 dark:text-brand-300">
                        <span class="h-px w-6 shrink-0 bg-brand-500"></span>
                        <span>{{ $kicker }}</span>
                    </span>

                    <h2 class="mt-3 font-display text-2xl font-extrabold leading-[1.4] text-balance-fa text-ink-900 sm:text-3xl dark:text-white">
                        {{ $title }}
                    </h2>

                    @if (filled($description))
                        <p class="mt-3 text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $description }}</p>
                    @endif
                </div>

                @if (filled($note))
                    <p class="max-w-xs shrink-0 text-xs leading-6 text-slate-500 dark:text-ink-300 sm:text-end">{{ $note }}</p>
                @endif
            </div>

            {{-- Four statistics plus the award box: two columns on mobile,
                 three on tablet and a single row of five on wide screens. --}}
            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 xl:grid-cols-5">
                @if (filled($award))
                    <div class="col-span-2 flex flex-col justify-between gap-5 rounded-[0.875rem] border border-accent-300 bg-accent-50 p-5 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-6 xl:col-span-1 dark:border-accent-500/30 dark:bg-accent-950/40">
                        <div class="flex items-start justify-between gap-3">
                            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-accent-500/20 text-accent-700 dark:text-accent-200">
                                <x-icon :name="data_get($award, 'icon', 'spark')" class="size-5" />
                            </span>

                            @if (filled(data_get($award, 'detail')))
                                <span class="shrink-0 rounded-lg border border-accent-300 bg-white/70 px-2.5 py-1 text-xs font-semibold text-accent-800 dark:border-accent-500/30 dark:bg-white/5 dark:text-accent-200">
                                    {{ data_get($award, 'detail') }}
                                </span>
                            @endif
                        </div>

                        <p class="font-display text-lg font-extrabold leading-8 text-balance-fa text-accent-900 sm:text-xl dark:text-accent-100">
                            {{ data_get($award, 'title') }}
                        </p>
                    </div>
                @endif

                @foreach ($visibleStatistics as $statistic)
                    <x-stat-card
                        :label="$statistic->label"
                        :value="$statistic->value"
                        :description="$statistic->description"
                        :icon="$statistic->icon"
                        :variant="$loop->first ? 'feature' : 'default'"
                        data-reveal
                        style="--reveal-delay: {{ $loop->index * 70 }}ms"
                    />
                @endforeach
            </div>
        </div>
    </section>
@endif
