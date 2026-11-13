@props([
    'title' => 'شناسنامه هنرستان',
])

@php
    $school = config('school') ?? [];

    $managerName = $school['manager_name'] ?? '';
    $managerPhone = $school['manager_phone'] ?? '';
    $deputyName = $school['deputy_name'] ?? '';
    $deputyPhone = $school['deputy_phone'] ?? '';

    $rows = [
        ['label' => 'نام کامل', 'value' => $school['name'] ?? '', 'icon' => 'building'],
        ['label' => 'نوع هنرستان', 'value' => $school['type'] ?? '', 'icon' => 'graduation'],
        ['label' => 'نشانی', 'value' => $school['address'] ?? '', 'icon' => 'location'],
        ['label' => 'مدیریت', 'value' => $managerName, 'extra' => $managerPhone, 'icon' => 'user'],
        ['label' => 'معاونت', 'value' => $deputyName, 'extra' => $deputyPhone, 'icon' => 'user'],
    ];

    /* The founding year only appears once the school has published it. */
    $foundedYear = $school['founded_year'] ?? '';

    if (filled($foundedYear)) {
        array_splice($rows, 2, 0, [
            ['label' => 'سال تأسیس', 'value' => (string) $foundedYear, 'icon' => 'calendar'],
        ]);
    }
@endphp

<div {{ $attributes->merge(['class' => 'surface-card overflow-hidden']) }}>
    <div class="flex items-center justify-between gap-3 border-b border-ink-900/8 bg-[var(--surface-muted)] px-6 py-4 dark:border-white/8">
        <h3 class="font-display text-sm font-bold text-ink-900 dark:text-white">{{ $title }}</h3>

        <span class="inline-flex items-center gap-1.5 rounded-lg border border-accent-500/25 bg-accent-500/10 px-2.5 py-1 text-[0.7rem] font-semibold text-accent-700 dark:text-accent-300">
            <x-icon name="trophy" class="size-3.5 shrink-0" />
            <span>رتبه اول کشوری ۱۴۰۳</span>
        </span>
    </div>

    <dl class="divide-y divide-ink-900/8 dark:divide-white/8">
        @foreach ($rows as $row)
            @if (filled($row['value']))
                <div class="flex items-start gap-4 px-6 py-4">
                    <span class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                        <x-icon :name="$row['icon']" class="size-4" />
                    </span>

                    <div class="min-w-0 flex-1">
                        <dt class="text-xs text-slate-500 dark:text-ink-300">{{ $row['label'] }}</dt>
                        <dd class="mt-1 text-sm font-medium leading-7 text-ink-900 dark:text-white">{{ $row['value'] }}</dd>

                        @if (filled($row['extra'] ?? null))
                            <dd class="mt-1.5">
                                <a href="tel:{{ $row['extra'] }}"
                                   class="inline-flex items-center gap-2 text-xs font-medium text-brand-600 transition-colors hover:text-brand-500 dark:text-brand-300">
                                    <x-icon name="phone" class="size-3.5 shrink-0" />
                                    <bdi class="tabular-nums">{{ $row['extra'] }}</bdi>
                                </a>
                            </dd>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </dl>

    {{-- The closing row carries the way to reach the school instead of
         repeating the majors and the grade levels. --}}
    <div class="flex items-center justify-between gap-4 border-t border-ink-900/8 bg-[var(--surface-muted)] px-6 py-4 dark:border-white/8">
        <span class="inline-flex items-center gap-2 text-xs text-slate-500 dark:text-ink-300">
            <x-icon name="phone" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
            <span>ارتباط با هنرستان</span>
        </span>

        <a href="{{ route('contact.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 transition-colors hover:text-brand-500 dark:text-brand-300 dark:hover:text-brand-200">
            <span>فرم تماس و ثبت‌نام</span>
            <x-icon name="arrow" class="size-4 shrink-0" />
        </a>
    </div>
</div>
