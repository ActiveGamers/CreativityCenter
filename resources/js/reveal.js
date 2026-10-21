/**
 * Scroll reveal — a quiet fade/slide-in for elements marked with
 * `[data-reveal]`.
 *
 * Usage in Blade:
 *   <div data-reveal data-reveal="start" style="--reveal-delay: 120ms">…</div>
 *
 * The hidden state only exists once JavaScript has booted (see the `.js`
 * class in app.css), so content is never invisible to visitors without JS.
 * `prefers-reduced-motion` disables the effect entirely.
 */

const SELECTOR = '[data-reveal]';

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
