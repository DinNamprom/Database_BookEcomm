<style>
    .auth-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 80px; align-items: start; padding-top: 56px; }
    .auth-hero { font-size: 88px; line-height: 1.02; font-weight: 400; margin: 0; }
    .auth-title { font-size: 48px; line-height: 1.1; font-weight: 400; margin: 0 0 20px; }

    .field { margin-bottom: 24px; }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .field label { display: block; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 10px; }
    .input-wrap { position: relative; }
    .input { width: 100%; box-sizing: border-box; height: 50px; padding: 0 16px; font: inherit; font-size: 13px; color: var(--ink); background: rgba(255, 255, 255, .35); border: 1px solid var(--taupe-light); border-radius: 0; }
    .input::placeholder { color: var(--taupe); opacity: .8; }
    .input:focus { outline: 2px solid var(--ink); outline-offset: 1px; }
    .input.is-invalid { border-color: var(--accent); }
    .input-wrap .input { padding-right: 52px; }
    .eye { position: absolute; top: 0; right: 0; height: 50px; width: 50px; display: flex; align-items: center; justify-content: center; background: none; border: 0; cursor: pointer; color: var(--ink); }
    .eye:focus-visible { outline: 2px solid var(--ink); outline-offset: -4px; }
    .hint { font-size: 11px; color: var(--taupe); margin: 8px 0 0; }
    .error { font-size: 11px; color: var(--accent); margin: 8px 0 0; }

    .check { display: flex; align-items: center; gap: 12px; font-size: 12px; margin-bottom: 14px; cursor: pointer; }
    .check input { appearance: none; -webkit-appearance: none; flex: none; width: 17px; height: 17px; margin: 0; border: 1px solid var(--ink); background: transparent; display: grid; place-content: center; cursor: pointer; }
    .check input:checked { background: var(--ink); }
    .check input:checked::after { content: ""; width: 4px; height: 8px; border: solid var(--cream); border-width: 0 1.5px 1.5px 0; transform: rotate(45deg) translate(-1px, -1px); }
    .check input:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
    .check a { text-decoration: underline; }

    .btn-block { display: block; width: 100%; box-sizing: border-box; text-align: center; border: 1px solid var(--accent); background: var(--accent); color: #fff; padding: 0 14px; height: 50px; line-height: 48px; font: inherit; font-size: 12px; letter-spacing: .03em; cursor: pointer; }
    .btn-block:hover { background: #bf3019; border-color: #bf3019; }
    .btn-block:focus-visible { outline: 2px solid var(--ink); outline-offset: 2px; }
    .btn-outline { background: transparent; color: var(--ink); border-color: var(--ink); }
    .btn-outline:hover { background: var(--ink); border-color: var(--ink); color: var(--cream); }

    .fine { font-size: 10px; color: var(--taupe); margin: 16px 0 0; }
    .rule { border: 0; border-top: 1px solid var(--taupe-light); margin: 32px 0; }
    .rule-ink { border-top-color: var(--ink); }

    .notice { border: 1px solid var(--accent); padding: 16px 18px; margin-bottom: 32px; }
    .notice .notice-label { font-size: 10px; letter-spacing: .08em; text-transform: uppercase; color: var(--accent); margin: 0 0 8px; }
    .notice p:last-child { margin: 0; font-size: 12px; line-height: 1.6; }

    .perks { list-style: none; padding: 24px 0 0; margin: 40px 0 0; border-top: 1px solid var(--taupe); font-size: 12px; }
    .perks li { margin-bottom: 16px; }

    .row-between { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .row-between .check { margin-bottom: 0; }
    .row-between a { font-size: 11px; }

    .link-accent { color: var(--accent); }
    .stack-gap { margin-bottom: 8px; }

    @media (max-width: 900px) {
        .auth-grid { grid-template-columns: minmax(0, 1fr); gap: 40px; padding-top: 32px; }
        .auth-hero { font-size: 52px; }
        .auth-title { font-size: 36px; }
        .field-row { grid-template-columns: 1fr; gap: 0; }
        .wrap { padding-left: 24px; padding-right: 24px; }
    }
</style>

<script>
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-toggle-password]');
        if (!btn) return;
        var input = document.getElementById(btn.getAttribute('data-toggle-password'));
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
</script>