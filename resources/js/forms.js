/**
 * Progressive form enhancement — client-side validation, AJAX submission and
 * Persian inline error rendering.
 *
 * Public API (attached to `window`):
 *   AppForms.attach(formElement, { onSuccess, onError, redirect })
 *
 * Server-side validation stays the real guard; this module only shortens the
 * feedback loop and never replaces it. Every failure path ends in a Persian
 * toast — `alert()` is never used.
 */

import { Toast } from './toast.js';

/* ---------------------------------------------------------------------------
 * Fixed wordings (AGENTS.md §10) — keep these strings exactly as written.
 * ------------------------------------------------------------------------- */
const TEXT = {
    expiredSession: 'خطایی در پردازش رخ داد؛ لطفاً لحظاتی دیگر دوباره تلاش کنید یا صفحه را تازه‌سازی کنید.',
    loggedOut: 'اگر از حساب خارج شده‌اید، دوباره وارد شوید.',
    network: 'اتصال به سرور ناموفق بود. اتصال اینترنت خود را بررسی کنید و از خاموش بودن VPN مطمئن شوید.',
    requestFailed: 'انجام درخواست ممکن نشد. لطفاً چند لحظه بعد دوباره تلاش کنید.',
    invalid: 'اطلاعات وارد‌شده معتبر نیست. لطفاً موارد مشخص‌شده را اصلاح کنید.',
    success: 'درخواست شما با موفقیت ثبت شد.',
    postProcessFailed: 'درخواست شما ثبت شد، اما نمایش نتیجه کامل نشد. صفحه را تازه‌سازی کنید.',
    field: {
        required: 'این فیلد الزامی است.',
        email: 'نشانی ایمیل وارد‌شده معتبر نیست.',
        mobile: 'شماره همراه باید ۱۱ رقم و با ۰۹ شروع شود.',
        minlength: 'حداقل {min} نویسه لازم است.',
        maxlength: 'حداکثر {max} نویسه مجاز است.',
        numeric: 'تنها عدد وارد کنید.',
    },
};

const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
const MOBILE_PATTERN = /^09\d{9}$/;

const PERSIAN_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
const ARABIC_DIGITS = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

/** Convert Persian / Arabic digits to Latin digits for validation. */
export function toLatinDigits(value) {
    return String(value ?? '')
        .replace(/[۰-۹]/g, (digit) => String(PERSIAN_DIGITS.indexOf(digit)))
        .replace(/[٠-٩]/g, (digit) => String(ARABIC_DIGITS.indexOf(digit)));
}

/** Does the string contain Persian characters? */
function isPersian(value) {
    return /[؀-ۿ]/.test(String(value ?? ''));
}

/** Read the CSRF token published by the layout. */
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/** The controls of a form that can be validated. */
function fields(form) {
    return Array.from(form.querySelectorAll('input, textarea, select')).filter(
        (field) => field.type !== 'hidden' && field.type !== 'submit' && field.type !== 'button',
    );
}

/** The error slot rendered by <x-form-field /> for a given field name. */
function errorSlot(form, name) {
    return form.querySelector(`[data-error-for="${CSS.escape(name)}"]`);
}

/**
 * Show or clear the inline error of a single field.
 *
 * @param {HTMLFormElement} form
 * @param {string} name
 * @param {string|null} message
 */
export function setFieldError(form, name, message) {
    const slot = errorSlot(form, name);
    const control = form.querySelector(`[name="${CSS.escape(name)}"]`);

    if (slot) {
        slot.textContent = message ?? '';
        slot.classList.toggle('hidden', !message);

        if (message && !slot.id) {
            slot.id = `error-${name}`;
        }
    }

    if (control) {
        if (message) {
            control.setAttribute('aria-invalid', 'true');

            if (slot?.id) {
                control.setAttribute('aria-describedby', slot.id);
            }
        } else {
            control.removeAttribute('aria-invalid');
        }
    }
}

/** Clear every inline error inside the form. */
function clearErrors(form) {
    fields(form).forEach((field) => setFieldError(form, field.name, null));
}

/**
 * Validate one control.
 *
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} field
 * @returns {string|null} Persian error message, or null when valid.
 */
export function validateField(field) {
    const rawValue = field.value ?? '';
    const value = toLatinDigits(rawValue).trim();
    const required = field.hasAttribute('required') || field.dataset.required === 'true';

    /* A checkbox carries a value even when it is unchecked, so the value can
       never tell an empty box from a filled one: only `checked` can. */
    if (field.type === 'checkbox') {
        if (required && !field.checked) {
            return TEXT.field.required;
        }

        return null;
    }

    if (required && value === '') {
        return TEXT.field.required;
    }

    if (value === '') {
        return null;
    }

    const rule = field.dataset.validate ?? (field.type === 'email' ? 'email' : field.type === 'tel' ? 'mobile' : '');

    if (rule === 'none' || rule === 'text') {
        return null;
    }

    if (rule === 'email' && !EMAIL_PATTERN.test(value)) {
        return TEXT.field.email;
    }

    if (rule === 'mobile' && !MOBILE_PATTERN.test(value)) {
        return TEXT.field.mobile;
    }

    if (field.dataset.numeric === 'true' && !/^\d+$/.test(value)) {
        return TEXT.field.numeric;
    }

    const min = Number(field.minLength || 0);

    if (min > 0 && value.length < min) {
        return TEXT.field.minlength.replace('{min}', String(min));
    }

    const max = Number(field.maxLength || 0);

    if (max > 0 && value.length > max) {
        return TEXT.field.maxlength.replace('{max}', String(max));
    }

    return null;
}

/** Validate the whole form; returns false when something is wrong. */
function validateForm(form) {
    clearErrors(form);

    let firstInvalid = null;

    fields(form).forEach((field) => {
        const message = validateField(field);

        if (message) {
            setFieldError(form, field.name, message);
            firstInvalid = firstInvalid ?? field;
        }
    });

    if (firstInvalid) {
        firstInvalid.focus({ preventScroll: true });
        firstInvalid.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }

    return !firstInvalid;
}

/** Parse a response body as JSON without throwing. */
async function readJson(response) {
    try {
        return await response.json();
    } catch (error) {
        return null;
    }
}

/** Does the response tell us the visitor has been logged out? */
function indicatesLogout(response, body) {
    if (response.status === 401) {
        return true;
    }

    if (response.redirected && /login/i.test(response.url)) {
        return true;
    }

    const haystack = `${body?.message ?? ''} ${body?.error ?? ''} ${response.url ?? ''}`.toLowerCase();

    return /login|log\s?out|unauthenticated|not authenticated|وارد شوید|خارج شده/.test(haystack);
}

/** Toggle the loading state of the form's submit controls. */
function setLoading(form, loading) {
    if (loading) {
        form.dataset.loading = 'true';
    } else {
        delete form.dataset.loading;
    }

    form.querySelectorAll('[type="submit"]').forEach((button) => {
        button.disabled = loading;
        button.setAttribute('aria-busy', loading ? 'true' : 'false');
    });
}

/** The progress bar used while a file is uploading. */
function progressBar(form) {
    let bar = form.querySelector('[data-upload-progress]');

    if (!bar) {
        bar = document.createElement('div');
        bar.dataset.uploadProgress = '';
        bar.className = 'mt-4 hidden';
        bar.innerHTML = `
            <div class="mb-2 flex items-center justify-between text-xs text-slate-500 dark:text-ink-200">
                <span>در حال بارگذاری فایل…</span>
                <span data-upload-percent>۰٪</span>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-ink-800">
                <div data-upload-bar class="h-full w-0 rounded-full bg-brand-500 transition-[width] duration-200"></div>
            </div>
        `;
        form.appendChild(bar);
    }

    return bar;
}

/** Update the upload progress bar. */
function updateProgress(form, percent) {
    const bar = progressBar(form);
    const fill = bar.querySelector('[data-upload-bar]');
    const label = bar.querySelector('[data-upload-percent]');
    const value = Math.max(0, Math.min(100, Math.round(percent)));

    bar.classList.remove('hidden');
    fill.style.width = `${value}%`;
    label.textContent = `${toPersianDigits(value)}٪`;
}

/** Hide the upload progress bar. */
function hideProgress(form) {
    const bar = form.querySelector('[data-upload-progress]');

    if (!bar) {
        return;
    }

    bar.classList.add('hidden');

    const fill = bar.querySelector('[data-upload-bar]');

    if (fill) {
        fill.style.width = '0%';
    }
}

/** Render Latin digits as Persian digits (used for the progress label only). */
function toPersianDigits(value) {
    return String(value).replace(/\d/g, (digit) => PERSIAN_DIGITS[Number(digit)]);
}

/**
 * Submit the form.
 *
 * Forms without a file input use `fetch`. Forms with a file input fall back
 * to XMLHttpRequest so the upload progress bar reports real bytes.
 */
function request(form) {
    const method = (form.getAttribute('method') || 'POST').toUpperCase();
    const action = form.getAttribute('action') || window.location.href;
    const data = new FormData(form);

    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken(),
    };

    if (method === 'GET') {
        const params = new URLSearchParams();

        data.forEach((value, key) => params.append(key, value));

        const separator = action.includes('?') ? '&' : '?';

        return fetch(`${action}${separator}${params.toString()}`, {
            method: 'GET',
            headers,
            credentials: 'same-origin',
        }).then((response) => ({ response, body: null }));
    }

    const hasFile = Boolean(form.querySelector('input[type="file"]'));

    if (!hasFile) {
        return fetch(action, {
            method,
            body: data,
            headers,
            credentials: 'same-origin',
        }).then(async (response) => ({ response, body: await readJson(response) }));
    }

    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        xhr.open(method, action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken());
        xhr.withCredentials = true;

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable && event.total > 0) {
                updateProgress(form, (event.loaded / event.total) * 100);
            }
        });

        xhr.addEventListener('load', () => {
            let body = null;

            try {
                body = JSON.parse(xhr.responseText);
            } catch (error) {
                body = null;
            }

            resolve({ response: { status: xhr.status, url: xhr.responseURL, redirected: false }, body });
        });

        xhr.addEventListener('error', () => reject(new Error('network')));
        xhr.addEventListener('abort', () => reject(new Error('abort')));

        xhr.send(data);
    });
}

/**
 * Handle a server response: 419 / 401 / 422 / success / everything else.
 */
async function handleResponse({ response, body }, form, options) {
    if (response.status === 419 || response.status === 401) {
        let message = TEXT.expiredSession;

        if (indicatesLogout(response, body)) {
            message = `${message} ${TEXT.loggedOut}`;
        }

        Toast.error(message);
        options.onError?.(body, response);
        return;
    }

    if (response.status === 422 && body?.errors) {
        let firstField = null;

        Object.entries(body.errors).forEach(([name, messages]) => {
            const message = Array.isArray(messages) ? messages[0] : messages;
            const text = isPersian(message) ? message : TEXT.invalid;

            setFieldError(form, name, text);

            if (!firstField) {
                firstField = form.querySelector(`[name="${CSS.escape(name)}"]`);
            }
        });

        Toast.error(TEXT.invalid);
        firstField?.focus({ preventScroll: true });
        options.onError?.(body, response);
        return;
    }

    if (response.status >= 200 && response.status < 300) {
        const message = body?.message && isPersian(body.message) ? body.message : TEXT.success;

        Toast.success(message);
        options.onSuccess?.(body, form);

        const redirect = options.redirect ?? form.dataset.redirect ?? body?.redirect;

        if (redirect) {
            window.location.href = redirect;
        }

        return;
    }

    Toast.error(TEXT.requestFailed);
    options.onError?.(body, response);
}

/**
 * Attach AJAX behaviour to a form.
 *
 * @param {HTMLFormElement} form
 * @param {{ onSuccess?: Function, onError?: Function, redirect?: string }} [options]
 */
export function attach(form, options = {}) {
    if (!form || form.dataset.appFormsAttached === 'true') {
        return null;
    }

    form.dataset.appFormsAttached = 'true';

    const onSubmit = async (event) => {
        event.preventDefault();

        if (form.dataset.loading === 'true') {
            return;
        }

        if (!validateForm(form)) {
            Toast.error(TEXT.invalid);
            return;
        }

        setLoading(form, true);

        let result;

        /* Only the request itself may report a connection failure: once the
           server answered, the request has been accepted and a failure in the
           success handling must not be dressed up as a network problem. */
        try {
            result = await request(form);
        } catch (error) {
            Toast.error(TEXT.network);
            options.onError?.(null, null);
            setLoading(form, false);
            hideProgress(form);

            return;
        }

        try {
            await handleResponse(result, form, options);
        } catch (error) {
            /* The request succeeded; the failure comes from the success
               handler or from the redirect. The visitor still gets a Persian
               message — never the connection wording. */
            console.error('[forms] the success handling failed', error);
            Toast.error(TEXT.postProcessFailed);
        } finally {
            setLoading(form, false);
            hideProgress(form);
        }
    };

    form.addEventListener('submit', onSubmit);

    // Clear a field error as soon as the visitor edits the field again.
    form.addEventListener('input', (event) => {
        const field = event.target;

        if (!field?.name) {
            return;
        }

        /* A server-rendered error is cleared as well: it carries no
           `aria-invalid` when the markup was produced without the
           enhancement, so the visible message is the second signal. */
        const invalid = field.getAttribute('aria-invalid') === 'true';
        const slot = errorSlot(form, field.name);
        const serverError = Boolean(slot && slot.textContent.trim() !== '');

        if (invalid || serverError) {
            setFieldError(form, field.name, null);
        }
    });

    return {
        detach() {
            form.removeEventListener('submit', onSubmit);
            delete form.dataset.appFormsAttached;
        },
    };
}

export const AppForms = {
    attach,
    validateField,
    setFieldError,
    toLatinDigits,
};

window.AppForms = AppForms;
