@php
    $categoryLabel = $post->category->label();

    /* The badge tone belongs to the category itself, not to this view. */
    $tone = $post->category->tone();

    $paragraphs = collect(preg_split('/\R{2,}/', (string) $post->body))
        ->map(fn (string $paragraph): string => trim($paragraph))
        ->filter()
        ->values();

    $publishedAt = $post->published_at;

    /* The publication date is shown in the Persian calendar. */
    $publishedLabel = $publishedAt
        ? \Morilog\Jalali\Jalalian::fromCarbon($publishedAt)->format('Y/m/d')
        : null;
@endphp

<x-layouts.app
    :title="$post->title"
    :description="filled($post->excerpt) ? $post->excerpt : $post->title">

    <article>
        <header class="dark relative isolate overflow-hidden bg-ink-950 text-white">
            <div class="pointer-events-none absolute inset-0 -z-10 texture-grid" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -top-28 start-[-12%] -z-10 h-80 w-[75%] hero-wash opacity-80" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-px bg-brand-500/35" aria-hidden="true"></div>

            <div class="container-page py-12 sm:py-16 lg:py-20">
                <x-breadcrumb
                    :items="[
                        ['label' => 'خانه', 'href' => route('home')],
                        ['label' => 'اخبار', 'href' => route('news.index')],
                        ['label' => $post->title],
                    ]"
                    class="mb-8" />

                <div class="flex flex-col gap-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-badge :tone="$tone">{{ $categoryLabel }}</x-badge>

                        @if ($publishedLabel)
                            <span class="inline-flex items-center gap-1.5 text-xs tabular-nums text-ink-300">
                                <x-icon name="calendar" class="size-3.5 shrink-0 opacity-70" />
                                <bdi dir="ltr">{{ $publishedLabel }}</bdi>
                            </span>
                        @endif
                    </div>

                    <h1 class="max-w-4xl font-display text-2xl font-extrabold leading-[1.45] text-balance-fa sm:text-3xl lg:text-4xl">
                        {{ $post->title }}
                    </h1>

                    @if (filled($post->excerpt))
                        <p class="max-w-3xl text-sm leading-9 text-ink-200/90 sm:text-base">{{ $post->excerpt }}</p>
                    @endif
                </div>
            </div>
        </header>

        <div class="section-y">
            <div class="container-page grid gap-10 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-8">
                    <div class="prose-fa">
                        @foreach ($paragraphs as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-ink-900/10 pt-6 dark:border-white/10">
                        <a href="{{ route('news.index') }}"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 transition-colors hover:text-brand-500 dark:text-brand-300">
                            <x-icon name="arrow" class="size-4 rotate-180" />
                            <span>بازگشت به فهرست اخبار</span>
                        </a>

                        <x-button :href="route('contact.index')" variant="outline" size="sm" icon="phone" icon-position="start">
                            پرسشی دارید؟ تماس بگیرید
                        </x-button>
                    </div>
                </div>

                <aside class="lg:col-span-4">
                    <div class="surface-card flex flex-col gap-5 p-6">
                        <h2 class="font-display text-base font-bold text-ink-900 dark:text-white">این خبر را از کجا دنبال کنیم؟</h2>

                        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">
                            برای دریافت اطلاعیه‌های آموزشی و پاسخ به پرسش‌های خود، با کادر آموزشی هنرستان تماس بگیرید یا فرم مشاوره و ثبت‌نام را تکمیل کنید.
                        </p>

                        <ul class="flex flex-col gap-3.5 text-sm leading-7 text-slate-600 dark:text-ink-200">
                            <li class="flex items-start gap-2.5">
                                <x-icon name="location" class="mt-1 size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                                <span>{{ config('school.address') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <x-icon name="phone" class="mt-1 size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                                <bdi dir="ltr" class="tabular-nums">
                                    <a href="tel:{{ config('school.manager_phone') }}" class="transition-colors hover:text-brand-600 dark:hover:text-brand-300">{{ config('school.manager_phone') }}</a>
                                </bdi>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <x-icon name="phone" class="mt-1 size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                                <bdi dir="ltr" class="tabular-nums">
                                    <a href="tel:{{ config('school.deputy_phone') }}" class="transition-colors hover:text-brand-600 dark:hover:text-brand-300">{{ config('school.deputy_phone') }}</a>
                                </bdi>
                            </li>
                        </ul>

                        <div class="border-t border-ink-900/8 pt-5 dark:border-white/10">
                            <x-button :href="route('admission.index')" variant="primary" size="sm" icon="arrow" icon-position="end">
                                درخواست مشاوره و ثبت‌نام
                            </x-button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    <x-pages.related-posts :post="$post" />
</x-layouts.app>
