<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const headers = { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };

    // ----- Profile and address forms -----
    document.querySelectorAll('[data-member-form]').forEach(function (form) {
        const kind = form.dataset.memberForm;
        const status = form.querySelector('.form-status');
        const button = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            form.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
            form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
            status.textContent = '';
            status.removeAttribute('data-failed');
            button.disabled = true;

            try {
                // FormData includes _method=PATCH, so Laravel treats this as a PATCH request
                const res = await fetch(form.action, { method: 'POST', headers, body: new FormData(form) });
                const data = await res.json().catch(() => ({}));

                if (res.status === 422) {
                    Object.entries(data.errors || {}).forEach(function ([field, messages]) {
                        const err = form.querySelector('[data-error="' + field + '"]');
                        const input = form.querySelector('[name="' + field + '"]');
                        if (err) err.textContent = messages[0];
                        if (input) input.setAttribute('aria-invalid', 'true');
                    });
                    status.textContent = 'Please check the highlighted fields.';
                    status.setAttribute('data-failed', 'true');
                    return;
                }

                if (!res.ok) throw new Error();

                status.textContent = data.message || 'Saved.';

                if (kind === 'profile') {
                    const first = form.querySelector('[name="first_name"]').value;
                    const last = form.querySelector('[name="last_name"]').value;
                    document.querySelectorAll('[data-member-greeting]').forEach(el => el.textContent = first);
                    document.querySelectorAll('[data-member-name], .sidebar-name').forEach(el => el.textContent = (first + ' ' + last).trim());
                }

                if (kind === 'address') {
                    document.querySelector('[data-delivery-address]').textContent = data.address || 'No delivery address saved yet.';
                    Object.entries(data.delivery_address || {}).forEach(([name, value]) => {
                        const input = form.elements.namedItem(name);
                        if (input) input.value = input.defaultValue = value ?? '';
                    });
                    form.querySelector('fieldset').disabled = true;
                    form.querySelector('[data-address-actions]').hidden = true;
                    form.querySelector('[data-cancel-address]').hidden = false;
                    const edit = document.querySelector('[data-edit-address]');
                    edit.hidden = false;
                    edit.setAttribute('aria-expanded', 'false');
                }
            } catch (err) {
                status.textContent = 'Something went wrong. Please try again.';
                status.setAttribute('data-failed', 'true');
            } finally {
                button.disabled = false;
            }
        });
    });

    // ----- Edit the prefilled delivery address -----
    const addressForm = document.getElementById('address-editor');
    const editAddress = document.querySelector('[data-edit-address]');
    if (addressForm && editAddress) {
        editAddress.addEventListener('click', function () {
            addressForm.querySelector('fieldset').disabled = false;
            addressForm.querySelector('[data-address-actions]').hidden = false;
            editAddress.hidden = true;
            editAddress.setAttribute('aria-expanded', 'true');
            addressForm.querySelector('input[name="street_address"]')?.focus();
        });
        addressForm.querySelector('[data-cancel-address]')?.addEventListener('click', function () {
            addressForm.reset();
            addressForm.querySelector('fieldset').disabled = true;
            addressForm.querySelector('[data-address-actions]').hidden = true;
            addressForm.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
            addressForm.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
            addressForm.querySelector('.form-status').textContent = '';
            editAddress.hidden = false;
            editAddress.setAttribute('aria-expanded', 'false');
        });
    }

    // ----- Remove a favorite -----
    document.querySelectorAll('[data-remove-favorite]').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            btn.disabled = true;
            try {
                const res = await fetch(btn.dataset.removeFavorite, { method: 'DELETE', headers });
                if (!res.ok) throw new Error();

                const section = btn.closest('[data-favorite-section]');
                btn.closest('[data-favorite-row]').remove();

                const remaining = section.querySelectorAll('[data-favorite-row]').length;
                section.querySelector('[data-favorite-count]').textContent = remaining;
                if (remaining === 0) section.querySelector('[data-empty-state]').hidden = false;
            } catch (err) {
                btn.disabled = false;
                const status = document.querySelector('.favorites-status');
                if (status) {
                    status.textContent = 'Could not remove this favorite. Please try again.';
                    status.setAttribute('data-failed', 'true');
                }
            }
        });
    });
})();
</script>