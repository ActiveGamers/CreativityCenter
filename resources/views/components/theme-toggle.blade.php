@props(['class' => null])

<button type="button" data-theme-toggle aria-label="تغییر حالت روشن و تاریک" title="تغییر پوسته"
        {{ $attributes->merge(['class' => trim('grid size-10 shrink-0 place-items-center rounded-lg border border-ink-900/10 bg-white text-ink-700 transition-colors hover:border-brand-500/50 hover:text-brand-600 dark:border-white/10 dark:bg-white/5 dark:text-ink-100 dark:hover:border-brand-400/50 dark:hover:text-brand-300 ' . ($class ?? ''))]) }}>
    <x-icon name="sun" class="size-5 dark:hidden" />
    <x-icon name="moon" class="hidden size-5 dark:block" />
</button>
