@props([
    'eyebrow' => null,
    'headline' => null,
    'lead' => null,
    'description' => null,
])

@php
    $school = config('school') ?? [];

    $eyebrow = $eyebrow ?? trim(($school['type'] ?? 'هنرستان فنی پسرانه').' · '.($school['city'] ?? 'بندرعباس').' · '.($school['province'] ?? 'هرمزگان'));
    $headline = $headline ?? ($school['name'] ?? config('app.name', 'هنرستان فنی'));
    $lead = $lead ?? 'رتبه اول کشوری در سال تحصیلی ۱۴۰۳';
    $description = $description ?? \App\Support\SchoolContent::intro();
    $address = $school['address'] ?? '';
    $managerName = $school['manager_name'] ?? '';
    $managerPhone = $school['manager_phone'] ?? '';
@endphp

{{-- The opening band: deep petrol, blueprint grid, a single soft wash. --}}
<section class="dark relative isolate overflow-hidden bg-ink-950 text-white">
    <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -top-44 start-[-18%] -z-10 h-[36rem] w-[85%] hero-wash opacity-90" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/40" aria-hidden="true"></div>

    <div class="container-page grid items-center gap-14 py-16 lg:grid-cols-12 lg:gap-12 lg:py-24">
        <div class="lg:col-span-7" data-reveal>
            <p class="inline-flex items-center gap-2 rounded-full border border-white/15 px-3.5 py-1.5 text-xs font-medium text-ink-100">
                <span class="size-1.5 shrink-0 rounded-full bg-accent-400"></span>
                {{ $eyebrow }}
            </p>

            <h1 class="mt-6 max-w-2xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl lg:text-[3.35rem] lg:leading-[1.28]">
                {{ $headline }}
            </h1>

            <p class="mt-5 flex items-center gap-2.5 text-base font-bold text-accent-300 sm:text-lg">
                <x-icon name="trophy" class="size-5 shrink-0" />
                <span>{{ $lead }}</span>
            </p>

            <p class="mt-6 max-w-2xl text-sm leading-9 text-ink-200/90 sm:text-base">
                {{ $description }}
            </p>

            <div class="mt-9 flex flex-col items-stretch gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                <x-button :href="route('admission.index')" variant="primary" size="lg" class="w-full sm:w-auto">ثبت‌نام پایه دهم</x-button>
                <x-button :href="route('majors.index')" variant="outline" size="lg" icon="arrow" iconPosition="end" class="w-full sm:w-auto">رشته‌های تحصیلی</x-button>
            </div>

            <dl class="mt-11 grid gap-x-10 gap-y-5 border-t border-white/10 pt-7 sm:grid-cols-2">
                @if (filled($address))
                    <div class="flex items-start gap-3">
                        <x-icon name="location" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <div>
                            <dt class="text-xs text-ink-300">نشانی هنرستان</dt>
                            <dd class="mt-1 text-sm font-medium text-white">{{ $address }}</dd>
                        </div>
                    </div>
                @endif

                @if (filled($managerPhone))
                    <div class="flex items-start gap-3">
                        <x-icon name="phone" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <div>
                            <dt class="text-xs text-ink-300">تماس با مدیریت</dt>
                            <dd class="mt-1 text-sm font-medium text-white">
                                <bdi class="tabular-nums">
                                    <a href="tel:{{ $managerPhone }}" class="transition-colors hover:text-brand-300">{{ $managerPhone }}</a>
                                </bdi>
                            </dd>
                        </div>
                    </div>
                @endif
            </dl>
        </div>

        <div class="lg:col-span-5" data-reveal style="--reveal-delay: 140ms">
            <x-home.hero-visual />
        </div>
    </div>
</section>
