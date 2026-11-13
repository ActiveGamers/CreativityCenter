@props(['name', 'class' => 'size-5'])

@php
    /*
     * A single, hand-drawn stroke icon set on a 24x24 grid.
     * Every glyph uses `currentColor` and inherits stroke settings from the
     * wrapper <svg>, so a component only has to control its colour.
     */
    $icons = [
        'network' => '<circle cx="12" cy="12" r="2.6"/><circle cx="5" cy="5.5" r="1.9"/><circle cx="19" cy="5.5" r="1.9"/><circle cx="12" cy="19.5" r="1.9"/><path d="m6.7 7 3.2 3.2M17.3 7l-3.2 3.2M12 14.6v2.9"/>',

        'mechatronics' => '<circle cx="12" cy="12" r="5.4"/><circle cx="12" cy="12" r="1.8"/><path d="M12 2.8v2.4M12 18.8v2.4M2.8 12h2.4M18.8 12h2.4"/><path d="m5.5 5.5 1.7 1.7M16.8 16.8l1.7 1.7M18.5 5.5l-1.7 1.7M7.2 16.8l-1.7 1.7"/>',

        'trophy' => '<path d="M8 3.8h8v5.6a4 4 0 0 1-8 0Z"/><path d="M8 5.6H6.2A2.4 2.4 0 0 0 8.6 10M16 5.6h1.8A2.4 2.4 0 0 1 15.4 10"/><path d="M12 13.4v3.3"/><path d="M9 20.3h6l-.7-3.6H9.7Z"/>',

        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',

        'mail' => '<rect x="2.5" y="4.5" width="19" height="15" rx="2.5"/><path d="m3.2 7 8.1 5.4a1.6 1.6 0 0 0 1.8 0L20.8 7"/>',

        'location' => '<path d="M20 10.5c0 5.4-8 12-8 12s-8-6.6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10.2" r="2.8"/>',

        'menu' => '<path d="M3.5 7h17M3.5 12h17M3.5 17h17"/>',

        'close' => '<path d="m6 6 12 12M18 6 6 18"/>',

        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2.6v2M12 19.4v2M2.6 12h2M19.4 12h2M5.4 5.4l1.4 1.4M17.2 17.2l1.4 1.4M18.6 5.4l-1.4 1.4M6.8 17.2l-1.4 1.4"/>',

        'moon' => '<path d="M20.5 14.3A8.5 8.5 0 0 1 9.7 3.5a8.5 8.5 0 1 0 10.8 10.8Z"/>',

        'arrow' => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',

        'graduation' => '<path d="M2.8 9 12 4.5 21.2 9 12 13.5 2.8 9Z"/><path d="M6.5 11v5.2c0 1.6 2.5 3 5.5 3s5.5-1.4 5.5-3V11"/><path d="M21.2 9v5"/>',

        'building' => '<path d="M3.5 20.5h17"/><path d="M5.5 20.5V5.2L12 2.5l6.5 2.7v15.3"/><path d="M9.8 20.5v-4.2h4.4v4.2"/><path d="M9.8 9.6h1.4M12.8 9.6h1.4M9.8 12.6h1.4M12.8 12.6h1.4"/>',

        'chip' => '<rect x="6.5" y="6.5" width="11" height="11" rx="2"/><rect x="9.8" y="9.8" width="4.4" height="4.4" rx="1"/><path d="M9.5 3v3.5M14.5 3v3.5M9.5 17.5V21M14.5 17.5V21M3 9.5h3.5M3 14.5h3.5M17.5 9.5H21M17.5 14.5H21"/>',

        'wrench' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',

        'chart' => '<path d="M4 20.5h16"/><path d="M7.5 20.5v-6M12 20.5V7M16.5 20.5v-9"/>',

        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 9.6h17M8 3v4M16 3v4"/>',

        'user' => '<circle cx="12" cy="8" r="3.8"/><path d="M4.6 20.5a7.4 7.4 0 0 1 14.8 0"/>',

        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',

        'download' => '<path d="M12 3.5v11.2"/><path d="m7.5 10.8 4.5 4.5 4.5-4.5"/><path d="M4.5 19.5h15"/>',

        'spark' => '<path d="M12 3.5 13.8 9.4 19.7 11.2 13.8 13 12 18.9 10.2 13 4.3 11.2 10.2 9.4 12 3.5Z"/>',

        'quote' => '<path d="M3 21c3 0 7-1 7-8V5c0-1.25-.76-2-2-2H4c-1.25 0-2 .75-2 2v6c0 1.25.75 2 2 2h3c0 4-1 5-4 6v2Z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.76-2-2-2h-4c-1.25 0-2 .75-2 2v6c0 1.25.75 2 2 2h3c0 4-1 5-4 6v2Z"/>',
    ];

    /*
     * Foreign vocabulary coming from the seeded content (statistic icons,
     * for instance) is mapped onto the canonical set above so nothing ever
     * falls back to a meaningless glyph.
     */
    $aliases = [
        'award' => 'trophy',
        'medal' => 'trophy',
        'certificate' => 'graduation',
        'star' => 'spark',
        'layers' => 'chart',
        'book' => 'graduation',
        'cap' => 'graduation',
        'school' => 'building',
        'gear' => 'mechatronics',
        'cpu' => 'chip',
        'tool' => 'wrench',
        'mobile' => 'phone',
        'email' => 'mail',
        'map' => 'location',
        'arrow-left' => 'arrow',
        'plus' => 'check',
    ];

    $key = isset($icons[$name]) ? $name : ($aliases[$name] ?? null);

    $body = $key ? $icons[$key] : $icons['spark'];
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="{{ $class }}"
     @unless ($attributes->has('aria-label')) aria-hidden="true" @endunless {{ $attributes }}>
    {!! $body !!}
</svg>
