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

function boot() {
    initTheme();
    initNavigation();
    initForms();
    initReveal();
    initCounters();
    initGallery();
    initNews();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}
