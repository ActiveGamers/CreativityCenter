/**
 * Toasts — short, dismissible Persian notifications.
 *
 * Public API (attached to `window`):
 *   Toast.success(message)
 *   Toast.error(message)
 *   Toast.info(message)
 *   Toast.show(message, { type, timeout })
 *
 * Messages are rendered verbatim, so they must always be written in Persian.
 */

const CONTAINER_ID = 'toast-container';
const DEFAULT_TIMEOUT = 5200;
const MAX_VISIBLE = 4;

const ICONS = {
    success: '<path d="m5 13 4 4L19 7"/>',
    error: '<path d="M12 8v5"/><path d="M12 16.5h.01"/><circle cx="12" cy="12" r="9"/>',
    info: '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 7.5h.01"/>',
};

const TONES = {
    success: 'border-emerald-500/35 bg-emerald-50 text-emerald-900 dark:bg-emerald-950/70 dark:text-emerald-100',
    error: 'border-red-500/35 bg-red-50 text-red-900 dark:bg-red-950/70 dark:text-red-100',
    info: 'border-brand-500/35 bg-brand-50 text-brand-900 dark:bg-brand-950/70 dark:text-brand-100',
};

const ICON_TONES = {
    success: 'text-emerald-600 dark:text-emerald-400',
    error: 'text-red-600 dark:text-red-400',
    info: 'text-brand-600 dark:text-brand-300',
};

/** Return the toast container, creating it when the layout is not present. */
function container() {
    let element = document.getElementById(CONTAINER_ID);

    if (!element) {
        element = document.createElement('div');
        element.id = CONTAINER_ID;
        element.setAttribute('aria-live', 'polite');
        element.setAttribute('aria-atomic', 'false');
        element.className =
            'pointer-events-none fixed bottom-4 end-4 z-[100] flex w-[calc(100%_-_2rem)] max-w-sm flex-col gap-3';
        document.body.appendChild(element);
    }

    return element;
}

/**
 * Show a toast.
 *
 * @param {string} message Persian message.
 * @param {{ type?: 'success'|'error'|'info', timeout?: number }} [options]
 * @returns {HTMLElement} The toast element.
 */
export function show(message, options = {}) {
    const type = ['success', 'error', 'info'].includes(options.type) ? options.type : 'info';
    const timeout = Number.isFinite(options.timeout) ? options.timeout : DEFAULT_TIMEOUT;

    const host = container();

    // Never let the stack grow past a comfortable height.
    while (host.children.length >= MAX_VISIBLE) {
        host.firstElementChild?.remove();
    }

    const toast = document.createElement('div');
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
    toast.setAttribute('dir', 'rtl');
    toast.className = [
        'pointer-events-auto flex items-start gap-3 rounded-xl border p-4 shadow-[var(--shadow-pop)]',
        'toast-enter',
        TONES[type],
    ].join(' ');

    toast.innerHTML = `
        <span class="mt-0.5 shrink-0 ${ICON_TONES[type]}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                 stroke-linejoin="round" class="size-5" aria-hidden="true">${ICONS[type]}</svg>
        </span>
        <p class="flex-1 text-sm leading-7">${escapeHtml(message)}</p>
        <button type="button" data-toast-dismiss
                class="-me-1 -mt-1 shrink-0 rounded-md p-1 text-current/60 transition-colors hover:bg-black/5 hover:text-current focus-visible:outline-2 dark:hover:bg-white/10"
                aria-label="بستن پیام">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" class="size-4" aria-hidden="true">
                <path d="m6 6 12 12"/><path d="m18 6-12 12"/>
            </svg>
        </button>
    `;

    host.appendChild(toast);

    const dismiss = () => remove(toast);

    toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', dismiss);

    if (timeout > 0) {
        window.setTimeout(dismiss, timeout);
    }

    return toast;
}

/** Fade a toast out and remove it from the DOM. */
function remove(toast) {
    if (!toast.isConnected || toast.dataset.leaving === 'true') {
        return;
    }

    toast.dataset.leaving = 'true';
    toast.classList.remove('toast-enter');
    toast.classList.add('toast-leave');

    window.setTimeout(() => toast.remove(), 220);
}

/** Escape text before it is injected into the toast markup. */
function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    })[character]);
}

export const Toast = {
    success(message, options = {}) {
        return show(message, { ...options, type: 'success' });
    },
    error(message, options = {}) {
        return show(message, { ...options, type: 'error' });
    },
    info(message, options = {}) {
        return show(message, { ...options, type: 'info' });
    },
    show(message, options = {}) {
        return show(message, options);
    },
};

window.Toast = Toast;
