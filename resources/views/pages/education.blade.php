@php
    $gradeLabels = collect(\App\Enums\GradeLevel::ordered());

    $allCourses = collect($coursesByGrade)->flatMap(fn ($items) => $items);

    $totalCourses = $allCourses->count();

    $totalHours = $allCourses->sum(fn ($course) => (int) ($course->weekly_hours ?? 0));

    $faDigits = fn ($value): string => strtr((string) $value, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);
@endphp

<x-layouts.app
    title="برنامهٔ درسی"
    description="برنامهٔ درسی کامل هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس در سه پایهٔ دهم، یازدهم و دوازدهم برای رشته‌های شبکه و نرم‌افزار رایانه و مکاترونیک، همراه با ساعات هفتگی هر درس.">

    <x-page-hero
        title="برنامهٔ درسی و ساعات هفتگی"
        description="برنامهٔ آموزشی هنرستان در سه پایهٔ دهم، یازدهم و دوازدهم و برای هر دو رشته ارائه می‌شود. در جدول‌های زیر، عنوان هر درس، شرح کوتاه و ساعات هفتگی آن آمده است."
        eyebrow="برنامهٔ آموزشی سه سال"
        :items="[
            ['label' => 'خانه', 'href' => route('home')],
            ['label' => 'برنامهٔ درسی'],
        ]"
        :facts="[
            ['label' => 'پایهٔ تحصیلی', 'value' => $faDigits($gradeLabels->count()) . ' پایه'],
            ['label' => 'رشتهٔ تحصیلی', 'value' => $faDigits($majors->count()) . ' رشته'],
            ['label' => 'مجموع دروس', 'value' => $faDigits($totalCourses) . ' درس'],
            ['label' => 'مجموع ساعات هفتگی', 'value' => $faDigits($totalHours) . ' ساعت'],
        ]" />

    {{-- رشته‌ها --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="دو رشتهٔ هنرستان"
                title="برنامهٔ درسی هر رشته، جدا از دیگری"
                description="دروس مشترک و دروس تخصصی هر رشته در جدول‌های ادامه با نام رشته مشخص شده‌اند. برای توضیح کامل هر رشته، صفحهٔ اختصاصی آن را ببینید." />

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @foreach ($majors as $major)
                    <x-major-card :major="$major" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- برنامهٔ درسی پایه به پایه --}}
    <section class="section-y bg-ink-50/60 dark:bg-ink-950/50">
        <div class="container-page">
            <x-section-heading
                kicker="برنامهٔ درسی"
                title="از پایهٔ دهم تا دوازدهم"
                description="هر پایه با فهرست دروس هر دو رشته آمده است. در نمایش رایانه، جدول‌ها کنار هم دیده می‌شوند و در نمایش تلفن به کارت‌های خوانا تبدیل می‌شوند." />

            <div class="mt-10 flex flex-col gap-14">
                @foreach ($gradeLabels as $grade)
                    @php $gradeCourses = $coursesByGrade[$grade->value] ?? collect(); @endphp

                    <div class="flex flex-col gap-5" data-reveal>
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-ink-900 text-sm font-bold text-white dark:bg-white dark:text-ink-950">
                                    {{ $faDigits($grade->order()) }}
                                </span>

                                <h3 class="font-display text-xl font-extrabold text-ink-900 dark:text-white">
                                    پایهٔ {{ $grade->label() }}
                                </h3>
                            </div>

                            <span class="text-xs text-slate-500 dark:text-ink-300">
                                {{ $faDigits($gradeCourses->count()) }} درس در دو رشته
                            </span>
                        </div>

                        <x-pages.curriculum-table
                            :courses="$gradeCourses"
                            show-major
                            :caption="'دروس پایهٔ ' . $grade->label() . ' در دو رشتهٔ هنرستان'" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- راهنمای انتخاب --}}
    <section class="section-y">
        <div class="container-page grid gap-6 lg:grid-cols-3">
            @foreach ([
                ['icon' => 'graduation', 'title' => 'پایهٔ دهم', 'text' => 'دورهٔ مشترک آشنایی با شبکه، سخت‌افزار، مدارهای الکتریکی و مبانی برنامه‌نویسی؛ نقطهٔ شروع هر دو رشته.'],
                ['icon' => 'chart', 'title' => 'پایهٔ یازدهم', 'text' => 'ورود به دروس تخصصی: طراحی و پیکربندی شبکه، امنیت، کنترل صنعتی، PLC و رباتیک.'],
                ['icon' => 'trophy', 'title' => 'پایهٔ دوازدهم', 'text' => 'پروژه‌های عملی و کارگاه پایانی؛ از مدیریت سرور و سرویس‌های سازمانی تا ساخت کامل یک سامانهٔ مکاترونیکی.'],
            ] as $step)
                <div class="surface-card flex flex-col gap-3.5 p-6">
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-950/60 dark:text-accent-300">
                        <x-icon :name="$step['icon']" class="size-5" />
                    </span>

                    <h3 class="font-display text-base font-bold text-ink-900 dark:text-white">{{ $step['title'] }}</h3>

                    <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <x-cta-band
        title="برنامهٔ درسی را دیدید؟ حالا دربارهٔ رشته‌ها مشورت بگیرید"
        description="فرم مشاوره و ثبت‌نام را تکمیل کنید تا کادر آموزشی دربارهٔ رشتهٔ مناسب، ظرفیت و مدارک مورد نیاز با شما تماس بگیرد." />
</x-layouts.app>
