/**
 * Gallery — lightbox viewer and optional category filters.
 *
 * Markup contract:
 *   <button data-lightbox data-caption="…">  trigger (the image may be a
 *                                            child <img>; its src is used)
 *   <button data-filter="workshops">          filter button
 *   <figure data-filter-item data-category="workshops">…</figure>
 *
 * The lightbox overlay is created on demand and reused for every item.
 */

/** Collect the items that can be opened in the lightbox. */
function collectItems(root) {
    return Array.from(root.querySelectorAll('[data-lightbox]')).map((trigger) => {
        const image = trigger.querySelector('img');
        const source = trigger.getAttribute('data-lightbox') || image?.getAttribute('src') || '';
        const caption =
            trigger.getAttribute('data-caption') ||
            trigger.querySelector('figcaption')?.textContent?.trim() ||
            image?.getAttribute('alt') ||
            '';

        return { trigger, source, caption: caption.trim() };
    });
}

/** The focusable elements of the overlay, in visual order. */
const FOCUSABLE = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';

/**
 * Make the page behind the overlay inert while the lightbox is open, so the
 * keyboard can never leave the dialog. The state is restored on close.
 *
 * @param {HTMLElement} overlay
 * @param {boolean} inert
 */
function setBackgroundInert(overlay, inert) {
    Array.from(document.body.children).forEach((element) => {
        if (element === overlay || !element.matches('header, main, footer')) {
            return;
        }

        if (inert) {
            if (!element.hasAttribute('inert')) {
                element.setAttribute('inert', '');
                element.dataset.lightboxInert = 'true';
            }

            return;
        }

        if (element.dataset.lightboxInert === 'true') {
            element.removeAttribute('inert');
            delete element.dataset.lightboxInert;
        }
    });
}

/**
 * Keep the Tab key inside the overlay: the focus cycles through the close,
 * previous and next buttons instead of leaving the lightbox.
 *
 * @param {KeyboardEvent} event
 * @param {HTMLElement} overlay
 */
function trapFocus(event, overlay) {
    if (event.key !== 'Tab') {
        return;
    }

    const focusable = Array.from(overlay.querySelectorAll(FOCUSABLE)).filter(
        (element) => !element.closest('[hidden]') && element.getAttribute('aria-hidden') !== 'true',
    );

    if (!focusable.length) {
        return;
    }

    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    const active = document.activeElement;

    if (!overlay.contains(active)) {
        event.preventDefault();
        (event.shiftKey ? last : first).focus();

        return;
    }

    if (event.shiftKey && active === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && active === last) {
        event.preventDefault();
        first.focus();
    }
}

/** Build the lightbox overlay once. */
function createOverlay() {
    const overlay = document.createElement('div');

    overlay.dataset.lightboxOverlay = '';
    overlay.className = 'fixed inset-0 z-[120] hidden';
    overlay.setAttribute('dir', 'rtl');
    overlay.innerHTML = `
        <div data-lightbox-backdrop class="absolute inset-0 bg-ink-950/90"></div>
        <div role="dialog" aria-modal="true" aria-label="نمایش تصویر"
             class="relative flex h-full flex-col items-center justify-center gap-4 p-4 sm:p-8">
            <button type="button" data-lightbox-close
                    class="absolute end-4 top-4 grid size-11 place-items-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:bg-white/15"
                    aria-label="بستن نمایشگر تصویر">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="m6 6 12 12"/><path d="m18 6-12 12"/>
                </svg>
            </button>
            <button type="button" data-lightbox-prev
                    class="absolute start-2 top-1/2 grid size-11 -translate-y-1/2 place-items-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:bg-white/15 sm:start-4"
                    aria-label="تصویر قبلی">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="m15 6-6 6 6 6"/>
                </svg>
            </button>
            <button type="button" data-lightbox-next
                    class="absolute end-2 top-1/2 grid size-11 -translate-y-1/2 place-items-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:bg-white/15 sm:end-4"
                    aria-label="تصویر بعدی">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="m9 6 6 6-6 6"/>
                </svg>
            </button>
            <img data-lightbox-image src="" alt=""
                 class="max-h-[72vh] w-auto max-w-full rounded-xl object-contain shadow-2xl" />
            <p data-lightbox-caption class="max-w-2xl text-center text-sm leading-7 text-white/80"></p>
            <span data-lightbox-counter class="text-xs tabular-nums text-white/50"></span>
        </div>
    `;

    document.body.appendChild(overlay);

    return overlay;
}

/**
 * Initialise the lightbox and the optional filters.
 *
 * @param {ParentNode} [root]
 */
export function initGallery(root = document) {
    const items = collectItems(root);

    if (items.length) {
        const overlay = createOverlay();
        const image = overlay.querySelector('[data-lightbox-image]');
        const caption = overlay.querySelector('[data-lightbox-caption]');
        const counter = overlay.querySelector('[data-lightbox-counter]');
        const backdrop = overlay.querySelector('[data-lightbox-backdrop]');

        let index = 0;
        let lastFocused = null;

        /* Only the items the current filter leaves visible take part in the
           sequence: the arrows and the counter must not walk over the images
           the visitor has filtered out. */
        const visibleItems = () => items.filter((item) => {
            const wrapper = item.trigger.closest('[data-filter-item]');

            return !wrapper || !wrapper.hidden;
        });

        const render = () => {
            const visible = visibleItems();
            const item = visible[index] ?? visible[0];

            if (!item) {
                return;
            }

            image.src = item.source;
            image.alt = item.caption;
            caption.textContent = item.caption;
            caption.classList.toggle('hidden', !item.caption);
            counter.textContent = `${visible.indexOf(item) + 1} / ${visible.length}`;
        };

        const open = (position) => {
            const visible = visibleItems();
            const target = items[position];

            index = Math.max(0, visible.indexOf(target));
            lastFocused = document.activeElement;
            render();
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setBackgroundInert(overlay, true);
            overlay.querySelector('[data-lightbox-close]')?.focus();
        };

        const close = () => {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
            setBackgroundInert(overlay, false);
            lastFocused?.focus?.();
        };

        const step = (direction) => {
            const visible = visibleItems();

            if (!visible.length) {
                return;
            }

            index = (index + direction + visible.length) % visible.length;
            render();
        };

        items.forEach((item, position) => {
            item.trigger.addEventListener('click', (event) => {
                event.preventDefault();
                open(position);
            });
        });

        overlay.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
        backdrop?.addEventListener('click', close);
        overlay.querySelector('[data-lightbox-prev]')?.addEventListener('click', () => step(-1));
        overlay.querySelector('[data-lightbox-next]')?.addEventListener('click', () => step(1));

        document.addEventListener('keydown', (event) => {
            if (overlay.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                close();
            } else if (event.key === 'ArrowLeft') {
                step(1);
            } else if (event.key === 'ArrowRight') {
                step(-1);
            } else {
                trapFocus(event, overlay);
            }
        });
    }

    initFilters(root);
}

/** Wire the optional category filters. */
function initFilters(root) {
    const buttons = Array.from(root.querySelectorAll('[data-filter]'));

    if (!buttons.length) {
        return;
    }

    const entries = Array.from(root.querySelectorAll('[data-filter-item]'));

    const apply = (value) => {
        entries.forEach((entry) => {
            const category = entry.dataset.category ?? '';
            const visible = value === 'all' || category === value;

            entry.hidden = !visible;
        });

        buttons.forEach((button) => {
            const active = button.dataset.filter === value;

            button.dataset.active = active ? 'true' : 'false';
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            apply(button.dataset.filter || 'all');
        });
    });

    apply('all');
}
