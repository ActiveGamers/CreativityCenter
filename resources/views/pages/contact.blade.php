@php
    $inputClasses = 'w-full rounded-xl border border-ink-900/15 bg-white px-4 py-3 text-sm text-ink-900 shadow-[0_1px_2px_rgb(7_30_42/0.04)] outline-none transition-colors placeholder:text-slate-400 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 dark:border-white/12 dark:bg-ink-950/60 dark:text-white dark:placeholder:text-ink-400 dark:focus:border-brand-400';
@endphp

<x-layouts.app
    title="تماس با ما"
    description="راه‌های ارتباط با هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس: نشانی، شمارهٔ تماس مدیریت و معاونت، و فرم ارسال پیام مستقیم.">

    <x-page-hero
        title="تماس با ما"
        description="برای هر پرسشی دربارهٔ رشته‌ها، برنامهٔ درسی، ثبت‌نام یا همکاری با هنرستان، از راه‌های ارتباطی زیر استفاده کنید. پیام‌های فرم در اولین فرصت کاری پاسخ داده می‌شوند."
        eyebrow="راه‌های ارتباطی"
        :items="[
            ['label' => 'خانه', 'href' => route('home')],
            ['label' => 'تماس با ما'],
        ]"
        :facts="[
            ['label' => 'نوع مدرسه', 'value' => $school['type']],
            ['label' => 'شهر', 'value' => $school['city'] . '، ' . $school['province']],
        ]" />

    <section class="section-y">
        <div class="container-page grid gap-10 lg:grid-cols-12 lg:gap-12">
            {{-- پنل اطلاعات --}}
            <aside class="flex flex-col gap-6 lg:col-span-5">
                <div class="surface-card flex flex-col gap-6 p-6 sm:p-7">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-display text-lg font-extrabold text-ink-900 dark:text-white">اطلاعات هنرستان</h2>
                        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                            {{ $school['name'] }} در {{ $school['city'] }}، {{ $school['province'] }} به آموزش دانش‌آموزان پسر در دو رشتهٔ شبکه و نرم‌افزار رایانه و مکاترونیک می‌پردازد.
                        </p>
                    </div>

                    <x-pages.map-placeholder
                        :address="$school['address']"
                        :map-url="$school['map_url'] ?? null" />

                    <div class="flex flex-col gap-5 border-t border-ink-900/8 pt-6 dark:border-white/10">
                        <x-pages.contact-item
                            icon="building"
                            label="نوع مدرسه"
                            :value="$school['type']" />

                        <x-pages.contact-item
                            icon="user"
                            label="مدیریت هنرستان"
                            :value="$school['manager_name']" />

                        <x-pages.contact-item
                            icon="phone"
                            label="شمارهٔ تماس مدیریت"
                            :value="$school['manager_phone']"
                            :href="'tel:' . $school['manager_phone']"
                            ltr />

                        <x-pages.contact-item
                            icon="user"
                            label="معاونت هنرستان"
                            :value="$school['deputy_name']" />

                        <x-pages.contact-item
                            icon="phone"
                            label="شمارهٔ تماس معاونت"
                            :value="$school['deputy_phone']"
                            :href="'tel:' . $school['deputy_phone']"
                            ltr />
                    </div>
                </div>

                <div class="surface-card flex flex-col gap-4 p-6 sm:p-7">
                    <h2 class="font-display text-base font-bold text-ink-900 dark:text-white">دنبال ثبت‌نام هستید؟</h2>

                    <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                        برای ثبت‌نام پایهٔ دهم، فرم مشاوره و ثبت‌نام را تکمیل کنید تا کادر آموزشی با شما تماس بگیرد و دربارهٔ رشتهٔ مناسب راهنمایی‌تان کند.
                    </p>

                    <x-button :href="route('admission.index')" variant="primary" size="md" icon="arrow" icon-position="end">
                        درخواست مشاوره و ثبت‌نام
                    </x-button>
                </div>
            </aside>

            {{-- فرم تماس --}}
            <div class="lg:col-span-7">
                <div class="surface-card flex flex-col gap-7 p-6 sm:p-8">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-display text-xl font-extrabold text-ink-900 dark:text-white">ارسال پیام</h2>
                        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                            فیلدهای ستاره‌دار اجباری هستند. پیام شما در نخستین فرصت کاری بررسی و پاسخ داده می‌شود.
                        </p>
                    </div>

                    {{-- The no-JavaScript path: a plain POST that redirects back
                         with a flash message. --}}
                    @if (session('success'))
                        <x-pages.notice
                            tone="success"
                            icon="check"
                            role="status"
                            data-flash-success
                            title="پیام شما ثبت شد"
                            :description="session('success')" />
                    @endif

                    @if ($errors->any())
                        <x-pages.notice
                            tone="accent"
                            icon="spark"
                            role="alert"
                            title="چند مورد نیاز به اصلاح دارد"
                            description="لطفاً خطاهای مشخص‌شده در فرم را برطرف کنید و دوباره ارسال بفرستید." />
                    @endif

                    {{-- The action is origin relative on purpose: the form must
                         always post back to the host that served the page, even
                         behind a proxy that rewrites the host header. --}}
                    <form method="post" action="{{ route('contact.store', [], false) }}" data-app-form novalidate class="flex flex-col gap-6">
                        @csrf

                        <div class="grid gap-6 sm:grid-cols-2">
                            <x-form-field
                                name="name"
                                label="نام و نام خانوادگی"
                                :required="true"
                                :error="$errors->first('name')"
                                icon="user">
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                    placeholder="مثال: زهرا کریمی"
                                    class="{{ $inputClasses }}">
                            </x-form-field>

                            <x-form-field
                                name="phone"
                                label="شمارهٔ تماس"
                                :required="true"
                                :error="$errors->first('phone')"
                                help="شماره را با ارقام انگلیسی وارد کنید؛ مثال: 09121234567"
                                icon="phone">
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    inputmode="numeric"
                                    dir="ltr"
                                    autocomplete="tel"
                                    placeholder="09121234567"
                                    data-validate="mobile"
                                    class="{{ $inputClasses }} text-start">
                            </x-form-field>

                            <x-form-field
                                name="email"
                                label="نشانی ایمیل"
                                :error="$errors->first('email')"
                                help="اختیاری — برای دریافت پاسخ مکتوب."
                                icon="mail">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    dir="ltr"
                                    autocomplete="email"
                                    placeholder="name@example.com"
                                    class="{{ $inputClasses }} text-start">
                            </x-form-field>

                            <x-form-field
                                name="subject"
                                label="موضوع"
                                :error="$errors->first('subject')"
                                help="اختیاری — خلاصهٔ پیام در یک عبارت."
                                icon="quote">
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    maxlength="180"
                                    placeholder="مثال: پرسش دربارهٔ ثبت‌نام"
                                    class="{{ $inputClasses }}">
                            </x-form-field>
                        </div>

                        <x-form-field
                            name="message"
                            label="متن پیام"
                            :required="true"
                            :error="$errors->first('message')"
                            help="حداقل ۱۰ نویسه بنویسید تا کادر آموزشی بتواند دقیق پاسخ دهد."
                            icon="mail">
                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                required
                                minlength="10"
                                maxlength="2000"
                                placeholder="سلام، برای ثبت‌نام پایهٔ دهم سوالی داشتم."
                                class="{{ $inputClasses }} min-h-40 resize-y">{{ old('message') }}</textarea>
                        </x-form-field>

                        <div class="flex flex-col gap-4 border-t border-ink-900/8 pt-6 sm:flex-row sm:items-center sm:justify-between dark:border-white/10">
                            <p class="max-w-sm text-xs leading-7 text-slate-500 dark:text-ink-300">
                                اطلاعات شما فقط برای پاسخ به همین پیام نگه‌داری می‌شود.
                            </p>

                            <x-button type="submit" variant="primary" size="lg" icon="arrow" icon-position="end" class="w-full sm:w-auto">
                                ارسال پیام
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band
        title="هنوز پرسشی دارید؟"
        description="همین‌جا پیام بگذارید یا شمارهٔ تماس کادر آموزشی را بگیرید. برای ثبت‌نام پایهٔ دهم، فرم مشاوره و ثبت‌نام در دسترس است." />
</x-layouts.app>
