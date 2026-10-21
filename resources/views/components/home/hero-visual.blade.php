@php
    /*
     * The hero anchor of the home page.
     *
     * The school has no press photo yet, so the visual is drawn instead of
     * photographed: a technical dial in the spirit of an engineering
     * drawing, with the two majors rendered as a hub-and-spoke network and
     * the chip of the mechatronics workshop at its centre.
     *
     * Every coordinate is computed so the composition stays precise at any
     * size — the drawing is resolution independent, never an image file.
     */
    $center = 180;
    $ringOuter = 168;
    $ringInner = 150;

    /* 72 ticks every five degrees, every sixth one drawn long. */
    $ticks = '';

    for ($index = 0; $index < 72; $index++) {
        $angle = deg2rad($index * 5);
        $isMajor = $index % 6 === 0;
        $inner = $isMajor ? $ringInner : $ringInner + 8;

        $ticks .= sprintf(
            '<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" stroke="#5DD7E8" stroke-opacity="%s" stroke-width="1" />',
            $center + $inner * cos($angle),
            $center + $inner * sin($angle),
            $center + $ringOuter * cos($angle),
            $center + $ringOuter * sin($angle),
            $isMajor ? '0.5' : '0.2',
        );
    }

    /* Hub and spokes of the network. */
    $nodes = [
        ['angle' => -90, 'radius' => 106, 'kind' => 'hub'],
        ['angle' => -18, 'radius' => 94, 'kind' => 'node'],
        ['angle' => 42, 'radius' => 102, 'kind' => 'node'],
        ['angle' => 118, 'radius' => 88, 'kind' => 'node'],
        ['angle' => 186, 'radius' => 98, 'kind' => 'node'],
        ['angle' => 252, 'radius' => 92, 'kind' => 'node'],
    ];

    $spokes = '';
    $dots = '';

    foreach ($nodes as $node) {
        $angle = deg2rad($node['angle']);
        $x = $center + $node['radius'] * cos($angle);
        $y = $center + $node['radius'] * sin($angle);

        $spokes .= sprintf(
            '<line x1="%s" y1="%s" x2="%.2f" y2="%.2f" stroke="#5DD7E8" stroke-opacity="0.28" stroke-width="1.2" />',
            $center,
            $center,
            $x,
            $y,
        );

        /* A small "via" point on every third spoke, like a wiring diagram. */
        if ($node['kind'] === 'hub') {
            $dots .= sprintf(
                '<circle cx="%.2f" cy="%.2f" r="2.6" fill="#5DD7E8" fill-opacity="0.55" />',
                $center + ($x - $center) * 0.55,
                $center + ($y - $center) * 0.55,
            );
        }
    }

    $nodeDots = '';

    foreach ($nodes as $node) {
        $angle = deg2rad($node['angle']);
        $x = $center + $node['radius'] * cos($angle);
        $y = $center + $node['radius'] * sin($angle);

        if ($node['kind'] === 'hub') {
            $nodeDots .= sprintf(
                '<circle cx="%.2f" cy="%.2f" r="10" fill="#FBBF24" fill-opacity="0.16" />'
                . '<circle cx="%.2f" cy="%.2f" r="4.6" fill="#FBBF24" />'
                . '<circle cx="%.2f" cy="%.2f" r="8" fill="none" stroke="#FBBF24" stroke-opacity="0.55" stroke-width="1" />',
                $x,
                $y,
                $x,
                $y,
                $x,
                $y,
            );

            continue;
        }

        $nodeDots .= sprintf(
            '<circle cx="%.2f" cy="%.2f" r="6.4" fill="#071E2A" />'
            . '<circle cx="%.2f" cy="%.2f" r="6.4" fill="none" stroke="#5DD7E8" stroke-opacity="0.65" stroke-width="1.4" />'
            . '<circle cx="%.2f" cy="%.2f" r="2" fill="#5DD7E8" fill-opacity="0.85" />',
            $x,
            $y,
            $x,
            $y,
            $x,
            $y,
        );
    }
@endphp

<div class="relative">
    {{-- The drawing sheet: crop marks, dial, caption. --}}
    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-ink-900/40 p-5 sm:p-7">
        <span class="pointer-events-none absolute top-0 start-0 size-6 border-s-2 border-t-2 border-brand-300/45" aria-hidden="true"></span>
        <span class="pointer-events-none absolute top-0 end-0 size-6 border-e-2 border-t-2 border-brand-300/45" aria-hidden="true"></span>
        <span class="pointer-events-none absolute bottom-0 start-0 size-6 border-s-2 border-b-2 border-brand-300/45" aria-hidden="true"></span>
        <span class="pointer-events-none absolute bottom-0 end-0 size-6 border-e-2 border-b-2 border-brand-300/45" aria-hidden="true"></span>

        <div class="flex items-center justify-between gap-3">
            <span class="text-[0.7rem] font-semibold tracking-tight text-ink-300">نمای فنی رشته‌های هنرستان</span>
            <span class="inline-flex items-center gap-1.5 rounded-md border border-white/10 px-2 py-1 text-[0.65rem] text-ink-300">
                <span class="size-1.5 rounded-full bg-accent-400"></span>
                <span>مرکز فعال</span>
            </span>
        </div>

        <svg viewBox="0 0 360 360" class="mx-auto mt-2 w-full max-w-[21rem]" role="img"
             aria-label="نمودار فنی شبکه و مکاترونیک هنرستان">
            {{-- Slowly rotating outer dial. Barely perceptible, alive. --}}
            <g class="motion-reduce:animate-none"
               style="transform-origin: 180px 180px; transform-box: view-box; animation: spin 160s linear infinite;">
                {!! $ticks !!}
            </g>

            <circle cx="180" cy="180" r="132" fill="none" stroke="#5DD7E8" stroke-opacity="0.24" stroke-width="1" stroke-dasharray="2 10" />
            <circle cx="180" cy="180" r="120" fill="none" stroke="#5DD7E8" stroke-opacity="0.12" stroke-width="1" />

            {!! $spokes !!}
            {!! $dots !!}

            {{-- The mechatronics chip at the centre of the network. --}}
            <g transform="translate(180 180) scale(2.3) translate(-12 -12)" fill="none" stroke="#E3EEF3"
               stroke-opacity="0.92" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"
               vector-effect="non-scaling-stroke">
                <rect x="6.5" y="6.5" width="11" height="11" rx="2" />
                <rect x="9.8" y="9.8" width="4.4" height="4.4" rx="1" />
                <path d="M9.5 3v3.5M14.5 3v3.5M9.5 17.5V21M14.5 17.5V21M3 9.5h3.5M3 14.5h3.5M17.5 9.5H21M17.5 14.5H21" />
            </g>

            {!! $nodeDots !!}
        </svg>

        <div class="mt-1 flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-4">
            <span class="text-xs leading-6 text-ink-200">شبکه و نرم‌افزار رایانه · مکاترونیک</span>

            <span class="inline-flex items-center gap-2 rounded-lg border border-accent-500/30 bg-accent-500/10 px-2.5 py-1.5 text-xs font-semibold text-accent-300">
                <x-icon name="trophy" class="size-4 shrink-0" />
                <span>رتبه اول کشوری · <bdi>۱۴۰۳</bdi></span>
            </span>
        </div>
    </div>
</div>
