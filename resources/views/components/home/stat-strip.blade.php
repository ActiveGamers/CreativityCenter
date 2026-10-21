@props([
    'statistics' => [],
    'kicker' => null,
    'title' => null,
    'description' => null,
    'note' => null,
    'variant' => 'plinth',
])

@php
    $statistics = collect($statistics)->values();

    $kicker = $kicker ?? 'در یک نگاه';
    $title = $title ?? 'دستاوردهای هنرستان در یک نگاه';
    $description = $description ?? 'رتبه‌های کشوری بر پایه نتایج سه سال اخیر (۱۴۰۱ تا ۱۴۰۳) و تعداد رشته‌ها و پایه‌ها بر پایه وضعیت فعلی هنرستان است.';

    $isPlinth = $variant === 'plinth';
@endphp

@if ($statistics->isNotEmpty())
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

            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:mt-10 xl:grid-cols-6">
                @foreach ($statistics as $statistic)
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
