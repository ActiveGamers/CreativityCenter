@props(['title' => null, 'description' => null])

@php
    $schoolName = config('school.name') ?? config('app.name', 'هنرستان فنی');
    $pageTitle = filled($title) ? $title . ' | ' . $schoolName : $schoolName;
    $metaDescription = filled($description)
        ? $description
        : (config('school.description') ?? 'معرفی رشته‌ها، برنامه‌های آموزشی، دستاوردها و اخبار هنرستان فنی پسرانه جوار شرکت پالایش نفت بندرعباس.');
    $ogImage = config('school.og_image') ?: asset('images/og-image.jpg');
@endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#071E2A">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $schoolName }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $schoolName }}">
    <meta property="og:locale" content="fa_IR">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Theme bootstrap: runs before first paint so the palette never flashes.
         Keep it in sync with resources/js/theme.js. --}}
    <script>
        (function () {
            try {
                document.documentElement.classList.add('js');
                var stored = window.localStorage.getItem('theme');
                var theme = (stored === 'light' || stored === 'dark')
                    ? stored
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
                document.documentElement.dataset.theme = theme;
            } catch (error) {
                /* storage blocked — fall back to the light palette */
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>
<body class="min-h-dvh font-sans antialiased">
    <a href="#main"
       class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:end-3 focus:z-[200] focus:rounded-lg focus:bg-ink-900 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white">
        پرش به محتوای اصلی
    </a>

    <x-site-header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site-footer />

    <x-toast-container />
</body>
</html>
