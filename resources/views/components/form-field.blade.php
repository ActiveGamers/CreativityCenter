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

    /* The slot holds the control itself. It is rendered once here, because
       the markup decides whether the field is a single control or a radio
       group. */
    $control = $slot->toHtml();

    /* A radio set has no single control to point at: the question is asked
       by a legend and the answers are the radio buttons themselves. */
    $isRadioGroup = str_contains($control, 'type="radio"');

    $errorId = filled($name) ? 'error-'.$name : null;

    /* A server-rendered error belongs to the control: the attributes that
       link the two live on the control the caller put in the slot. */
    if (filled($error) && filled($errorId)) {
        $control = $isRadioGroup
            ? preg_replace_callback(
                '/<input\b/i',
                fn (array $matches): string => '<input aria-invalid="true"',
                $control,
            )
            : preg_replace_callback(
                '/<(input|select|textarea)\b/i',
                fn (array $matches): string => '<'.$matches[1].' aria-invalid="true" aria-describedby="'.$errorId.'"',
                $control,
                1,
            );
    }
@endphp

@if ($isRadioGroup)
    {{-- A group of answers: the legend asks the question, the error is
         described by the group itself. --}}
    <fieldset {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}
              @if (filled($errorId)) aria-describedby="{{ $errorId }}" @endif>
        @if (filled($label))
            <legend class="flex items-center gap-1.5 text-sm font-medium text-ink-800 dark:text-ink-100">
                @if (filled($icon))
                    <x-icon :name="$icon" class="size-4 shrink-0 text-brand-600 dark:text-brand-300" />
                @endif

                <span>{{ $label }}</span>

                @if ($required)
                    <span class="text-accent-600 dark:text-accent-400" aria-hidden="true">*</span>
                    <span class="sr-only">(الزامی)</span>
                @endif
            </legend>
        @endif

        {!! $control !!}

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
    </fieldset>
@else
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

        {!! $control !!}

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
@endif
