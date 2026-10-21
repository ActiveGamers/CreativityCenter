<x-layouts.app
    title="رشته‌های تحصیلی"
    description="معرفی رشته‌های شبکه و نرم‌افزار رایانه و مکاترونیک در هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس، همراه با برنامهٔ درسی و ساعات هفتگی هر پایه.">

    <x-page-hero
        title="رشته‌های تحصیلی"
        description="رشته‌های تخصصی هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس: شبکه و نرم‌افزار رایانه و مکاترونیک. پیش از ثبت‌نام، برنامهٔ درسی و ساعات هفتگی هر رشته را ببینید."
        :items="[
            ['label' => 'خانه', 'href' => route('home')],
            ['label' => 'رشته‌های تحصیلی'],
        ]"
        :facts="[
            ['label' => 'پایهٔ تحصیلی', 'value' => 'دهم تا دوازدهم'],
            ['label' => 'دستاورد سال ۱۴۰۳', 'value' => 'رتبهٔ اول کشوری'],
        ]" />

    {{-- رشته‌های فعال --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="رشته‌های فعال"
                title="دو رشته، دو مسیر حرفه‌ای"
                description="هر رشته با دروس عملی، کارگاه تخصصی و پروژهٔ پایانی همراه است. برای دیدن سرفصل‌ها و ساعات هفتگی، وارد صفحهٔ همان رشته شوید.">
                <x-slot:action>
                    <x-button :href="route('education')" variant="outline" size="sm" icon="chart" icon-position="end">
                        برنامهٔ درسی کامل
                    </x-button>
                </x-slot:action>
            </x-section-heading>

            @if ($majors->isNotEmpty())
                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    @foreach ($majors as $major)
                        {{-- A fade-in (not a horizontal slide): a translated
                             card would push the page wider than the viewport
                             on small screens. --}}
                        <x-major-card
                            :major="$major"
                            data-reveal
                            :style="'--reveal-delay: ' . ($loop->index * 110) . 'ms'" />
                    @endforeach
                </div>
            @else
                <div class="mt-10">
                    <x-empty-state
                        title="هنوز رشته‌ای منتشر نشده است"
                        description="به‌محض فعال شدن رشته‌های جدید، فهرست آن‌ها در همین صفحه نمایش داده می‌شود."
                        icon="chip">
                        <x-button :href="route('contact.index')" variant="secondary" size="sm">تماس با کادر آموزشی</x-button>
                    </x-empty-state>
                </div>
            @endif

            <div class="mt-8">
                <x-pages.notice
                    tone="neutral"
                    icon="spark"
                    title="دربارهٔ ظرفیت اعلام‌شده"
                    description="ظرفیت هر رشته در سایت بر پایهٔ اعلام فعلی دفتر هنرستان درج شده و ممکن است در هر سال تحصیلی تغییر کند. برای وضعیت دقیق ظرفیت و مدارک مورد نیاز، با کادر آموزشی تماس بگیرید." />
            </div>
        </div>
    </section>

    {{-- چرا این رشته‌ها --}}
    <section class="section-y bg-ink-50/60 dark:bg-ink-950/50">
        <div class="container-page">
            <x-section-heading
                kicker="چرا این رشته‌ها؟"
                title="چهار دلیل برای انتخاب هنرستان"
                description="آنچه دانش‌آموز در کارگاه‌های هنرستان می‌آموزد، فاصلهٔ زیادی با یک کلاس نظری دارد." />

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'wrench', 'title' => 'کارگاه عملی', 'text' => 'دروس به‌جای تئوری محض، در آزمایشگاه شبکه و کارگاه کنترل صنعتی و رباتیک اجرا می‌شود.'],
                    ['icon' => 'graduation', 'title' => 'مسیر ادامهٔ تحصیل', 'text' => 'دیپلم رشته‌های فنی، ادامهٔ تحصیل در رشته‌های دانشگاهی مرتبط را هموار می‌کند و مسیر ورود به بازار کار تخصصی را باز نگه می‌دارد.'],
                    ['icon' => 'trophy', 'title' => 'رقابت در مسابقات', 'text' => 'دانش‌آموزان هنرستان در سه سال اخیر بیش از ۲۰۰ رتبهٔ سه‌رقمی و ۳ رتبهٔ تک‌رقمی کشوری کسب کرده‌اند.'],
                    ['icon' => 'chart', 'title' => 'مهارت قابل استفاده', 'text' => 'مهارت شبکه، برنامه‌نویسی و کنترل صنعتی، مهارت‌هایی است که در صنعت منطقه به‌طور مستقیم به کار گرفته می‌شود.'],
                ] as $reason)
                    <div class="surface-card flex flex-col gap-3.5 p-6" data-reveal>
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                            <x-icon :name="$reason['icon']" class="size-5" />
                        </span>

                        <h3 class="font-display text-base font-bold text-ink-900 dark:text-white">{{ $reason['title'] }}</h3>

                        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $reason['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band
        title="برای ثبت‌نام و مشاوره با ما در تماس باشید"
        description="فرم مشاوره و ثبت‌نام را تکمیل کنید تا کادر آموزشی در اولین فرصت کاری با شما تماس بگیرد و دربارهٔ رشتهٔ مناسب راهنمایی‌تان کند." />
</x-layouts.app>
