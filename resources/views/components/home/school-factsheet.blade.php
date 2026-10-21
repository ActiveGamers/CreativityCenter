@props([
    'title' => 'شناسنامه هنرستان',
])

@php
    $school = config('school') ?? [];

    $managerName = $school['manager_name'] ?? '';
    $managerPhone = $school['manager_phone'] ?? '';
    $deputyName = $school['deputy_name'] ?? '';
    $deputyPhone = $school['deputy_phone'] ?? '';

    $majors = ['شبکه و نرم‌افزار رایانه', 'مکاترونیک'];
    $grades = ['دهم', 'یازدهم', 'دوازدهم'];

    $rows = [
        ['label' => 'نام کامل', 'value' => $school['name'] ?? '', 'icon' => 'building'],
        ['label' => 'نوع هنرستان', 'value' => $school['type'] ?? '', 'icon' => 'graduation'],
        ['label' => 'نشانی', 'value' => $school['address'] ?? '', 'icon' => 'location'],
        ['label' => 'مدیریت', 'value' => $managerName, 'extra' => $managerPhone, 'icon' => 'user'],
        ['label' => 'معاونت', 'value' => $deputyName, 'extra' => $deputyPhone, 'icon' => 'user'],
    ];
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

        <div class="flex items-start gap-4 px-6 py-4">
            <span class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                <x-icon name="chip" class="size-4" />
            </span>

            <div class="min-w-0 flex-1">
                <dt class="text-xs text-slate-500 dark:text-ink-300">رشته‌های تحصیلی</dt>
                <dd class="mt-1.5 flex flex-wrap gap-1.5">
                    @foreach ($majors as $major)
                        <span class="rounded-lg border border-ink-900/10 px-2.5 py-1 text-xs font-medium text-ink-800 dark:border-white/10 dark:text-ink-100">
                            {{ $major }}
                        </span>
                    @endforeach
                </dd>
            </div>
        </div>

        <div class="flex items-start gap-4 px-6 py-4">
            <span class="mt-0.5 grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                <x-icon name="graduation" class="size-4" />
            </span>

            <div class="min-w-0 flex-1">
                <dt class="text-xs text-slate-500 dark:text-ink-300">پایه‌های تحصیلی</dt>
                <dd class="mt-1.5 flex flex-wrap gap-1.5">
                    @foreach ($grades as $grade)
                        <span class="rounded-lg border border-ink-900/10 px-2.5 py-1 text-xs font-medium text-ink-800 dark:border-white/10 dark:text-ink-100">
                            {{ $grade }}
                        </span>
                    @endforeach
                </dd>
            </div>
        </div>
    </dl>
</div>
