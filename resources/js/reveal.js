/**
 * Scroll reveal — a quiet fade/slide-in for elements marked with
 * `[data-reveal]`.
 *
 * Usage in Blade:
 *   <div data-reveal="start" style="--reveal-delay: 120ms">…</div>
 *
 * The hidden state only exists once this module has initialised
 * successfully (it adds the `reveal-ready` class to the <html> element at
 * the very end of the initialisation), so a blocked bundle, a failed
 * bundle or a failed initialisation leaves the content visible.
 * `prefers-reduced-motion` disables the effect entirely.
 */

const SELECTOR = '[data-reveal]';

/** The class that switches the hidden state of the reveal elements on. */
const READY_CLASS = 'reveal-ready';

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

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-revealed'));

        document.documentElement.classList.add(READY_CLASS);

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

    /* The hidden state is switched on only now, when everything is in place:
       the observer is set up, the elements are watched and nothing can throw
       any more. The class is added before the first paint of the reveal
       elements, so they never flash into view before they fade in — and if
       anything above had failed, the content would simply stay visible. */
    document.documentElement.classList.add(READY_CLASS);
}
