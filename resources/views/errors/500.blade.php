@php
    /*
     * The technical detail of the failure stays in the log: a 500 can carry
     * a raw SQL message, a file path or any other internal information, and
     * the page must never show it to the visitor.
     */
    $serverMessage = 'مشکلی در نمایش این صفحه پیش آمد. لطفاً چند لحظه بعد دوباره تلاش کنید.';
@endphp

<x-layouts.app
    title="خطا در نمایش صفحه"
    description="در نمایش این صفحه خطایی رخ داد. لطفاً صفحه را تازه‌سازی کنید و در صورت تکرار مشکل با کادر آموزشی تماس بگیرید.">

    <section class="dark relative isolate overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

        <div class="container-page py-14 sm:py-18 lg:py-24">
            <x-breadcrumb
                :items="[
                    ['label' => 'خانه', 'href' => route('home')],
                    ['label' => 'خطا در نمایش صفحه'],
                ]"
                class="mb-8" />

            <div class="flex flex-col gap-6">
                <p class="font-display text-6xl font-extrabold leading-none text-brand-300 sm:text-7xl">
                    <bdi dir="ltr">500</bdi>
                </p>

                <h1 class="max-w-3xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl">
                    نمایش این صفحه با مشکل روبه‌رو شد
                </h1>

                <p class="max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">
                    {{ $serverMessage }}
                </p>

                <p class="max-w-2xl text-sm leading-8 text-ink-300/90">
                    صفحه را یک‌بار تازه‌سازی کنید و دوباره تلاش کنید. اگر مشکل تکرار شد، با کادر آموزشی تماس بگیرید تا پیگیری شود.
                </p>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <x-button :href="route('home')" variant="primary" size="md" icon="arrow" icon-position="end">
                    صفحهٔ اصلی
                </x-button>

                <x-button :href="route('contact.index')" variant="outline" size="md" icon="phone" icon-position="start">
                    تماس با کادر آموزشی
                </x-button>
            </div>
        </div>
    </section>
</x-layouts.app>
