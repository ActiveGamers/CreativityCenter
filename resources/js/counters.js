/**
 * Counters — animate numeric statistics when they scroll into view.
 *
 * Usage in Blade:
 *   <span data-counter="1250" data-counter-duration="1600">0</span>
 *
 * Digits stay Latin: these are technical figures, not Persian prose.
 */

const SELECTOR = '[data-counter]';
const DEFAULT_DURATION = 1600;

/** Ease-out exponential — fast start, gentle landing. */
function easeOut(t) {
    return 1 - Math.pow(1 - t, 3);
}

/** Format a number with thin thousands separators. */
function format(value) {
    return new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 0,
    }).format(Math.round(value));
}

/** Run the animation for a single element. */
function animate(element) {
    const target = Number(element.dataset.counter ?? 0);
    const duration = Number(element.dataset.counterDuration ?? DEFAULT_DURATION);
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!Number.isFinite(target) || reducedMotion) {
        element.textContent = format(target);
        return;
    }

    const start = performance.now();

    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);

        element.textContent = format(target * easeOut(progress));

        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            element.textContent = format(target);
        }
    };

    window.requestAnimationFrame(step);
}

/**
 * Observe every `[data-counter]` element inside the given root.
 *
 * @param {ParentNode} [root]
 */
export function initCounters(root = document) {
    const items = Array.from(root.querySelectorAll(SELECTOR));

    if (!items.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        items.forEach(animate);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animate(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.4 },
    );

    items.forEach((item) => observer.observe(item));
}
