<x-layouts.app
    :description="'هنرستان جوار شرکت پالایش نفت بندرعباس؛ هنرستان فنی پسرانه بندرعباس با رتبه اول کشوری در سال تحصیلی ۱۴۰۳ در رشته‌های شبکه و نرم‌افزار رایانه و مکاترونیک. معرفی رشته‌ها، برنامه درسی، افتخارات و اخبار هنرستان.'">

    {{-- ۱ · Opening band ------------------------------------------------ --}}
    <x-home.hero />

    {{-- ۲ · Trust strip -------------------------------------------------- --}}
    <x-home.stat-strip
        :statistics="$statistics"
        :exclude="['رشته‌های فعال', 'پایه‌های تحصیلی']"
    />

    {{-- ۳ · معرفی کوتاه -------------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <div class="grid items-start gap-12 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-7">
                    <x-section-heading
                        kicker="درباره هنرستان"
                        title="هنرستانی که با نتیجه شناخته می‌شود"
                        data-reveal
                    >
                        <x-slot:action>
                            <x-button :href="route('about')" variant="ghost" size="sm" icon="arrow" iconPosition="end">
                                معرفی کامل هنرستان
                            </x-button>
                        </x-slot:action>
                    </x-section-heading>

                    <div class="prose-fa mt-7 max-w-2xl" data-reveal style="--reveal-delay: 80ms">
                        <p class="text-lg leading-9 font-medium text-ink-900 dark:text-white">
                            {{ \App\Support\SchoolContent::intro() }}
                        </p>

                        <p class="text-base leading-9">
                            دانش‌آموزان در این هنرستان مسیری پیوسته را از مبانی تخصصی تا پروژه‌های عملی و آمادگی برای دانشگاه و بازار کار طی می‌کنند؛ آموزشی که در کلاس آغاز می‌شود و در کارگاه ادامه پیدا می‌کند.
                        </p>
                    </div>

                    <dl class="mt-9 grid gap-x-8 gap-y-4 border-t border-ink-900/10 pt-7 sm:grid-cols-2 dark:border-white/10" data-reveal style="--reveal-delay: 140ms">
                        <div>
                            <dt class="text-xs text-slate-500 dark:text-ink-300">سال‌های درخشان</dt>
                            <dd class="mt-1.5 font-display text-base font-bold text-ink-900 dark:text-white">۱۴۰۱ تا ۱۴۰۳</dd>
                        </div>

                        <div>
                            <dt class="text-xs text-slate-500 dark:text-ink-300">نشانی</dt>
                            <dd class="mt-1.5 text-sm font-semibold text-ink-900 dark:text-white">{{ config('school.address') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                    <x-home.school-factsheet />
                </div>
            </div>
        </div>
    </section>

    {{-- ۴ · رشته‌های تحصیلی ----------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="رشته‌های تحصیلی"
                title="رشته‌های تخصصی، مسیرهای حرفه‌ای"
                description="رشته‌های تخصصی هنرستان را ببینید؛ برنامه درسی هر پایه در صفحه آموزش آمده است."
                data-reveal
            >
                <x-slot:action>
                    <x-button :href="route('majors.index')" variant="ghost" size="sm" icon="arrow" iconPosition="end">
                        همه رشته‌ها
                    </x-button>
                </x-slot:action>
            </x-section-heading>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:gap-6">
                @foreach ($majors as $major)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
                        <x-major-card :major="$major" class="h-full" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ۵ · پایه‌های تحصیلی ----------------------------------------------- --}}
    <section class="border-y border-ink-900/8 bg-[var(--surface-sunken)] dark:border-white/8">
        <div class="container-page section-y">
            <x-section-heading
                kicker="مسیر تحصیلی"
                title="سه پایه، یک مسیر روشن"
                description="دانش‌آموز از مبانی تخصصی تا پروژه‌های عملی، قدم‌به‌قدم پیش می‌رود."
                data-reveal
            />

            <div class="mt-11">
                <x-home.grade-journey :href="route('education')" />
            </div>
        </div>
    </section>

    {{-- ۶ · افتخارات ----------------------------------------------------- --}}
    <section class="dark relative isolate overflow-hidden bg-ink-900 text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-32 start-[-14%] -z-10 h-96 w-[70%] hero-wash opacity-70" aria-hidden="true"></div>

        <div class="container-page section-y">
            <x-section-heading
                kicker="افتخارات کشوری"
                title="رتبه اول کشوری و یک رکورد سه‌ساله"
                description="دستاوردهای هنرستان در سه سال اخیر، مجموعه‌ای از رتبه‌های سه‌رقمی، دو‌رقمی و تک‌رقمی کشوری است."
                data-reveal
            >
                <x-slot:action>
                    <x-button :href="route('achievements')" variant="ghost" size="sm" icon="arrow" iconPosition="end"
                              class="text-ink-100 hover:bg-white/10 hover:text-white">
                        همه افتخارات
                    </x-button>
                </x-slot:action>
            </x-section-heading>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                @foreach ($achievements as $achievement)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
                        <x-achievement-card :achievement="$achievement" class="h-full" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ۷ · مدیریت و معاونت ----------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="کادر آموزشی"
                title="مدیریت و معاونت هنرستان"
                description="برای راهنمایی درباره ثبت‌نام، برنامه درسی و امور آموزشی، با کادر هنرستان تماس بگیرید."
                data-reveal
            >
                <x-slot:action>
                    <x-button :href="route('contact.index')" variant="ghost" size="sm" icon="arrow" iconPosition="end">
                        تماس با هنرستان
                    </x-button>
                </x-slot:action>
            </x-section-heading>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:gap-6">
                @foreach ($staff as $member)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
                        <x-home.staff-card :member="$member" class="h-full" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ۸ · اخبار --------------------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="اخبار و اطلاعیه‌ها"
                title="تازه‌ترین خبرهای هنرستان"
                description="گزارش‌ها، اطلاعیه‌ها و رویدادهای هنرستان را از این بخش دنبال کنید."
                data-reveal
            >
                <x-slot:action>
                    <x-button :href="route('news.index')" variant="ghost" size="sm" icon="arrow" iconPosition="end">
                        همه خبرها
                    </x-button>
                </x-slot:action>
            </x-section-heading>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                @foreach ($posts as $post)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
                        <x-post-card :post="$post" class="h-full" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ۹ · دعوت به ثبت‌نام ----------------------------------------------- --}}
    <x-cta-band
        title="برای ثبت‌نام پایه دهم با ما در تماس باشید"
        description="فرم ثبت‌نام آنلاین هنرستان در دسترس است. برای راهنمایی درباره مدارک مورد نیاز و ظرفیت پذیرش هر رشته، از فرم ثبت‌نام یا تماس مستقیم با کادر آموزشی استفاده کنید."
        primary-label="فرم ثبت‌نام"
        primary-href="{{ route('admission.index') }}"
        secondary-label="تماس با ما"
        secondary-href="{{ route('contact.index') }}"
    />
</x-layouts.app>
