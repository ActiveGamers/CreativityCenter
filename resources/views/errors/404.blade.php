@php
    $exceptionMessage = isset($exception) && $exception instanceof \Throwable ? $exception->getMessage() : '';

    $isPersian = preg_match('/[\x{0600}-\x{06FF}]/u', $exceptionMessage) === 1;

    $notFoundMessage = $isPersian
        ? $exceptionMessage
        : 'مطلب مورد نظر یافت نشد یا دیگر در دسترس نیست. لطفاً فهرست مطالب را مشاهده کنید یا با کادر آموزشی تماس بگیرید.';
@endphp

<x-layouts.app
    title="صفحه پیدا نشد"
    description="صفحهٔ مورد نظر پیدا نشد. می‌توانید به صفحهٔ اصلی، فهرست اخبار یا صفحهٔ تماس با کادر آموزشی بروید.">

    <section class="dark relative isolate overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

        <div class="container-page py-14 sm:py-18 lg:py-24">
            <x-breadcrumb
                :items="[
                    ['label' => 'خانه', 'href' => route('home')],
                    ['label' => 'صفحه پیدا نشد'],
                ]"
                class="mb-8" />

            <div class="flex flex-col gap-6">
                <p class="font-display text-6xl font-extrabold leading-none text-brand-300 sm:text-7xl">
                    <bdi dir="ltr">404</bdi>
                </p>

                <h1 class="max-w-3xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl">
                    صفحه‌ای که دنبالش بودید پیدا نشد
                </h1>

                <p class="max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">
                    {{ $notFoundMessage }}
                </p>

                <p class="max-w-2xl text-sm leading-8 text-ink-300/90">
                    اگر نشانی را اشتباه وارد کرده‌اید، می‌توانید از فهرست زیر ادامه دهید. اگر فکر می‌کنید این صفحه باید وجود داشته باشد، به کادر آموزشی اطلاع دهید.
                </p>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <x-button :href="route('home')" variant="primary" size="md" icon="arrow" icon-position="end">
                    صفحهٔ اصلی
                </x-button>

                <x-button :href="route('news.index')" variant="outline" size="md">
                    فهرست اخبار
                </x-button>

                <x-button :href="route('contact.index')" variant="outline" size="md" icon="phone" icon-position="start">
                    تماس با کادر آموزشی
                </x-button>
            </div>
        </div>
    </section>
</x-layouts.app>
