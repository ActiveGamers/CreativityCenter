@props([
    'achievement',
    'variant' => 'default',
    'showDescription' => true,
    'index' => null,
])

@php
    $toFa = fn ($value): string => strtr((string) $value, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);

    $title = data_get($achievement, 'title', '');
    $description = data_get($achievement, 'description', '');
    $rankLabel = data_get($achievement, 'rank_label', '');
    $year = data_get($achievement, 'year', '');
    $scope = data_get($achievement, 'scope', '');
    $competition = data_get($achievement, 'competition', '');

    $scopes = [
        'national' => 'کشوری',
        'province' => 'استانی',
        'provincial' => 'استانی',
        'regional' => 'منطقه‌ای',
        'city' => 'شهری',
        'school' => 'مدرسه‌ای',
        'international' => 'بین‌المللی',
    ];

    /* `scope` arrives either as a backed enum (from the model) or as a plain
       string (from a hand-built array). Both are resolved to Persian. */
    if (is_object($scope) && method_exists($scope, 'label')) {
        $scopeLabel = (string) $scope->label();
        $scopeValue = $scope->value ?? 'national';
    } elseif ($scope instanceof \BackedEnum) {
        $scopeValue = (string) $scope->value;
        $scopeLabel = $scopes[$scopeValue] ?? $scopeValue;
    } else {
        $scopeValue = is_string($scope) ? $scope : '';
        $scopeLabel = $scopes[$scopeValue] ?? (is_string($scope) ? $scope : '');
    }

    $isNational = $scopeValue === 'national';
    $isFeatured = $variant === 'featured';
@endphp

@if ($isFeatured)
    {{-- The trophy case: one achievement given the room it deserves. --}}
    <div {{ $attributes->merge(['class' => 'surface-card relative flex flex-col gap-7 overflow-hidden p-6 sm:p-8 lg:flex-row lg:items-center lg:gap-10']) }}>
        <span class="absolute inset-x-0 top-0 h-1 bg-accent-500" aria-hidden="true"></span>

        <div class="flex shrink-0 items-center gap-4 lg:flex-col lg:items-start lg:gap-3">
            <span class="grid size-16 shrink-0 place-items-center rounded-2xl bg-accent-500/12 text-accent-600 dark:bg-accent-500/15 dark:text-accent-300">
                <x-icon name="trophy" class="size-8" />
            </span>

            @if ($index !== null)
                <span class="font-display text-4xl font-extrabold leading-none text-ink-900/10 tabular-nums dark:text-white/10">
                    {{ $toFa(str_pad((string) $index, 2, '0', STR_PAD_LEFT)) }}
                </span>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-lg border border-accent-200 bg-accent-50 px-2.5 py-1.5 text-xs font-semibold text-accent-700 dark:border-accent-500/25 dark:bg-accent-950/60 dark:text-accent-300">
                    <x-icon name="trophy" class="size-4 shrink-0" />
                    <span>{{ filled($rankLabel) ? $rankLabel : 'رتبه برتر' }}</span>
                </span>

                @if (filled($scopeLabel))
                    <x-badge tone="brand">{{ $scopeLabel }}</x-badge>
                @endif
            </div>

            <h3 class="mt-4 font-display text-xl font-extrabold leading-[1.5] text-balance-fa text-ink-900 sm:text-2xl dark:text-white">
                {{ $title }}
            </h3>

            @if ($showDescription && filled($description))
                <p class="mt-3 max-w-2xl text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $description }}</p>
            @endif

            @if (filled($competition))
                <p class="mt-4 inline-flex items-center gap-2 rounded-lg border border-ink-900/10 px-3 py-2 text-xs font-medium text-ink-700 dark:border-white/10 dark:text-ink-100">
                    <x-icon name="spark" class="size-4 shrink-0 text-accent-500" />
                    <span>مسابقه: {{ $competition }}</span>
                </p>
            @endif
        </div>

        @if (filled($year))
            <div class="hidden shrink-0 flex-col items-end gap-2 lg:flex">
                <span class="font-display text-6xl font-extrabold leading-none tabular-nums text-ink-900/8 dark:text-white/8">
                    {{ $toFa($year) }}
                </span>

                <span class="text-xs font-medium text-slate-500 dark:text-ink-300">سال تحصیلی</span>
            </div>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'surface-card flex flex-col gap-4 p-5 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-6']) }}>
        <div class="flex items-start justify-between gap-3">
            <span class="inline-flex items-center gap-2 rounded-lg border px-2.5 py-1.5 text-xs font-semibold {{ $isNational ? 'border-accent-200 bg-accent-50 text-accent-700 dark:border-accent-500/25 dark:bg-accent-950/60 dark:text-accent-300' : 'border-brand-200 bg-brand-50 text-brand-700 dark:border-brand-500/25 dark:bg-brand-950/60 dark:text-brand-300' }}">
                <x-icon name="trophy" class="size-4 shrink-0" />
                <span>{{ filled($rankLabel) ? $rankLabel : 'رتبه برتر' }}</span>
            </span>

            @if (filled($year))
                <span class="inline-flex items-center gap-1.5 text-xs font-medium tabular-nums text-ink-600 dark:text-ink-200">
                    <x-icon name="calendar" class="size-4 shrink-0 opacity-60" />
                    <bdi>{{ $toFa($year) }}</bdi>
                </span>
            @endif
        </div>

        <h3 class="font-display text-base font-bold leading-8 text-ink-900 text-balance-fa dark:text-white">
            {{ $title }}
        </h3>

        @if ($showDescription && filled($description))
            <p class="text-sm leading-7 text-slate-600 dark:text-ink-200">{{ $description }}</p>
        @endif

        <dl class="mt-auto flex flex-col gap-2 border-t border-ink-900/8 pt-4 text-xs dark:border-white/10">
            @if (filled($competition))
                <div class="flex items-center gap-2">
                    <dt class="shrink-0 text-slate-500 dark:text-ink-300">مسابقه</dt>
                    <dd class="min-w-0 flex-1 truncate font-medium text-ink-800 dark:text-ink-100">{{ $competition }}</dd>
                </div>
            @endif

            @if (filled($scopeLabel))
                <div class="flex items-center gap-2">
                    <dt class="shrink-0 text-slate-500 dark:text-ink-300">سطح</dt>
                    <dd class="min-w-0 flex-1 truncate font-medium text-ink-800 dark:text-ink-100">{{ $scopeLabel }}</dd>
                </div>
            @endif
        </dl>
    </div>
@endif
