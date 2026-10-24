@props([
    'member' => null,
    'detailed' => false,
])

@php
    $name = data_get($member, 'name', '');
    $role = data_get($member, 'role', '');
    $subject = data_get($member, 'subject', '');
    $bio = data_get($member, 'bio', '');
    $phone = data_get($member, 'phone', '');

    /* The manager gets the amber plaque, the deputy the petrol one. */
    $isManager = str_contains((string) $role, 'مدیر');

    $chip = $isManager
        ? 'bg-accent-500/12 text-accent-600 dark:bg-accent-500/15 dark:text-accent-300'
        : 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300';

    $badge = $isManager
        ? 'border-accent-200 bg-accent-50 text-accent-700 dark:border-accent-500/25 dark:bg-accent-950/60 dark:text-accent-300'
        : 'border-brand-200 bg-brand-50 text-brand-700 dark:border-brand-500/25 dark:bg-brand-950/60 dark:text-brand-300';
@endphp

<article {{ $attributes->merge(['class' => 'surface-card flex flex-col gap-5 p-6 transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)]']) }}>
    <div class="flex items-center gap-4">
        <span class="grid size-14 shrink-0 place-items-center rounded-2xl {{ $chip }}">
            <x-icon name="user" class="size-7" />
        </span>

        <div class="min-w-0">
            <h3 class="font-display text-lg font-bold leading-8 text-ink-900 text-balance-fa dark:text-white">
                {{ $name }}
            </h3>

            @if (filled($role))
                <span class="mt-2 inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $badge }}">
                    {{ $role }}
                </span>
            @endif
        </div>
    </div>

    @if ($detailed && filled($bio))
        <p class="text-sm leading-8 text-slate-600 dark:text-ink-200">{{ $bio }}</p>
    @endif

    @if (filled($subject))
        <p class="flex items-center gap-2 text-sm text-ink-700 dark:text-ink-100">
            <x-icon name="book" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
            <span>{{ $subject }}</span>
        </p>
    @endif

    @if (filled($phone))
        <a href="tel:{{ $phone }}"
           class="mt-auto inline-flex items-center gap-2.5 rounded-xl border border-ink-900/10 px-3.5 py-2.5 text-sm font-medium text-ink-800 transition-colors hover:border-brand-500/50 hover:text-brand-600 dark:border-white/10 dark:text-ink-100 dark:hover:border-brand-400/50 dark:hover:text-brand-300">
            <x-icon name="phone" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
            <bdi class="tabular-nums">{{ $phone }}</bdi>
        </a>
    @endif
</article>
