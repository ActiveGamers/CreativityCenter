@php
    $exceptionMessage = isset($exception) && $exception instanceof \Throwable ? $exception->getMessage() : '';

    $isPersian = preg_match('/[\x{0600}-\x{06FF}]/u', $exceptionMessage) === 1;

    $sessionMessage = $isPersian
        ? $exceptionMessage
        : 'نشست شما در این صفحه منقضی شده است. لطفاً صفحه را تازه‌سازی کنید و دوباره تلاش کنید.';
@endphp

<x-layouts.app
    title="نشست منقضی شده"
    description="نشست شما منقضی شده است. صفحه را تازه‌سازی کنید و دوباره تلاش کنید یا با کادر آموزشی تماس بگیرید.">

    <section class="dark relative isolate overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

        <div class="container-page py-14 sm:py-18 lg:py-24">
            <x-breadcrumb
                :items="[
                    ['label' => 'خانه', 'href' => route('home')],
                    ['label' => 'نشست منقضی شده'],
                ]"
                class="mb-8" />

            <div class="flex flex-col gap-6">
                <p class="font-display text-6xl font-extrabold leading-none text-brand-300 sm:text-7xl">
                    <bdi dir="ltr">419</bdi>
                </p>

                <h1 class="max-w-3xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl">
                    نشست شما منقضی شده است
                </h1>

                <p class="max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">
                    {{ $sessionMessage }}
                </p>

                <p class="max-w-2xl text-sm leading-8 text-ink-300/90">
                    صفحه را یک‌بار تازه‌سازی کنید و دوباره تلاش کنید. اگر باز همین پیام را دیدید، با کادر آموزشی تماس بگیرید.
                </p>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <x-button :href="url()->current()" variant="primary" size="lg" icon="arrow" icon-position="end">
                    تلاش دوباره
                </x-button>

                <x-button :href="route('home')" variant="outline" size="lg" icon="arrow" icon-position="end">
                    صفحهٔ اصلی
                </x-button>

                <x-button :href="route('contact.index')" variant="outline" size="lg" icon="phone" icon-position="start">
                    تماس با ما
                </x-button>
            </div>
        </div>
    </section>
</x-layouts.app>
