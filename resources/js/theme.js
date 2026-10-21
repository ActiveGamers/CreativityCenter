/**
 * Theme controller — light / dark mode with a persisted visitor choice.
 *
 * Resolution order:
 *   1. `localStorage.theme` ('light' | 'dark') — the explicit visitor choice.
 *   2. `prefers-color-scheme` — the operating system preference.
 *
 * The same logic runs inline in <head> (see layouts/app) so the page never
 * flashes the wrong palette; this module keeps the DOM in sync afterwards
 * and exposes `window.Theme`.
 */

const STORAGE_KEY = 'theme';
const THEMES = ['light', 'dark'];

const root = document.documentElement;

/** Read the persisted choice, tolerating blocked storage (private mode). */
function readStoredTheme() {
    try {
        const stored = window.localStorage.getItem(STORAGE_KEY);
        return THEMES.includes(stored) ? stored : null;
    } catch (error) {
        return null;
    }
}

/** Persist the visitor choice; failures are non-fatal. */
function persistTheme(theme) {
    try {
        window.localStorage.setItem(STORAGE_KEY, theme);
    } catch (error) {
        /* Storage unavailable — the choice still applies for this page view. */
    }
}

/** The operating system preference. */
function systemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

/** The theme that should be active right now. */
export function resolveTheme() {
    return readStoredTheme() ?? systemTheme();
}

/**
 * Apply a theme to the document and announce it.
 * @param {'light'|'dark'} theme
 */
export function applyTheme(theme) {
    const next = THEMES.includes(theme) ? theme : 'light';

    root.classList.toggle('dark', next === 'dark');
    root.style.colorScheme = next;
    root.dataset.theme = next;

    document.dispatchEvent(new CustomEvent('themechange', { detail: { theme: next } }));

    return next;
}

/** The theme currently applied to the document. */
export function getTheme() {
    return root.classList.contains('dark') ? 'dark' : 'light';
}

/** Apply and remember a theme. */
export function setTheme(theme) {
    const next = applyTheme(theme);
    persistTheme(next);
    return next;
}

/** Flip between light and dark. */
export function toggleTheme() {
    return setTheme(getTheme() === 'dark' ? 'light' : 'dark');
}

/**
 * Wire up the toggle buttons and keep following the OS while the visitor
 * has not made an explicit choice.
 */
export function initTheme() {
    applyTheme(resolveTheme());

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-theme-toggle]');

        if (!trigger) {
            return;
        }

        event.preventDefault();
        toggleTheme();
    });

    const media = window.matchMedia('(prefers-color-scheme: dark)');

    media.addEventListener('change', (event) => {
        if (readStoredTheme() === null) {
            applyTheme(event.matches ? 'dark' : 'light');
        }
    });
}

window.Theme = {
    toggle: toggleTheme,
    get: getTheme,
    set: setTheme,
    resolve: resolveTheme,
};
