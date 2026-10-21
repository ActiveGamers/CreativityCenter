@props([
    'name' => null,
    'label' => null,
    'required' => false,
    'help' => null,
    'error' => null,
    'icon' => null,
    'for' => null,
])

@php
    /* `for` overrides the label target. Leave it unset to point at the field
       name; pass an empty string when the control is a group (a radio set)
       and therefore has no single focusable id. */
    $forTarget = $for === null ? $name : $for;
@endphp

{{-- The slot holds the control itself (input, textarea, select …). --}}
<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    @if (filled($label))
        <label @if (filled($forTarget)) for="{{ $forTarget }}" @endif
               class="flex items-center gap-1.5 text-sm font-medium text-ink-800 dark:text-ink-100">
            @if (filled($icon))
                <x-icon :name="$icon" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
            @endif

            <span>{{ $label }}</span>

            @if ($required)
                <span class="text-accent-600 dark:text-accent-400" aria-hidden="true">*</span>
                <span class="sr-only">(الزامی)</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if (filled($help) && blank($error))
        <p class="text-xs leading-6 text-slate-500 dark:text-ink-300">{{ $help }}</p>
    @endif

    <p @if (filled($name)) id="error-{{ $name }}" data-error-for="{{ $name }}" @endif
       role="alert"
       class="flex items-start gap-1.5 text-xs font-medium leading-6 text-red-600 dark:text-red-400 {{ blank($error) ? 'hidden' : '' }}">
        @if (filled($error))
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" class="mt-1 size-3.5 shrink-0" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5"/><path d="M12 16.2h.01"/>
            </svg>
            <span>{{ $error }}</span>
        @endif
    </p>
</div>
