/**
 * Front-end entry point.
 *
 * Every module is safe to load on every page: each one looks for the markup
 * it needs and returns immediately when that markup is absent.
 */

import { initTheme } from './theme.js';
import './toast.js';
import { AppForms } from './forms.js';
import { initNavigation } from './navigation.js';
import { initReveal } from './reveal.js';
import { initCounters } from './counters.js';
import { initGallery } from './gallery.js';
import { initNews } from './news.js';

/*
 * Note: `./echo` (Laravel Echo + Reverb) is intentionally NOT imported here.
 * The public website does not broadcast anything, and loading Echo would open a
 * websocket connection and add the broadcaster bundle to every public page.
 * Import it once a feature that actually needs realtime updates lands.
 */

/** Attach AJAX behaviour to the forms that opted in with `data-app-form`. */
function initForms() {
    document.querySelectorAll('form[data-app-form]').forEach((form) => {
        AppForms.attach(form, {
            /* After a successful submission the form is emptied and the
               inline errors are cleared, so the visitor can send another
               request without reloading the page. */
            onSuccess() {
                form.reset();

                form.querySelectorAll('[data-error-for]').forEach((slot) => {
                    const name = slot.getAttribute('data-error-for');

                    if (name) {
                        AppForms.setFieldError(form, name, null);
                    }
                });

                /* The flash message of a previous no-JavaScript submission
                   must not stay on screen next to the success toast. */
                document.querySelectorAll('[data-flash-success]').forEach((banner) => {
                    banner.remove();
                });
            },
        });
    });
}

/**
 * Run one initialiser.
 *
 * A broken feature must never take the whole page down: the initialiser
 * fails, the feature stays disabled and the rest of the page keeps
 * working.
 *
 * @param {() => void} initialiser
 */
function safely(initialiser) {
    try {
        initialiser();
    } catch (error) {
        /* The failure is a developer matter; the page stays usable. */
        console.error(error);
    }
}

function boot() {
    safely(initTheme);

    /* The navigation module sets the `nav-ready` marker itself once the drawer
       is wired, so a drawer that fails to initialise keeps the CSS-only
       fallback navigation instead of hiding it behind a dead trigger. */
    safely(initNavigation);

    safely(initForms);
    safely(initReveal);
    safely(initCounters);
    safely(initGallery);
    safely(initNews);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}
