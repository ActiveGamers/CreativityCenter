@props(['image'])

@php
    $path = data_get($image, 'path', '');
    $caption = data_get($image, 'caption', '');
    $title = data_get($image, 'title', '');
    $album = data_get($image, 'album', '') ?: 'عمومی';

    $src = filled($path)
        ? (str_starts_with((string) $path, 'http') || str_starts_with((string) $path, '/')
            ? $path
            : asset('storage/' . ltrim((string) $path, '/')))
        : null;

    $alt = filled($title) ? $title : (filled($caption) ? $caption : 'تصویر گالری هنرستان');
@endphp

<figure data-filter-item data-category="{{ $album }}"
        {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-xl border border-ink-900/10 bg-ink-100 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] dark:border-white/10 dark:bg-ink-900']) }}>
    @if (filled($src))
        {{-- Only a real image opens the lightbox: a placeholder has nothing
             to show and must not become a trigger. --}}
        <button type="button" data-lightbox data-caption="{{ filled($caption) ? $caption : $alt }}"
                class="block w-full cursor-zoom-in text-start" aria-label="نمایش بزرگ تصویر {{ $alt }}">
            <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" decoding="async"
                 class="aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
        </button>
    @else
        <div class="block w-full">
            <span class="grid aspect-[4/3] w-full place-items-center bg-ink-100 text-ink-400 dark:bg-ink-900 dark:text-ink-500">
                <x-icon name="chart" class="size-8" />
            </span>
        </div>
    @endif

    <figcaption class="pointer-events-none absolute inset-x-0 bottom-0 flex flex-col gap-1 bg-linear-to-t from-ink-950/92 via-ink-950/65 to-transparent p-4 pt-12 text-white">
        @if (filled($title))
            <span class="text-sm font-semibold leading-6">{{ $title }}</span>
        @endif

        @if (filled($caption))
            <span class="text-[0.72rem] leading-6 text-white/75">{{ $caption }}</span>
        @endif

        <span class="text-[0.68rem] text-white/55">{{ $album }}</span>
    </figcaption>

    <span class="pointer-events-none absolute end-3 top-3 grid size-9 place-items-center rounded-full border border-white/20 bg-ink-950/50 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100" aria-hidden="true">
        <x-icon name="spark" class="size-4" />
    </span>
</figure>
