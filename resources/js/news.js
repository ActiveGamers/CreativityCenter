/**
 * News archive — progressive enhancement for the article list.
 *
 * Markup contract:
 *   <div data-news-list>                          the grid of article cards
 *   <div data-news-item data-category="…">        a single article
 *   <div data-news-next-page="3">                 marker rendered by the
 *                                                  posts partial (hidden)
 *   <div data-news-skeleton>                      placeholders while loading
 *   <div data-news-pagination>                    server-rendered pagination
 *   <div data-news-more-wrapper>                  wrapper of the load button
 *   <button data-news-more data-url data-next-page>
 *   <button data-news-filter="…">                 category filter
 *
 * Without JavaScript the numbered pagination is the only navigation, every
 * article stays visible and the category filter is simply absent.
 */

import { Toast } from './toast.js';

/* ---------------------------------------------------------------------------
 * Fixed wordings (AGENTS.md §10) — keep these strings exactly as written.
 * ------------------------------------------------------------------------- */
const TEXT = {
    network: 'اتصال به سرور ناموفق بود. اتصال اینترنت خود را بررسی کنید و از خاموش بودن VPN مطمئن شوید.',
    failed: 'نگهداری بیشتر اخبار ممکن نشد. لطفاً چند لحظه بعد دوباره تلاش کنید.',
};

/**
 * The category filter. It only hides or shows the cards that are already in
 * the page; the content itself always comes from the server.
 *
 * @param {ParentNode} root
 * @returns {{ apply: Function, sync: Function, current: Function }|null}
 */
function createFilter(root) {
    const buttons = Array.from(root.querySelectorAll('[data-news-filter]'));

    if (!buttons.length) {
        return null;
    }

    let active = 'all';

    const items = () => Array.from(root.querySelectorAll('[data-news-item]'));

    const apply = (value) => {
        active = value || 'all';

        items().forEach((item) => {
            item.hidden = active !== 'all' && item.dataset.category !== active;
        });

        buttons.forEach((button) => {
            const isActive = button.dataset.newsFilter === active;

            button.dataset.active = isActive ? 'true' : 'false';
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    const sync = () => {
        const known = new Set(buttons.map((button) => button.dataset.newsFilter));
        const template = buttons[0];
        const container = template.parentElement;

        if (!container) {
            return;
        }

        items().forEach((item) => {
            const category = item.dataset.category;

            if (!category || known.has(category)) {
                return;
            }

            known.add(category);

            const button = document.createElement('button');

            button.type = 'button';
            button.dataset.newsFilter = category;
            button.dataset.active = 'false';
            button.setAttribute('aria-pressed', 'false');
            button.className = template.className;
            button.textContent = category;
            button.addEventListener('click', () => apply(category));

            buttons.push(button);
            container.appendChild(button);
        });
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => apply(button.dataset.newsFilter || 'all'));
    });

    apply('all');

    return {
        apply,
        sync,
        current: () => active,
    };
}

/**
 * The "load more" button. It fetches the next page from the `news.more`
 * endpoint and injects the returned markup into the list.
 *
 * @param {ParentNode} root
 * @param {{ filter: object|null }} context
 */
function initLoadMore(root, context) {
    const list = root.querySelector('[data-news-list]');
    const button = root.querySelector('[data-news-more]');
    const wrapper = root.querySelector('[data-news-more-wrapper]');
    const skeleton = root.querySelector('[data-news-skeleton]');
    const pagination = root.querySelector('[data-news-pagination]');

    if (!list || !button) {
        return null;
    }

    const baseUrl = button.dataset.url || '';

    let nextPage = Number(button.dataset.nextPage || 2);
    let loading = false;

    const showSkeleton = (visible) => {
        if (!skeleton) {
            return;
        }

        skeleton.classList.toggle('hidden', !visible);
    };

    const showButton = (visible) => {
        if (wrapper) {
            wrapper.classList.toggle('hidden', !visible);
            return;
        }

        button.classList.toggle('hidden', !visible);
    };

    const showPagination = (visible) => {
        if (pagination) {
            pagination.classList.toggle('hidden', !visible);
        }
    };

    /* The numbered pagination is the fallback for visitors without
       JavaScript; once the button works it only gets in the way. */
    showPagination(false);
    showButton(true);

    button.addEventListener('click', async () => {
        if (loading) {
            return;
        }

        loading = true;
        button.disabled = true;
        button.dataset.loading = 'true';
        showSkeleton(true);

        try {
            const url = new URL(baseUrl, window.location.origin);

            url.searchParams.set('page', String(nextPage));

            let response;

            try {
                response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        Accept: 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
            } catch (error) {
                Toast.error(TEXT.network);
                showPagination(true);
                return;
            }

            if (!response.ok) {
                Toast.error(TEXT.failed);
                showPagination(true);
                return;
            }

            const html = await response.text();

            /* The marker of the previous page is dropped before the new
               markup arrives, so only the marker of the fresh page is read. */
            list.querySelectorAll('[data-news-next-page]').forEach((marker) => {
                marker.remove();
            });

            list.insertAdjacentHTML('beforeend', html);

            /* The partial marks the next page; without a marker the last
               page has been reached and the button retires itself. */
            const marker = list.querySelector('[data-news-next-page]');

            if (marker) {
                nextPage = Number(marker.dataset.newsNextPage || nextPage + 1);
                button.dataset.nextPage = String(nextPage);
            } else {
                showButton(false);
            }

            context.filter?.sync();
            context.filter?.apply(context.filter.current());
        } finally {
            loading = false;
            button.disabled = false;
            delete button.dataset.loading;
            showSkeleton(false);
        }
    });

    return {
        nextPage: () => nextPage,
    };
}

/**
 * Initialise the news page enhancements.
 *
 * @param {ParentNode} [root]
 */
export function initNews(root = document) {
    if (!root.querySelector('[data-news-list]')) {
        return;
    }

    const filter = createFilter(root);
    const context = { filter };

    initLoadMore(root, context);
}
