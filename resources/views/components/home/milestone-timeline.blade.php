@props([
    'achievements' => [],
    'variant' => 'timeline',
    'emptyTitle' => 'هنوز دستاوردی ثبت نشده است.',
    'emptyDescription' => 'به‌زودی فهرست افتخارات هنرستان در این بخش منتشر می‌شود.',
])

@php
    $toFa = fn ($value): string => strtr((string) $value, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);

    $scopes = [
        'national' => 'کشوری',
        'province' => 'استانی',
        'provincial' => 'استانی',
        'regional' => 'منطقه‌ای',
        'city' => 'شهری',
        'school' => 'مدرسه‌ای',
        'international' => 'بین‌المللی',
    ];

    $resolveScope = function ($scope) use ($scopes): string {
        if (is_object($scope) && method_exists($scope, 'label')) {
            return (string) $scope->label();
        }

        if ($scope instanceof \BackedEnum) {
            return $scopes[$scope->value] ?? (string) $scope->value;
        }

        return is_string($scope) ? ($scopes[$scope] ?? $scope) : '';
    };

    /* Grouped by year, newest first — the order the controller already
       delivers, kept here so both variants read the same way. */
    $grouped = collect($achievements)
        ->filter(fn ($item) => filled(data_get($item, 'title')))
        ->groupBy(fn ($item) => data_get($item, 'year'))
        ->sortKeysDesc();
@endphp

@if ($grouped->isEmpty())
    <x-empty-state :title="$emptyTitle" :description="$emptyDescription" icon="trophy" />
@elseif ($variant === 'ledger')
    {{-- The ledger: one block per school year, the records listed under it. --}}
    <div class="flex flex-col gap-12">
        @foreach ($grouped as $year => $items)
            <section class="flex flex-col gap-5" data-reveal>
                <div class="flex items-center gap-4">
                    <span class="font-display text-3xl font-extrabold leading-none tabular-nums text-ink-900 dark:text-white">
                        {{ $toFa($year) }}
                    </span>

                    <span class="h-px flex-1 bg-ink-900/10 dark:bg-white/10"></span>

                    <span class="shrink-0 text-xs font-medium text-slate-500 dark:text-ink-300">سال تحصیلی</span>
                </div>

                <ul class="grid gap-4 lg:grid-cols-2">
                    @foreach ($items as $item)
                        <x-home.milestone-item
                            :item="$item"
                            :scope-label="$resolveScope(data_get($item, 'scope'))"
                            badge-icon="chart"
                            item-class="surface-card flex flex-col gap-4 p-5 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-6" />
                    @endforeach
                </ul>
            </section>
        @endforeach
    </div>
@else
    {{-- The timeline: one rail, one marker per school year. --}}
    <ol class="relative space-y-8">
        {{-- The rail runs behind the year markers. --}}
        <span class="pointer-events-none absolute inset-y-6 start-[2.6rem] hidden w-px border-s border-dashed border-ink-900/15 sm:block dark:border-white/15" aria-hidden="true"></span>

        @foreach ($grouped as $year => $items)
            <li class="relative grid gap-4 sm:grid-cols-[5.25rem_1fr] sm:gap-6" data-reveal>
                <div class="sm:pt-0.5">
                    <span class="inline-flex h-10 w-full max-w-[5.25rem] items-center justify-center rounded-full border border-brand-200 bg-white px-3 font-display text-sm font-extrabold tabular-nums text-brand-700 shadow-[var(--shadow-card)] dark:border-brand-500/35 dark:bg-ink-950 dark:text-brand-300">
                        {{ $toFa($year) }}
                    </span>
                </div>

                <ul class="flex flex-col gap-3">
                    @foreach ($items as $item)
                        <x-home.milestone-item
                            :item="$item"
                            :scope-label="$resolveScope(data_get($item, 'scope'))" />
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ol>
@endif
