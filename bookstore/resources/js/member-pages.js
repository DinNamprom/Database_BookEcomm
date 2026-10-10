if (document.body.classList.contains('member-page')) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const status = (element, message, failed = false) => {
        element.textContent = message;
        element.dataset.failed = String(failed);
    };
    const send = async (url, method, data) => {
        const response = await fetch(url, {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            credentials: 'same-origin',
            body: data ? JSON.stringify(data) : undefined,
        });
        const result = response.status === 204 ? {} : await response.json().catch(() => ({}));
        if (!response.ok) {
            const message = response.status === 419 || response.status === 401
                ? 'Your session has expired. Refresh this page and sign in again.'
                : response.status === 422 ? 'Please check the highlighted fields.' : 'Could not save your changes. Please try again.';
            throw Object.assign(new Error(message), { errors: result.errors || {} });
        }
        return result;
    };
    const addressForm = document.querySelector('#address-editor');
    const editAddress = document.querySelector('[data-edit-address]');
    const toggleAddress = (open) => {
        addressForm.hidden = !open;
        editAddress.setAttribute('aria-expanded', String(open));
        if (open) addressForm.querySelector('textarea').focus();
        else editAddress.focus();
    };
    editAddress?.addEventListener('click', () => toggleAddress(addressForm.hidden));
    document.querySelector('[data-cancel-address]')?.addEventListener('click', () => {
        addressForm.reset();
        addressForm.querySelectorAll('[data-error]').forEach((node) => { node.textContent = ''; });
        addressForm.querySelector('textarea').removeAttribute('aria-invalid');
        status(addressForm.querySelector('.form-status'), '');
        toggleAddress(false);
    });
    document.querySelectorAll('[data-member-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('[type="submit"]');
            const message = form.querySelector('.form-status');
            form.querySelectorAll('[data-error]').forEach((node) => { node.textContent = ''; });
            form.querySelectorAll('[aria-invalid]').forEach((node) => node.removeAttribute('aria-invalid'));
            const data = Object.fromEntries([...new FormData(form)].filter(([key]) => !key.startsWith('_')));
            button.disabled = true;
            form.setAttribute('aria-busy', 'true');
            status(message, 'Saving…');
            try {
                const member = await send(form.action, 'PATCH', data);
                if (form.dataset.memberForm === 'profile') {
                    document.querySelectorAll('[data-member-name]').forEach((node) => { node.textContent = member.name; });
                    document.querySelector('[data-member-greeting]').textContent = member.first_name || member.name;
                } else {
                    document.querySelector('[data-delivery-address]').textContent = member.address;
                }
                form.querySelectorAll('input:not([type="hidden"]), textarea').forEach((input) => { input.defaultValue = input.value; });
                status(message, 'Changes saved.');
            } catch (error) {
                Object.entries(error.errors || {}).forEach(([key, messages]) => {
                    const input = form.elements.namedItem(key);
                    if (input) input.setAttribute('aria-invalid', 'true');
                    const target = [...form.querySelectorAll('[data-error]')].find((node) => node.dataset.error === key);
                    if (target) target.textContent = messages[0];
                });
                status(message, error.message || 'Unable to connect. Please try again.', true);
                form.querySelector('[aria-invalid="true"]')?.focus();
            } finally {
                button.disabled = false;
                form.removeAttribute('aria-busy');
            }
        });
    });
    document.querySelectorAll('[data-remove-favorite]').forEach((button) => {
        button.addEventListener('click', async () => {
            const message = document.querySelector('.favorites-status');
            button.disabled = true;
            status(message, 'Removing favorite…');
            try {
                await send(button.dataset.removeFavorite, 'DELETE');
                const section = button.closest('[data-favorite-section]');
                const next = button.closest('[data-favorite-row]').nextElementSibling?.querySelector('button');
                button.closest('[data-favorite-row]').remove();
                const count = section.querySelectorAll('[data-favorite-row]').length;
                section.querySelector('[data-favorite-count]').textContent = count;
                section.querySelector('[data-empty-state]').hidden = count !== 0;
                status(message, 'Favorite removed.');
                const focus = next || section.querySelector('h1');
                focus.setAttribute('tabindex', '-1');
                focus.focus();
            } catch (error) {
                status(message, error.message || 'Unable to connect. Please try again.', true);
                button.disabled = false;
            }
        });
    });
}
