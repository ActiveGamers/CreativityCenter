@props([
    'src' => null,
    'hint' => null,
    'label' => 'محل قرارگیری تصویر',
    'alt' => 'نمای فنی رشته‌های هنرستان',
])

@php
    /*
     * The hero photo slot.
     *
     * The school drops a real landscape photograph (16:9) into
     * `public/images/school/technical-view.jpg`. When the file is there it is
     * rendered inside the frame; until then the frame shows an honest
     * placeholder that names the exact path the photo has to be placed at.
     *
     * `src` overrides the path without touching the markup.
     */
    $relativePath = $src ?: 'images/school/technical-view.jpg';
    $imageExists = file_exists(public_path($relativePath));
    $imageUrl = asset($relativePath);
    $hint = $hint ?: 'public/'.$relativePath;
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full overflow-hidden rounded-2xl border border-ink-900/10 bg-[var(--surface-muted)] dark:border-white/10']) }}>
    @if ($imageExists)
        <img src="{{ $imageUrl }}" alt="{{ $alt }}"
             class="aspect-[16/9] w-full object-cover" loading="eager" decoding="async">
    @else
        {{-- Placeholder: a dashed hairline frame that reads as a photo slot. --}}
        <div class="flex aspect-[16/9] w-full flex-col items-center justify-center gap-3 border-2 border-dashed border-ink-900/15 p-4 text-center dark:border-white/20">
            <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-ink-100 text-ink-500 dark:bg-white/5 dark:text-brand-300">
                <x-icon name="building" class="size-6" />
            </span>

            <span class="text-sm font-semibold text-ink-800 dark:text-ink-100">{{ $label }}</span>

            <span class="text-xs leading-6 text-slate-500 dark:text-ink-300">
                تصویر باید در مسیر زیر قرار گیرد:
            </span>

            <code class="mt-1 block w-full break-all rounded-md bg-ink-100 px-2 py-1 text-center font-mono text-[0.7rem] leading-5 text-ink-700 dark:bg-white/10 dark:text-ink-200" dir="ltr">{{ $hint }}</code>
        </div>
    @endif
</div>
