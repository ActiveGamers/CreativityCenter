/**
 * Scroll reveal — a quiet fade/slide-in for elements marked with
 * `[data-reveal]`.
 *
 * Usage in Blade:
 *   <div data-reveal="start" style="--reveal-delay: 120ms">…</div>
 *
 * The hidden state only exists once this module has initialised (it adds the
 * `reveal-ready` class to the <html> element), so a blocked or failed bundle
 * leaves the content visible. `prefers-reduced-motion` disables the effect
 * entirely.
 */

const SELECTOR = '[data-reveal]';

/** The class that switches the hidden state of the reveal elements on. */
const READY_CLASS = 'reveal-ready';

/* The class is set as soon as the bundle runs, before the browser paints the
   page: the reveal elements are never shown and hidden again. */
document.documentElement.classList.add(READY_CLASS);

/**
 * Reveal every `[data-reveal]` element inside the given root.
 *
 * @param {ParentNode} [root]
 */
export function initReveal(root = document) {
    const items = Array.from(root.querySelectorAll(SELECTOR));

    if (!items.length) {
        return;
    }

    /* The class is added before the first paint of the reveal elements, so
       they never flash into view before they fade in. */
    document.documentElement.classList.add(READY_CLASS);

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -8% 0px',
        },
    );

    items.forEach((item) => observer.observe(item));
}
