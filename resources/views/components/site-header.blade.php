@php
    $school = config('school') ?? [];

    $address = $school['address'] ?? '';
    $managerName = $school['manager_name'] ?? '';
    $managerPhone = $school['manager_phone'] ?? '';
    $deputyName = $school['deputy_name'] ?? '';
    $deputyPhone = $school['deputy_phone'] ?? '';
    $shortName = $school['short_name'] ?? ($school['name'] ?? 'هنرستان فنی');
    $fullName = $school['name'] ?? config('app.name', 'هنرستان فنی');

    /* Route names are resolved through the router; a graceful fallback keeps
       the header renderable while the routes are still being wired up. */
    $resolve = fn (string $name): string => \Illuminate\Support\Facades\Route::has($name)
        ? route($name)
        : url('/');

    $homeUrl = $resolve('home');
    $contactUrl = $resolve('contact.index');

    $navItems = collect([
        ['label' => 'خانه', 'route' => 'home'],
        ['label' => 'درباره هنرستان', 'route' => 'about'],
        ['label' => 'رشته‌های تحصیلی', 'route' => 'majors.index'],
        ['label' => 'آموزش', 'route' => 'education'],
        ['label' => 'افتخارات', 'route' => 'achievements'],
        ['label' => 'اخبار', 'route' => 'news.index'],
        ['label' => 'گالری', 'route' => 'gallery'],
        ['label' => 'تماس با ما', 'route' => 'contact.index'],
    ])->map(fn (array $item): array => [
        'label' => $item['label'],
        'href' => $resolve($item['route']),
        'active' => request()->routeIs($item['route']),
    ])->all();
@endphp

<header data-site-header class="sticky top-0 z-50">
    {{-- Slim utility bar: address and the two direct phone lines --}}
    <div class="hidden border-b border-white/5 bg-ink-950 text-ink-100/80 lg:block">
        <div class="container-page flex h-10 items-center justify-between gap-6 text-[0.78rem]">
            <div class="flex min-w-0 items-center gap-6">
                @if (filled($address))
                    <span class="inline-flex min-w-0 items-center gap-1.5">
                        <x-icon name="location" class="size-4 shrink-0 text-brand-300" />
                        <span class="truncate">{{ $address }}</span>
                    </span>
                @endif

                @if (filled($managerPhone))
                    <span class="inline-flex shrink-0 items-center gap-1.5">
                        <x-icon name="user" class="size-4 shrink-0 text-brand-300" />
                        <span>{{ filled($managerName) ? $managerName : 'مدیر' }}</span>
                        <bdi class="tabular-nums font-medium text-white">{{ $managerPhone }}</bdi>
                    </span>
                @endif

                @if (filled($deputyPhone))
                    <span class="inline-flex shrink-0 items-center gap-1.5">
                        <x-icon name="user" class="size-4 shrink-0 text-brand-300" />
                        <span>{{ filled($deputyName) ? $deputyName : 'معاون' }}</span>
                        <bdi class="tabular-nums font-medium text-white">{{ $deputyPhone }}</bdi>
                    </span>
                @endif
            </div>

            <span class="inline-flex shrink-0 items-center gap-1.5 text-accent-300">
                <x-icon name="trophy" class="size-4 shrink-0" />
                <span>رتبه اول کشوری ۱۴۰۳</span>
            </span>
        </div>
    </div>

    {{-- Main bar --}}
    <div data-header-bar class="border-b border-ink-900/10 bg-white dark:border-white/10 dark:bg-ink-950">
        <div class="container-page flex h-16 items-center justify-between gap-4 lg:h-20">
            {{-- Wordmark --}}
            <a href="{{ $homeUrl }}" class="group flex shrink-0 items-center gap-3">
                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-ink-900 text-white transition-colors group-hover:bg-brand-500 dark:bg-brand-500 dark:text-ink-950">
                    <x-icon name="graduation" class="size-6" />
                </span>
                <span class="flex flex-col leading-tight">
                    <span class="font-display text-[0.95rem] font-extrabold text-ink-900 dark:text-white">
                        {{ $shortName }}
                    </span>
                    <span class="hidden text-[0.7rem] text-slate-500 sm:block dark:text-ink-200">
                        {{ $fullName }}
                    </span>
                </span>
            </a>

            {{-- Desktop navigation --}}
            <nav class="hidden items-center gap-6 lg:flex" aria-label="فهرست اصلی">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" data-nav-link @if ($item['active']) aria-current="page" @endif
                       class="text-sm font-medium text-ink-700 transition-colors hover:text-brand-600 dark:text-ink-100 dark:hover:text-brand-300">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex shrink-0 items-center gap-2">
                <x-theme-toggle />

                {{-- `max-sm:hidden` (not `hidden`) so the utility wins over the
                     button's own `inline-flex` display value. --}}
                <x-button :href="$contactUrl" variant="primary" size="sm" class="max-sm:hidden">
                    ثبت‌نام و تماس
                </x-button>

                <button type="button" data-drawer-open aria-controls="mobile-nav" aria-expanded="false"
                        aria-label="باز کردن منو"
                        class="grid size-10 shrink-0 place-items-center rounded-lg border border-ink-900/10 text-ink-800 transition-colors hover:border-brand-500/50 hover:text-brand-600 lg:hidden dark:border-white/10 dark:text-ink-100 dark:hover:border-brand-400/50 dark:hover:text-brand-300">
                    <x-icon name="menu" class="size-5" />
                </button>
            </div>
        </div>
    </div>
</header>

{{-- The fallback navigation: the off-canvas drawer cannot be opened
     without JavaScript, so the main links live in the markup as a plain,
     always usable navigation. It is a real element — the CSS hides it as
     soon as the drawer is wired (see `html.nav-ready [data-fallback-nav]` in
     app.css), so the fallback and the drawer are never visible together. --}}
<nav data-fallback-nav aria-label="فهرست اصلی (بدون جاوااسکریپت)"
     class="border-b border-ink-900/10 bg-ink-50 dark:border-white/10 dark:bg-ink-950">
    <div class="container-page flex flex-wrap items-center gap-x-5 gap-y-1 py-3 text-sm font-medium sm:gap-x-6">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}" @if ($item['active']) aria-current="page" @endif
               class="rounded-md px-1 py-1 transition-colors hover:text-brand-600 dark:hover:text-brand-300 {{ $item['active'] ? 'text-brand-600 dark:text-brand-300' : 'text-ink-700 dark:text-ink-100' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</nav>

{{-- Mobile drawer --}}
<div data-drawer-backdrop class="fixed inset-0 z-[60] hidden bg-ink-950/60 opacity-0 transition-opacity duration-300"
     aria-hidden="true"></div>

{{-- The drawer is closed by default: `inert` keeps its links out of the tab
     order until the drawer is opened. --}}
<aside id="mobile-nav" data-drawer aria-label="فهرست موبایل" aria-hidden="true" inert
       class="fixed inset-y-0 end-0 z-[70] flex w-[86%] max-w-sm flex-col border-s border-ink-900/10 bg-white shadow-[var(--shadow-pop)] lg:hidden dark:border-white/10 dark:bg-ink-950">
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-ink-900/10 px-5 dark:border-white/10">
        <span class="font-display text-sm font-bold text-ink-900 dark:text-white">{{ $shortName }}</span>

        <button type="button" data-drawer-close aria-label="بستن منو"
                class="grid size-10 place-items-center rounded-lg border border-ink-900/10 text-ink-800 transition-colors hover:border-brand-500/50 hover:text-brand-600 dark:border-white/10 dark:text-ink-100">
            <x-icon name="close" class="size-5" />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5" aria-label="فهرست موبایل">
        <ul class="flex flex-col gap-1">
            @foreach ($navItems as $item)
                <li>
                    <a href="{{ $item['href'] }}" data-nav-link @if ($item['active']) aria-current="page" @endif
                       class="flex items-center justify-between gap-3 rounded-lg px-3 py-3 text-sm font-medium text-ink-700 transition-colors hover:bg-ink-50 dark:text-ink-100 dark:hover:bg-white/5">
                        <span>{{ $item['label'] }}</span>
                        <x-icon name="arrow" class="size-4 shrink-0 opacity-35" />
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    @if (filled($managerPhone) || filled($deputyPhone))
        <div class="shrink-0 space-y-2 border-t border-ink-900/10 p-5 dark:border-white/10">
            @if (filled($managerPhone))
                <a href="tel:{{ $managerPhone }}"
                   class="flex items-center gap-2 rounded-lg border border-ink-900/10 px-3 py-2.5 text-sm transition-colors hover:border-brand-500/40 dark:border-white/10">
                    <x-icon name="phone" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                    <span class="text-ink-600 dark:text-ink-200">{{ filled($managerName) ? $managerName : 'مدیر' }}</span>
                    <bdi class="ms-auto tabular-nums font-medium text-ink-900 dark:text-white">{{ $managerPhone }}</bdi>
                </a>
            @endif

            @if (filled($deputyPhone))
                <a href="tel:{{ $deputyPhone }}"
                   class="flex items-center gap-2 rounded-lg border border-ink-900/10 px-3 py-2.5 text-sm transition-colors hover:border-brand-500/40 dark:border-white/10">
                    <x-icon name="phone" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                    <span class="text-ink-600 dark:text-ink-200">{{ filled($deputyName) ? $deputyName : 'معاون' }}</span>
                    <bdi class="ms-auto tabular-nums font-medium text-ink-900 dark:text-white">{{ $deputyPhone }}</bdi>
                </a>
            @endif
        </div>
    @endif
</aside>
