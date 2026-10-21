<x-layouts.app
    :title="'درباره هنرستان'"
    :description="'درباره هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس؛ افتخارات کشوری، رشته‌های آموزشی و کادر آموزشی هنرستان.'">

    {{-- ۱ · سربرگ -------------------------------------------------------- --}}
    <x-page-hero
        eyebrow="هنرستان فنی پسرانه · بندرعباس"
        title="درباره هنرستان"
        description="هنرستان جوار شرکت پالایش نفت بندرعباس یکی از برترین هنرستان‌های کشور است؛ با رکوردی از رتبه‌های کشوری در سه سال اخیر و کادر آموزشی همراه دانش‌آموزان تا کنکور و بازار کار."
        :items="[
            ['label' => 'خانه', 'href' => route('home')],
            ['label' => 'درباره هنرستان'],
        ]"
        :facts="[
            ['label' => 'دستاورد کشوری', 'value' => 'رتبه اول ۱۴۰۳'],
            ['label' => 'سال‌های درخشان', 'value' => '۱۴۰۱ تا ۱۴۰۳'],
        ]"
    />

    {{-- ۲ · معرفی هنرستان ------------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <div class="grid items-start gap-12 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-7">
                    <x-section-heading
                        kicker="معرفی هنرستان"
                        title="یک هنرستان هوشمند در بندرعباس"
                        data-reveal
                    />

                    <div class="prose-fa mt-7" data-reveal style="--reveal-delay: 80ms">
                        <p class="border-s-[3px] border-accent-500 ps-5 text-lg font-medium leading-10 text-ink-900 dark:text-white">
                            {{ $intro }}
                        </p>

                        <p>
                            آموزش در این هنرستان از مبانی تخصصی آغاز می‌شود و در طول سال‌های تحصیلی، توان کارگاهی و پروژه‌های عملی دانش‌آموز را گام‌به‌گام می‌سازد؛ تا جایی که برای ورود به دانشگاه و بازار کار آماده شود.
                        </p>

                        <p>
                            آنچه این هنرستان را در بندرعباس متمایز می‌کند، نتیجه آموزشی آن است؛ رتبه‌های پیاپی کشوری در سه سال اخیر، نشان می‌دهد مسیر آموزشی هنرستان به جایی رسیده که دانش‌آموزان این مجموعه در رقابت‌های ملی حرف اول را می‌زنند.
                        </p>
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-3" data-reveal style="--reveal-delay: 140ms">
                        <x-button :href="route('majors.index')" variant="primary" size="md" icon="arrow">رشته‌های تحصیلی</x-button>
                        <x-button :href="route('education')" variant="outline" size="md">برنامه‌های آموزشی</x-button>
                    </div>
                </div>

                <div class="lg:col-span-5" data-reveal style="--reveal-delay: 120ms">
                    <x-home.school-factsheet />
                </div>
            </div>
        </div>
    </section>

    {{-- ۳ · آمار هنرستان -------------------------------------------------- --}}
    <x-home.stat-strip
        :statistics="$statistics"
        :exclude="['رشته‌های فعال', 'پایه‌های تحصیلی']"
    />

    {{-- ۴ · مسیر افتخارات -------------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <div class="grid items-start gap-12 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-4">
                    <div class="lg:sticky lg:top-28">
                        <x-section-heading
                            kicker="مسیر افتخارات"
                            title="سه سال، یک رکورد"
                            description="دستاوردهای هنرستان به تفکیک سال تحصیلی در ادامه آمده است."
                            data-reveal
                        />

                        <p class="mt-7 flex items-start gap-3 rounded-xl border border-ink-900/10 bg-[var(--surface-muted)] p-4 text-xs leading-7 text-slate-600 dark:border-white/10 dark:text-ink-200" data-reveal style="--reveal-delay: 80ms">
                            <x-icon name="chart" class="mt-1 size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                            <span>
                                ارقام ثبت‌شده در بخش افتخارات به‌صورت تجمعی‌اند و مربوط به سه سال تحصیلی اخیر (۱۴۰۱ تا ۱۴۰۳) هستند؛ یعنی هر عدد، مجموع نتایج همان بازه سه‌ساله است.
                            </span>
                        </p>

                        <div class="mt-6" data-reveal style="--reveal-delay: 120ms">
                            <x-button :href="route('achievements')" variant="ghost" size="sm" icon="arrow" iconPosition="end">
                                صفحه کامل افتخارات
                            </x-button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <x-home.milestone-timeline :achievements="$achievements" variant="timeline" />
                </div>
            </div>
        </div>
    </section>

    {{-- ۵ · رشته‌های آموزشی و یادگیری عملی ------------------------------- --}}
    <section class="border-y border-ink-900/8 bg-[var(--surface-sunken)] dark:border-white/8">
        <div class="container-page section-y">
            <x-section-heading
                kicker="رشته‌های آموزشی"
                title="یادگیری در کارگاه، نه فقط در کلاس"
                description="برنامه آموزشی هنرستان بر پایه کارگاه‌های عملی تنظیم شده است؛ دانش‌آموز در هر دو رشته با ابزار واقعی کار می‌کند."
                data-reveal
            />

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:gap-6">
                <article class="surface-card flex flex-col gap-5 p-6 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-7" data-reveal>
                    <div class="flex items-center gap-4">
                        <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300">
                            <x-icon name="network" class="size-6" />
                        </span>

                        <div>
                            <h3 class="font-display text-lg font-bold leading-8 text-ink-900 dark:text-white">شبکه و نرم‌افزار رایانه</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-ink-300">طراحی، پیاده‌سازی و مدیریت زیرساخت‌های شبکه</p>
                        </div>
                    </div>

                    <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                        آموزش در این رشته از مبانی سخت‌افزار و سیستم‌عامل آغاز می‌شود و در پایه دوازدهم به مباحث پیشرفته مانند امنیت شبکه، مدیریت سرور و پروژه‌های عملی می‌رسد.
                    </p>
                </article>

                <article class="surface-card flex flex-col gap-5 p-6 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] sm:p-7" data-reveal style="--reveal-delay: 110ms">
                    <div class="flex items-center gap-4">
                        <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-accent-50 text-accent-600 dark:bg-accent-950/60 dark:text-accent-300">
                            <x-icon name="mechatronics" class="size-6" />
                        </span>

                        <div>
                            <h3 class="font-display text-lg font-bold leading-8 text-ink-900 dark:text-white">مکاترونیک</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-ink-300">تلاقی مکانیک، الکترونیک و کنترل</p>
                        </div>
                    </div>

                    <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                        با ترکیب دانش مکانیک و الکترونیک، دانش‌آموز با مدارهای آنالوگ و دیجیتال، کنترل صنعتی، رباتیک و اتوماسیون آشنا می‌شود و در کارگاه‌های عملی، سیستم‌های مکاترونیکی واقعی را طراحی و راه‌اندازی می‌کند.
                    </p>
                </article>
            </div>

            <div class="mt-9" data-reveal>
                <x-button :href="route('majors.index')" variant="secondary" size="md" icon="arrow">مشاهده صفحه رشته‌ها</x-button>
            </div>
        </div>
    </section>

    {{-- ۶ · کادر آموزشی --------------------------------------------------- --}}
    <section class="section-y">
        <div class="container-page">
            <x-section-heading
                kicker="کادر آموزشی"
                title="مدیریت و معاونت هنرستان"
                description="راهنمایی درباره ثبت‌نام، برنامه درسی و امور آموزشی را از این دو نفر بگیرید."
                data-reveal
            />

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:gap-6">
                @foreach ($staff as $member)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 110 }}ms">
                        <x-home.staff-card :member="$member" detailed class="h-full" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ۷ · دعوت به تماس --------------------------------------------------- --}}
    <x-cta-band
        kicker="یک گفت‌وگوی کوتاه"
        title="پرسشی دارید؟ با هنرستان تماس بگیرید"
        description="برای آشنایی بیشتر با رشته‌ها، پایه‌ها و شیوه ثبت‌نام، از فرم تماس همین وب‌سایت یا شماره‌های مستقیم کادر آموزشی استفاده کنید."
        primary-label="فرم تماس"
        primary-href="{{ route('contact.index') }}"
        secondary-label="ثبت‌نام پایه دهم"
        secondary-href="{{ route('admission.index') }}"
    />
</x-layouts.app>
