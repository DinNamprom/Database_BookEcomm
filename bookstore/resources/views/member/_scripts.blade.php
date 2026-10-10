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
                    const text = form.querySelector('[name="address"]').value;
                    document.querySelector('[data-delivery-address]').textContent = text || 'No delivery address saved yet.';
                    form.hidden = true;
                    document.querySelector('[data-edit-address]')?.setAttribute('aria-expanded', 'false');
                }
            } catch (err) {
                status.textContent = 'Something went wrong. Please try again.';
                status.setAttribute('data-failed', 'true');
            } finally {
                button.disabled = false;
            }
        });
    });

    // ----- Show / hide the address editor -----
    const editBtn = document.querySelector('[data-edit-address]');
    const editor = document.getElementById('address-editor');
    if (editBtn && editor) {
        editBtn.addEventListener('click', function () {
            editor.hidden = !editor.hidden;
            editBtn.setAttribute('aria-expanded', String(!editor.hidden));
            if (!editor.hidden) editor.querySelector('textarea')?.focus();
        });
        document.querySelector('[data-cancel-address]')?.addEventListener('click', function () {
            editor.hidden = true;
            editBtn.setAttribute('aria-expanded', 'false');
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