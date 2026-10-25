@php
    $faDigits = fn ($value): string => strtr((string) $value, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);

    /* One visual tone per major accent: a soft gradient for the card header
       and a matching colour for the icon. The accents come from
       config/education.php, so a future admin panel changes the look here
       by changing the data alone. */
    $accentStyles = [
        'brand'   => ['panel' => 'from-brand-500/15 to-brand-500/0',  'icon' => 'text-brand-600 dark:text-brand-300'],
        'accent'  => ['panel' => 'from-accent-500/15 to-accent-500/0', 'icon' => 'text-accent-600 dark:text-accent-300'],
        'ink'     => ['panel' => 'from-ink-500/15 to-ink-500/0',       'icon' => 'text-ink-700 dark:text-ink-100'],
        'success' => ['panel' => 'from-emerald-500/15 to-emerald-500/0', 'icon' => 'text-emerald-600 dark:text-emerald-300'],
    ];
@endphp

<x-layouts.app
    title="برنامهٔ درسی"
    description="فهرست دروس هنرستان به تفکیک رشته و پایه، همراه با ساعات هفتگی و شرح کوتاه هر درس. هر رشته جدول مستقل خود را دارد.">

    <x-page-hero
        title="فهرست دروس و ساعات هفتگی"
        description="برنامهٔ درسی هنرستان به تفکیک رشته و پایه ارائه می‌شود. هر جدول فهرست دروس، ساعات هفتگی و شرح هر درس را نشان می‌دهد."
        :items="[
            ['label' => 'خانه', 'href' => route('home')],
            ['label' => 'برنامهٔ درسی'],
        ]"
        :facts="[
            ['label' => 'مجموع دروس', 'value' => $faDigits($totalCourses) . ' درس'],
            ['label' => 'مجموع ساعات هفتگی', 'value' => $faDigits($totalHours) . ' ساعت'],
        ]" />

    {{-- یک جدول به ازای هر رشته --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="برنامهٔ درسی"
                title="دروس هر رشته، در جدولی مستقل"
                description="برای هر رشته یک جدول جداگانه نمایش داده می‌شود. برنامه‌ها از طریق پیکربندی قابل ویرایش هستند و در آینده از پنل مدیریت پشتیبانی خواهند شد." />

            @if ($majors->isEmpty())
                <x-empty-state
                    title="هنوز رشته‌ای تعریف نشده است."
                    description="به‌محض ثبت رشته‌ها در پیکربندی، جداول برنامهٔ درسی در اینجا نمایش داده می‌شوند."
                    icon="graduation"
                    class="mt-10" />
            @else
                <div class="mt-10 flex flex-col gap-12">
                    @foreach ($majors as $major)
                        @php
                            $tone = $accentStyles[data_get($major, 'accent', 'brand')] ?? $accentStyles['brand'];
                            $icon = data_get($major, 'icon', 'chip');
                        @endphp

                        <article class="surface-card overflow-hidden" data-reveal>
                            <header class="flex flex-wrap items-start justify-between gap-5 border-b border-ink-900/8 bg-gradient-to-bl {{ $tone['panel'] }} px-6 py-6 dark:border-white/10">
                                <div class="flex items-center gap-4">
                                    <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-white/80 shadow-sm ring-1 ring-ink-900/5 dark:bg-ink-900/60 dark:ring-white/10">
                                        <x-icon :name="$icon" class="size-6 {{ $tone['icon'] }}" />
                                    </span>

                                    <div>
                                        <h2 class="font-display text-xl font-extrabold text-ink-900 dark:text-white">
                                            {{ $major['title'] }}
                                        </h2>
                                        <p class="mt-1 text-sm leading-7 text-slate-600 dark:text-ink-200">{{ $major['subtitle'] }}</p>
                                    </div>
                                </div>

                                <dl class="flex gap-6">
                                    <div class="text-center">
                                        <dt class="text-xs text-slate-500 dark:text-ink-300">دروس</dt>
                                        <dd class="font-display text-2xl font-extrabold leading-none text-ink-900 dark:text-white">
                                            <x-pages.fa-number :value="$major['course_count']" />
                                        </dd>
                                    </div>

                                    <div class="text-center">
                                        <dt class="text-xs text-slate-500 dark:text-ink-300">ساعت</dt>
                                        <dd class="font-display text-2xl font-extrabold leading-none text-ink-900 dark:text-white">
                                            <x-pages.fa-number :value="$major['weekly_hours']" />
                                        </dd>
                                    </div>
                                </dl>
                            </header>

                            @if (filled($major['summary']))
                                <p class="px-6 pt-6 text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $major['summary'] }}</p>
                            @endif

                            <div class="p-6 pt-4">
                                <x-pages.major-curriculum-table
                                    :major="$major"
                                    caption="برنامهٔ درسی رشتهٔ {{ $major['title'] }}" />
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- راهنمای انتخاب --}}
    <section class="section-y bg-ink-50/60 dark:bg-ink-950/50">
        <div class="container-page">
            <x-section-heading
                kicker="راهنمای انتخاب"
                title="مسیر هر دانش‌آموز در سه پایه"
                description="هر پایه گامی رو به جلو در مسیر تخصص است: از آشنایی اولیه تا پروژه‌های عملی و کارگاه پایانی." />

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @foreach ($guidance as $step)
                    <div class="surface-card flex flex-col gap-3.5 p-6">
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-950/60 dark:text-accent-300">
                            <x-icon :name="$step['icon']" class="size-5" />
                        </span>

                        <h3 class="font-display text-base font-bold text-ink-900 dark:text-white">{{ $step['title'] }}</h3>

                        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band
        title="دروس را دیدید؟ حالا دربارهٔ رشته‌ها مشورت بگیرید"
        description="فرم مشاوره و ثبت‌نام را تکمیل کنید تا کادر آموزشی دربارهٔ رشتهٔ مناسب، ظرفیت و مدارک مورد نیاز با شما تماس بگیرد." />
</x-layouts.app>
