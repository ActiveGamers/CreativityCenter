@props(['post'])

@php
    $title = data_get($post, 'title', '');
    $slug = data_get($post, 'slug', '');
    $excerpt = data_get($post, 'excerpt', '');
    $category = data_get($post, 'category', '');
    $publishedAt = data_get($post, 'published_at', null);
    $cover = data_get($post, 'cover_image', null);

    $coverPath = is_array($cover)
        ? ($cover['path'] ?? null)
        : (is_object($cover) ? data_get($cover, 'path', null) : $cover);

    $imageUrl = filled($coverPath)
        ? (str_starts_with((string) $coverPath, 'http') || str_starts_with((string) $coverPath, '/')
            ? $coverPath
            : asset('storage/' . ltrim((string) $coverPath, '/')))
        : null;

    /* `category` arrives either as a backed enum (from the model) or as a
       plain string; both are resolved to a Persian label. */
    $categories = [
        'news' => 'اخبار',
        'announcement' => 'اطلاعیه',
        'event' => 'رویداد',
        'achievement' => 'افتخارآفرینی',
    ];

    $tones = [
        'news' => 'brand',
        'announcement' => 'accent',
        'event' => 'neutral',
        'achievement' => 'success',
    ];

    $categoryValue = null;
    $categoryLabel = '';

    if (is_object($category) && method_exists($category, 'label')) {
        $categoryLabel = (string) $category->label();
        $categoryValue = $category instanceof \BackedEnum ? $category->value : null;
    } elseif ($category instanceof \BackedEnum) {
        $categoryValue = $category->value;
        $categoryLabel = $categories[$categoryValue] ?? (string) $categoryValue;
    } elseif (is_string($category)) {
        $categoryValue = $category;
        $categoryLabel = $categories[$category] ?? $category;
    }

    $tone = $tones[$categoryValue] ?? 'brand';

    $href = filled($slug) && \Illuminate\Support\Facades\Route::has('news.show')
        ? route('news.show', $slug)
        : url('/');

    $publishedLabel = $publishedAt
        ? \Illuminate\Support\Carbon::parse($publishedAt)->format('Y/m/d')
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'surface-card group relative flex h-full flex-col overflow-hidden transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)]']) }}>
    @if (filled($imageUrl))
        <span class="relative block overflow-hidden bg-ink-100 dark:bg-ink-900">
            <img src="{{ $imageUrl }}" alt="{{ $title }}" loading="lazy" decoding="async"
                 class="aspect-[16/10] w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
        </span>
    @else
        {{-- No cover image: a quiet technical band keeps the rhythm of the grid. --}}
        <span class="relative block overflow-hidden border-b border-ink-900/8 bg-ink-50 dark:border-white/8 dark:bg-ink-900" aria-hidden="true">
            <span class="flex items-center gap-2 px-5 py-4 text-xs font-medium text-ink-500 dark:text-ink-300">
                <x-icon name="spark" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                <span>{{ $categoryLabel !== '' ? 'گزارش ' . $categoryLabel : 'گزارش هنرستان' }}</span>
            </span>
        </span>
    @endif

    <div class="flex flex-1 flex-col gap-3 p-5 sm:p-6">
        <div class="flex flex-wrap items-center gap-2">
            @if (filled($categoryLabel))
                <x-badge :tone="$tone">{{ $categoryLabel }}</x-badge>
            @endif

            @if (filled($publishedLabel))
                <span class="inline-flex items-center gap-1.5 text-xs tabular-nums text-slate-500 dark:text-ink-300">
                    <x-icon name="calendar" class="size-3.5 shrink-0 opacity-60" />
                    <bdi dir="ltr">{{ $publishedLabel }}</bdi>
                </span>
            @endif
        </div>

        <h3 class="font-display text-base font-bold leading-8 text-ink-900 text-balance-fa transition-colors group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-300">
            <a href="{{ $href }}" class="before:absolute before:inset-0">{{ $title }}</a>
        </h3>

        @if (filled($excerpt))
            <p class="line-clamp-3 text-sm leading-7 text-slate-600 dark:text-ink-200">{{ $excerpt }}</p>
        @endif

        <span class="mt-auto inline-flex items-center gap-1.5 pt-2 text-xs font-semibold text-brand-600 dark:text-brand-300">
            <span>ادامه مطلب</span>
            <x-icon name="arrow" class="size-4 shrink-0 transition-transform duration-300 group-hover:-translate-x-1" />
        </span>
    </div>
</article>
