@php
    $school = config('school') ?? [];

    $address = $school['address'] ?? '';
    $managerName = $school['manager_name'] ?? '';
    $managerPhone = $school['manager_phone'] ?? '';
    $deputyName = $school['deputy_name'] ?? '';
    $deputyPhone = $school['deputy_phone'] ?? '';
    $shortName = $school['short_name'] ?? ($school['name'] ?? 'هنرستان فنی');
    $fullName = $school['name'] ?? config('app.name', 'هنرستان فنی');
    $description = $school['description'] ?? 'هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس؛ مرجع آموزش رشته‌های برق، الکترونیک، مکاترونیک، شبکه و مکانیک.';

    $resolve = fn (string $name): string => \Illuminate\Support\Facades\Route::has($name)
        ? route($name)
        : url('/');

    $quickLinks = collect([
        ['label' => 'صفحه اصلی', 'route' => 'home'],
        ['label' => 'درباره هنرستان', 'route' => 'about'],
        ['label' => 'رشته‌های تحصیلی', 'route' => 'majors.index'],
        ['label' => 'برنامه‌های آموزشی', 'route' => 'education'],
        ['label' => 'افتخارات کسب‌شده', 'route' => 'achievements'],
        ['label' => 'اخبار و رویدادها', 'route' => 'news.index'],
        ['label' => 'گالری تصاویر', 'route' => 'gallery'],
        ['label' => 'تماس با ما', 'route' => 'contact.index'],
    ])->map(fn (array $item): array => [
        'label' => $item['label'],
        'href' => $resolve($item['route']),
    ])->all();

    /* The footer lists the first few fields. The list is read from the model
       when it is available and cached for an hour so it never turns the
       footer into a per-request database hit. */
    $majorLinks = [];

    try {
        if (class_exists(\App\Models\Major::class) && \Illuminate\Support\Facades\Route::has('majors.show')) {
            $majorLinks = \Illuminate\Support\Facades\Cache::remember('footer.major_links', 3600, function () {
                return \App\Models\Major::query()
                    ->select(['id', 'title', 'slug'])
                    ->orderBy('id')
                    ->limit(6)
                    ->get()
                    ->map(fn ($major): array => [
                        'title' => $major->title,
                        'href' => route('majors.show', $major->slug),
                    ])
                    ->all();
            });
        }
    } catch (\Throwable $exception) {
        $majorLinks = [];
    }

    $mapUrl = filled($address)
        ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address)
        : 'https://www.google.com/maps';
@endphp

<footer class="mt-auto border-t border-ink-900/10 bg-ink-950 text-ink-100 dark:border-white/10">
    <div class="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8 lg:py-16">
        {{-- درباره هنرستان --}}
        <div class="lg:col-span-4">
            <a href="{{ $resolve('home') }}" class="flex items-center gap-3">
                <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-500 text-ink-950">
                    <x-icon name="graduation" class="size-6" />
                </span>
                <span class="flex flex-col leading-tight">
                    <span class="font-display text-base font-extrabold text-white">{{ $shortName }}</span>
                    <span class="text-xs text-ink-300">{{ $fullName }}</span>
                </span>
            </a>

            <p class="mt-5 max-w-sm text-sm leading-8 text-ink-200/90">{{ $description }}</p>

            <div class="mt-6 inline-flex items-center gap-2 rounded-lg border border-accent-500/30 bg-accent-500/10 px-3 py-2 text-xs font-medium text-accent-300">
                <x-icon name="trophy" class="size-4 shrink-0" />
                <span>رتبه اول کشوری ۱۴۰۳</span>
            </div>
        </div>

        {{-- رشته‌ها --}}
        <div class="lg:col-span-2">
            <h2 class="font-display text-sm font-bold text-white">رشته‌ها</h2>
            <span class="mt-3 block h-px w-10 bg-brand-500"></span>

            <ul class="mt-4 space-y-3 text-sm">
                @forelse ($majorLinks as $major)
                    <li>
                        <a href="{{ $major['href'] }}" class="text-ink-200/90 transition-colors hover:text-brand-300">
                            {{ $major['title'] }}
                        </a>
                    </li>
                @empty
                    <li>
                        <a href="{{ $resolve('majors.index') }}" class="text-ink-200/90 transition-colors hover:text-brand-300">
                            همه رشته‌های تحصیلی
                        </a>
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- دسترسی سریع --}}
        <div class="lg:col-span-3">
            <h2 class="font-display text-sm font-bold text-white">دسترسی سریع</h2>
            <span class="mt-3 block h-px w-10 bg-brand-500"></span>

            <ul class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2 lg:grid-cols-1">
                @foreach ($quickLinks as $link)
                    <li>
                        <a href="{{ $link['href'] }}" class="text-ink-200/90 transition-colors hover:text-brand-300">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ارتباط با ما --}}
        <div class="lg:col-span-3">
            <h2 class="font-display text-sm font-bold text-white">ارتباط با ما</h2>
            <span class="mt-3 block h-px w-10 bg-brand-500"></span>

            <ul class="mt-4 space-y-3.5 text-sm">
                @if (filled($address))
                    <li class="flex items-start gap-2.5">
                        <x-icon name="location" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <span class="text-ink-200/90">
                            <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer"
                               class="transition-colors hover:text-brand-300">{{ $address }}</a>
                        </span>
                    </li>
                @endif

                @if (filled($managerPhone))
                    <li class="flex items-start gap-2.5">
                        <x-icon name="phone" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <span class="text-ink-200/90">
                            {{ filled($managerName) ? $managerName : 'مدیر' }}:
                            <bdi class="tabular-nums"><a href="tel:{{ $managerPhone }}" class="transition-colors hover:text-brand-300">{{ $managerPhone }}</a></bdi>
                        </span>
                    </li>
                @endif

                @if (filled($deputyPhone))
                    <li class="flex items-start gap-2.5">
                        <x-icon name="phone" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <span class="text-ink-200/90">
                            {{ filled($deputyName) ? $deputyName : 'معاون' }}:
                            <bdi class="tabular-nums"><a href="tel:{{ $deputyPhone }}" class="transition-colors hover:text-brand-300">{{ $deputyPhone }}</a></bdi>
                        </span>
                    </li>
                @endif

                @if (filled($school['email'] ?? ''))
                    <li class="flex items-start gap-2.5">
                        <x-icon name="mail" class="mt-1 size-4 shrink-0 text-brand-300" />
                        <span class="text-ink-200/90">
                            <bdi><a href="mailto:{{ $school['email'] }}" class="transition-colors hover:text-brand-300">{{ $school['email'] }}</a></bdi>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-page flex flex-col items-center justify-between gap-3 py-5 text-xs text-ink-300 sm:flex-row">
            <p>
                © {{ date('Y') }} — تمامی حقوق برای {{ $fullName }} محفوظ است.
            </p>

            <p class="inline-flex items-center gap-1.5 text-accent-300">
                <x-icon name="trophy" class="size-4 shrink-0" />
                <span>رتبه اول کشوری ۱۴۰۳</span>
            </p>
        </div>
    </div>
</footer>
