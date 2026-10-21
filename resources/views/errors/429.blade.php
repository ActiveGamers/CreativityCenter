@php
    /*
     * The page of a throttled request. The provider hands the Persian
     * message over as $throttleMessage; when the framework renders the view
     * on its own, the message of the exception is used - but only when it is
     * a Persian sentence, never the English default of the framework.
     */
    $exceptionMessage = isset($exception) && $exception instanceof \Throwable ? $exception->getMessage() : '';

    $isPersian = preg_match('/[\x{0600}-\x{06FF}]/u', $exceptionMessage) === 1;

    $defaultMessage = 'تعداد درخواست‌های ارسالی شما بیش از حد مجاز است. لطفاً چند دقیقه دیگر صبر کنید و دوباره تلاش کنید.';

    $throttleMessage = isset($throttleMessage) && is_string($throttleMessage) && $throttleMessage !== ''
        ? $throttleMessage
        : ($isPersian ? $exceptionMessage : $defaultMessage);
@endphp

<x-layouts.app
    title="درخواست بیش از حد"
    description="تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً چند دقیقه دیگر صبر کنید و دوباره تلاش کنید.">

    <section class="dark relative isolate overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

        <div class="container-page py-14 sm:py-18 lg:py-24">
            <x-breadcrumb
                :items="[
                    ['label' => 'خانه', 'href' => route('home')],
                    ['label' => 'درخواست بیش از حد'],
                ]"
                class="mb-8" />

            <div class="flex flex-col gap-6">
                <p class="font-display text-6xl font-extrabold leading-none text-brand-300 sm:text-7xl">
                    <bdi dir="ltr">429</bdi>
                </p>

                <h1 class="max-w-3xl font-display text-3xl font-extrabold leading-[1.35] text-balance-fa sm:text-4xl">
                    تعداد درخواست‌های شما بیش از حد مجاز است
                </h1>

                <p class="max-w-2xl text-sm leading-8 text-ink-200/90 sm:text-base">
                    {{ $throttleMessage }}
                </p>

                <p class="max-w-2xl text-sm leading-8 text-ink-300/90">
                    چند دقیقه صبر کنید و سپس فرم را دوباره ارسال کنید. اگر پیام شما ارسال نشده است، پس از این زمان دوباره تلاش کنید.
                </p>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <x-button :href="url()->current()" variant="primary" size="lg" icon="arrow" icon-position="end">
                    تلاش دوباره
                </x-button>

                <x-button :href="route('home')" variant="outline" size="lg" icon="arrow" icon-position="end">
                    صفحهٔ اصلی
                </x-button>
            </div>
        </div>
    </section>
</x-layouts.app>
