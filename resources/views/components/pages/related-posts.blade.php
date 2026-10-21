@props([
    'post' => null,
    'limit' => 3,
    'title' => 'اخبار دیگر',
    'kicker' => 'ادامه مطالعه',
])

@php
    /*
     * The article page only receives the current post from the controller, so
     * the related list is resolved here with the same published scope the
     * news index uses.
     */
    $related = collect();

    if (filled(data_get($post, 'id'))) {
        $related = \App\Models\Post::query()
            ->published()
            ->where('id', '!=', data_get($post, 'id'))
            ->select(['id', 'title', 'slug', 'excerpt', 'category', 'cover_image', 'published_at'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit((int) $limit)
            ->get();
    }
@endphp

@if ($related->isNotEmpty())
    <section {{ $attributes->merge(['class' => 'section-y border-t border-ink-900/8 dark:border-white/8']) }}>
        <div class="container-page">
            <x-section-heading :kicker="$kicker" :title="$title" />

            <div class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    <x-post-card :post="$item" />
                @endforeach
            </div>
        </div>
    </section>
@endif
