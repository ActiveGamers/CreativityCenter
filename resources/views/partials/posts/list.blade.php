@php
    /*
     * Shared by the news index and by the AJAX endpoint `news.more`, so the
     * markup is identical in both cases and the injected items land in the
     * same grid without any extra wrapper.
     */
    $items = $posts instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
        ? $posts->getCollection()
        : collect($posts);
@endphp

@foreach ($items as $post)
    <div data-news-item data-category="{{ $post->category->label() }}" class="h-full">
        <x-post-card :post="$post" />
    </div>
@endforeach

@if (method_exists($posts, 'hasMorePages') && $posts->hasMorePages())
    {{-- Marks the next page for the progressive enhancement in news.js. --}}
    <div data-news-next-page="{{ $posts->currentPage() + 1 }}" hidden aria-hidden="true"></div>
@endif
