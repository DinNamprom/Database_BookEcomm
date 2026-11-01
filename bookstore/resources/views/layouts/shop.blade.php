<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Margin')</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400;6..96,700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --cream: #f2f0e9;
            --ink: #1a1a1a;
            --accent: #d4462e;
            --taupe: #7d776b;
            --taupe-light: #d6d1c4;
        }

        body { background: var(--cream); color: var(--ink); font-family: 'IBM Plex Mono', monospace; font-size: 14px; margin: 0; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .muted { color: var(--taupe); font-size: 12px; }

        /* ---------- Header ---------- */
        .top-banner { background: var(--ink); color: var(--cream); font-size: .75rem; letter-spacing: .05em; text-transform: uppercase; padding: .5rem 2rem; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 2px solid var(--ink); }
        .header-logo { font-family: 'Bodoni Moda', serif; font-size: 2.5rem; font-weight: 700; letter-spacing: .1em; text-decoration: none; color: var(--ink); position: relative; }
        .header-logo sup { font-family: 'IBM Plex Mono', monospace; font-size: 1rem; position: absolute; top: .5rem; right: -1.2rem; color: var(--accent); }
        .nav-links, .header-right { display: flex; gap: 2rem; text-transform: uppercase; font-size: .9rem; }
        .nav-links a, .header-right a { text-decoration: none; color: var(--ink); }
        .nav-links a.active { color: var(--accent); }

        /* ---------- Step indicator ---------- */
        .steps { display: flex; gap: 2.5rem; padding: 1rem 2rem; border-bottom: 2px solid var(--ink); font-size: .8rem; text-transform: uppercase; letter-spacing: .05em; color: var(--taupe); }

        /* ---------- Page ---------- */
        .shop-main { max-width: 1200px; margin: 0 auto; padding: 40px 2rem 0; }
        .eyebrow { font-size: 12px; letter-spacing: .1em; text-transform: uppercase; color: var(--accent); margin: 0 0 8px; }
        .page-title { font-size: 48px; line-height: 1.1; margin: 0 0 32px; }
        .shop-grid { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 48px; align-items: start; }
        @media (max-width: 900px) { .shop-grid { grid-template-columns: 1fr; } }

        /* ---------- Home & Catalog book cards ---------- */
        .home-section { padding: 3rem 2rem; }
        .home-section h2,
        .home-section h3,
        .catalog-section h2,
        .catalog-section h3 { font-family: 'Playfair Display', 'Bodoni Moda', serif; font-weight: 400; }

        .home-section .section-header { margin-bottom: 3rem; }
        .home-section .section-subtitle { color: #d4462e; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.5rem; }
        .home-section .section-title-wrap { display: flex; justify-content: space-between; align-items: baseline; }
        .home-section .section-title { font-size: 4rem; margin: 0; letter-spacing: -0.02em; }
        .home-section .browse-link { color: #1a1a1a; text-decoration: none; text-transform: uppercase; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }

        .home-section .books-grid,
        .catalog-section .books-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; }
        .home-section .book-card,
        .catalog-section .book-card { display: flex; flex-direction: column; min-width: 0; }

        .home-section .book-cover,
        .catalog-section .book-cover { aspect-ratio: 3/4; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem; border: 1px solid #1a1a1a; margin-bottom: 1rem; position: relative; text-align: center; text-decoration: none; color: inherit; }
        .home-section .book-cover-top,
        .catalog-section .book-cover-top { position: absolute; top: 1rem; left: 1rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; }
        .home-section .book-cover-title,
        .catalog-section .book-cover-title { font-family: 'Playfair Display', 'Bodoni Moda', serif; font-size: 2.2rem; line-height: 1.1; margin-bottom: 2rem; }
        .home-section .book-cover-author,
        .catalog-section .book-cover-author { position: absolute; bottom: 1rem; left: 1rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .home-section .book-shape,
        .catalog-section .book-shape { width: 120px; height: 120px; display: flex; justify-content: center; align-items: center; }

        /* Cover color themes (Red / Cream / Dark) */
        .catalog-section .book-cover.cover-red { background-color: var(--accent); color: var(--cream); --shape-fg: var(--cream); --shape-cutout: var(--accent); }
        .catalog-section .book-cover.cover-cream { background-color: var(--cream); color: var(--ink); --shape-fg: var(--ink); --shape-cutout: var(--cream); }
        .catalog-section .book-cover.cover-dark { background-color: var(--ink); color: var(--cream); --shape-fg: var(--cream); --shape-cutout: var(--ink); }

        .home-section .book-meta,
        .catalog-section .book-meta { font-size: 0.8rem; text-transform: uppercase; color: #666; margin-bottom: 0.5rem; }
        .home-section .book-title,
        .catalog-section .book-title { font-family: 'Playfair Display', 'Bodoni Moda', serif; font-size: 1.8rem; margin: 0 0 1rem 0; line-height: 1.2; }
        .catalog-rating { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin: 0 0 14px; font-size: 11px; color: var(--muted); }
        .catalog-rating-stars { display: inline-block; position: relative; color: var(--accent); font: 20px/1 Georgia, serif; letter-spacing: 2px; white-space: nowrap; }
        .catalog-rating-fill { position: absolute; top: 0; left: 0; overflow: hidden; }
        .catalog-section .book-title a { color: inherit; text-decoration: none; }
        .home-section .book-author-info,
        .catalog-section .book-author-info { font-size: 0.9rem; margin-bottom: 0.2rem; }
        .home-section .book-format-stock,
        .catalog-section .book-format-stock { font-size: 0.8rem; color: #666; padding-bottom: 1rem; border-bottom: 1px solid #ccc; margin-bottom: 1rem; }
        .home-section .book-price-action,
        .catalog-section .book-price-action { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
        .home-section .book-price,
        .catalog-section .book-price { font-size: 1.1rem; }
        .home-section .view-book-btn,
        .catalog-section .view-book-btn { color: #d4462e; text-decoration: none; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; }

        /* Shapes for dummy covers */
        .home-section .shape-circle,
        .catalog-section .shape-circle { width: 80px; height: 80px; border-radius: 50%; border: 10px solid var(--shape-fg, white); position: relative; }
        .home-section .shape-circle::after,
        .catalog-section .shape-circle::after { content: ''; position: absolute; width: 20px; height: 20px; background: var(--shape-fg, white); border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%); }

        .home-section .shape-steps,
        .catalog-section .shape-steps { width: 100px; height: 60px; display: flex; flex-direction: column; align-items: center; }
        .home-section .shape-steps .step1,
        .catalog-section .shape-steps .step1 { width: 100px; height: 5px; background: var(--shape-fg, black); margin-bottom: 5px; }
        .home-section .shape-steps .step2,
        .catalog-section .shape-steps .step2 { width: 80px; height: 20px; border: 2px solid var(--shape-fg, black); margin-bottom: 5px; }
        .home-section .shape-steps .step3,
        .catalog-section .shape-steps .step3 { width: 50px; height: 30px; background: var(--shape-fg, black); }

        .home-section .shape-split-circle,
        .catalog-section .shape-split-circle { width: 90px; height: 90px; border-radius: 50%; background: var(--shape-fg, white); position: relative; overflow: hidden; }
        .home-section .shape-split-circle::after,
        .catalog-section .shape-split-circle::after { content: ''; position: absolute; top: 45%; left: -10px; right: -10px; height: 10px; background: var(--shape-cutout, #1a1a1a); }

        .home-section .shape-bars,
        .catalog-section .shape-bars { display: flex; align-items: flex-end; gap: 8px; height: 80px; }
        .home-section .shape-bars div,
        .catalog-section .shape-bars div { background: var(--shape-fg, black); width: 20px; }
        .home-section .shape-bars .b1,
        .catalog-section .shape-bars .b1 { height: 40px; }
        .home-section .shape-bars .b2,
        .catalog-section .shape-bars .b2 { height: 70px; }
        .home-section .shape-bars .b3,
        .catalog-section .shape-bars .b3 { height: 50px; }
        .home-section .shape-bars .b4,
        .catalog-section .shape-bars .b4 { height: 80px; }

        /* ---------- Browse All Books (Catalog) ---------- */
        .catalog-main { max-width: 1360px; margin: 0 auto; padding: 40px 2rem 0; }
        .catalog-section .page-title { font-size: clamp(44px, 5vw, 68px); margin: 0 0 28px; }
        .catalog-toolbar { display: grid; grid-template-columns: minmax(0, 1fr) 260px; gap: 16px; margin-bottom: 24px; }
        .catalog-search { display: flex; align-items: center; gap: 12px; height: 50px; padding: 0 16px; border: 1px solid var(--ink); }
        .catalog-search input { flex: 1; min-width: 0; border: 0; background: transparent; font: inherit; font-size: 12px; color: var(--ink); outline: none; }
        .catalog-search input::placeholder { color: var(--taupe); }
        .catalog-search .shortcut { font-size: 11px; color: var(--taupe); }
        .catalog-sort { position: relative; display: flex; align-items: center; height: 50px; border: 1px solid var(--ink); }
        .catalog-sort select { width: 100%; height: 100%; padding: 0 36px 0 16px; border: 0; background: transparent; font: inherit; font-size: 12px; color: var(--ink); appearance: none; cursor: pointer; outline: none; }
        .catalog-sort .chevron { position: absolute; right: 14px; font-size: 11px; pointer-events: none; }
        .catalog-guest-note { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 14px 0; border-top: 1px solid var(--taupe-light); border-bottom: 1px solid var(--taupe-light); font-size: 12px; }
        .catalog-guest-note p { margin: 0; }
        .catalog-guest-note a { color: var(--accent); text-decoration: none; white-space: nowrap; }
        .catalog-divider { border: 0; border-top: 1px solid var(--taupe-light); margin: 0; }
        .catalog-layout { display: grid; grid-template-columns: 190px minmax(0, 1fr); gap: 48px; align-items: start; padding-top: 28px; }
        .catalog-sidebar h4 { font-family: 'IBM Plex Mono', monospace; font-size: 11px; letter-spacing: .08em; text-transform: uppercase; margin: 0 0 14px; }
        .catalog-sidebar hr { border: 0; border-top: 1px solid var(--taupe-light); margin: 22px 0; }
        .catalog-subject-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; font-size: 12px; }
        .catalog-subject-list a { display: flex; justify-content: space-between; color: var(--ink); text-decoration: none; }
        .catalog-subject-list a.active .subject-label { color: var(--accent); }
        .catalog-subject-list .subject-count { color: var(--taupe); font-size: 11px; }
        .catalog-filter-checks { display: grid; gap: 10px; font-size: 12px; }
        .catalog-filter-checks label { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
        .catalog-filter-checks input[type="checkbox"] { accent-color: var(--ink); cursor: pointer; }
        .catalog-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; }
        .catalog-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 36px; padding-top: 20px; border-top: 1px solid var(--taupe-light); font-size: 12px; }
        .catalog-pages { display: flex; align-items: center; gap: 18px; }
        .catalog-pages a, .catalog-pages span { color: var(--ink); text-decoration: none; }
        .catalog-pages .active { color: var(--accent); }
        .catalog-pages .disabled { color: var(--accent); opacity: .7; }
        @media (max-width: 1024px) {
            .catalog-layout { grid-template-columns: 1fr; }
            .catalog-section .books-grid { grid-template-columns: repeat(2, 1fr); }
            .catalog-toolbar { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .catalog-section .books-grid { grid-template-columns: 1fr; }
        }

        /* ---------- Cart ---------- */
        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th { text-align: left; font-size: 11px; font-weight: 400; letter-spacing: .1em; text-transform: uppercase; color: var(--taupe); padding: 0 0 12px; border-bottom: 1px solid var(--ink); }
        .cart-table td { padding: 20px 0; border-bottom: 1px solid var(--taupe-light); vertical-align: top; }
        .cart-table .ctr { text-align: center; }
        .cart-table .num { text-align: right; }
        .cart-item { display: flex; gap: 16px; }
        .cart-item .cover { width: 64px; height: 88px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 9px; padding: 4px; background: var(--taupe-light); }
        .cart-item a { color: inherit; text-decoration: none; }
        .cart-item .book-title a:hover { text-decoration: underline; }
        .link-remove { background: none; border: 0; padding: 0; margin-top: 8px; font: inherit; font-size: 12px; color: var(--accent); text-decoration: underline; cursor: pointer; }
        .qty { display: inline-flex; align-items: center; border: 1px solid var(--ink); }
        .qty form { margin: 0; }
        .qty button { width: 32px; height: 36px; background: transparent; border: 0; font: inherit; cursor: pointer; }
        .qty input { width: 44px; height: 36px; text-align: center; border: 0; border-left: 1px solid var(--ink); border-right: 1px solid var(--ink); background: transparent; font: inherit; -moz-appearance: textfield; }
        .qty input::-webkit-outer-spin-button, .qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .cart-foot { display: flex; justify-content: space-between; padding-top: 16px; font-size: 12px; color: var(--taupe); }
        .cart-foot a { color: var(--ink); }
        .step-heading { margin-top: 1rem; }
        

        /* ---------- Order summary ---------- */
        .summary { border: 1px solid var(--ink); padding: 24px; }
        .summary h2 { font-size: 24px; margin: 0 0 16px; }
        .summary .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .summary .total { display: flex; justify-content: space-between; align-items: baseline; border-top: 1px solid var(--ink); margin-top: 12px; padding-top: 12px; }
        .summary .total .label { font-size: 12px; letter-spacing: .1em; text-transform: uppercase; }
        .summary .total .amount { font-family: 'Bodoni Moda', serif; font-size: 28px; }
        .summary .divider { border-top: 1px solid var(--taupe-light); margin: 16px 0; }
        .summary .label-sm { font-size: 11px; letter-spacing: .1em; text-transform: uppercase; color: var(--taupe); margin: 0 0 4px; }
        .btn-accent { display: block; text-align: center; background: var(--accent); color: #fff; text-decoration: none; padding: 14px 16px; margin: 16px 0; font-size: 13px; }
        .btn-accent:hover { opacity: .9; }

        /* ---------- Footer ---------- */
        .site-footer { background: #1a1a16; color: var(--cream); padding: 56px 0 40px; margin-top: 5rem; }
        .site-footer .brand-lg { font-family: 'Bodoni Moda', serif; font-size: 44px; line-height: 1; letter-spacing: .04em; display: flex; align-items: center; gap: 6px; }
        .site-footer .reg { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: 1px solid var(--cream); border-radius: 50%; font-size: 14px; letter-spacing: 0; }
        .site-footer p { font-size: 12px; line-height: 1.6; margin: 20px 0 0; }
        .site-footer hr { border: 0; border-top: 1px solid #4a4943; margin: 40px 0 28px; }
        .site-footer .legal { font-size: 10px; letter-spacing: .06em; text-transform: uppercase; margin: 0; }

        /* ---------- Base ---------- */
        .member-page { --paper: #f4f1e9; --ink: #191919; --red: #d9361e; background: var(--paper); color: var(--ink); font: 14px/1.6 'IBM Plex Mono', monospace; color-scheme: light; margin: 0; }
        .member-page * { box-sizing: border-box; }
        .member-page [hidden] { display: none !important; }
        .member-page a { color: inherit; text-decoration: none; }
        .member-page button { cursor: pointer; font: inherit; }
        .member-page button:disabled { cursor: wait; opacity: .55; }
        .member-page :focus-visible { outline: 2px solid var(--red); outline-offset: 4px; }

        /* ---------- Typography ---------- */
        .member-page h1,
        .member-page h2,
        .member-page .sidebar-name,
        .member-page .delivery-name { font-family: 'Bodoni Moda', Georgia, serif; font-weight: 400; line-height: 1.15; margin: 0; }
        .member-page h2 { font-size: 40px; margin: 0 0 28px; }
        .member-page .accent,
        .member-page [aria-current=page] { color: var(--red); }
        .member-page .eyebrow { color: var(--red); font-size: 12px; letter-spacing: .03em; }

        /* ---------- Page frame ---------- */
        .member-page .member-main { max-width: 1800px; margin: auto; padding: 60px 4.5% 75px; min-height: 65vh; }
        .member-page .member-hero { border-bottom: 1px solid; margin-bottom: 40px; }
        .member-page .hero-line { display: flex; align-items: flex-end; justify-content: space-between; gap: 32px; margin: 24px 0 40px; }
        .member-page .hero-line h1 { font-size: clamp(48px, 6vw, 100px); }
        .member-page .hero-line > p { max-width: 360px; margin: 0 0 5px; }
        .member-page .account-grid { display: grid; grid-template-columns: 230px minmax(0, 1fr); gap: 48px; }
        .member-page .account-content { min-width: 0; }

        /* ---------- Account sidebar ---------- */
        .member-page .sidebar-name { font-size: 30px !important; padding-bottom: 24px; border-bottom: 1px solid #c6c1b6; overflow-wrap: anywhere; }
        .member-page .account-sidebar nav { display: grid; gap: 24px; padding: 24px 0; border-bottom: 1px solid #c6c1b6; margin-bottom: 24px; }
        .member-page .text-button { border: 0; background: none; padding: 0; color: inherit; }
        .member-page .text-button:hover { text-decoration: underline; }

        /* ---------- Forms ---------- */
        .member-page .name-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .member-page .field { margin-bottom: 22px; }
        .member-page .field label { display: block; font-size: 12px; margin-bottom: 8px; }
        .member-page .field input,
        .member-page .field textarea { display: block; width: 100%; min-width: 0; border: 1px solid #bdb8ac; border-radius: 0; background: transparent; color: var(--ink); padding: 15px; font: inherit; }
        .member-page .field textarea { resize: vertical; }
        .member-page .field [aria-invalid=true] { border-color: var(--red); }
        .member-page .field-error { color: #ac2513; font-size: 12px; margin: 5px 0 0; }
        .member-page .field-error:empty { display: none; }
        .member-page .form-actions { display: flex; gap: 25px; align-items: center; flex-wrap: wrap; }
        .member-page .form-actions > a { font-size: 12px; }
        .member-page .primary-button { border: 1px solid var(--red); background: var(--red); color: #fff; padding: 16px 23px; display: inline-flex; gap: 28px; justify-content: space-between; }
        .member-page .primary-button:hover { background: #bc2c17; }
        .member-page .form-status { min-height: 1.5em; font-size: 12px; margin: 16px 0 0; overflow-wrap: anywhere; }
        .member-page .form-status[data-failed=true] { color: #ac2513; }

        /* ---------- Member information ---------- */
        .member-page .details-grid { display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(260px, 1fr); gap: 40px; border-bottom: 1px solid; padding-bottom: 40px; }
        .member-page .delivery-card { border: 1px solid; padding: 24px; align-self: start; scroll-margin-top: 25px; }
        .member-page .address-fields { border: 0; padding: 0; margin: 0; min-width: 0; }
        .member-page .address-legend { font-size: 12px; margin-bottom: 16px; }
        .member-page .address-fields:disabled input { opacity: 1; -webkit-text-fill-color: var(--ink); cursor: default; }
        .member-page .delivery-card form { margin-top: 24px; }
        .member-page .card-heading { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 24px; }
        .member-page .card-heading h3 { font: 12px 'IBM Plex Mono', monospace; margin: 0; }
        .member-page .delivery-name { font-size: 32px !important; margin-bottom: 20px !important; overflow-wrap: anywhere; }
        .member-page .delivery-address { white-space: pre-wrap; overflow-wrap: anywhere; }
        .member-page .delivery-note { border-top: 1px solid #c6c1b6; padding-top: 22px; margin-top: 25px; color: #716d63; font-size: 12px; }

        /* ---------- Favorites ---------- */
        .member-page .favorites-status:empty { display: none; }
        .member-page .favorite-section { margin-bottom: 55px; }
        .member-page .section-heading { display: flex; align-items: baseline; gap: 20px; border-bottom: 1px solid; padding-bottom: 24px; }
        .member-page .section-heading h1 { font-size: 46px; }
        .member-page .saved-count { font-size: 11px; color: #716d63; }
        .member-page .empty-state { padding: 35px 0; color: #716d63; }
        .member-page .favorite-row { padding: 30px 0; border-bottom: 1px solid #c6c1b6; display: grid; grid-template-columns: minmax(180px, 1fr) minmax(240px, 1.4fr); gap: 30px; }
        .member-page .favorite-info h2 { margin: 12px 0 20px; font-size: 36px; overflow-wrap: anywhere; }
        .member-page .favorite-actions { display: flex; gap: 30px; font-size: 12px; margin-top: 22px; }
        .member-page .catalog-count,
        .member-page .no-books { font-size: 12px; color: #716d63; }
        .member-page .favorite-books { display: flex; gap: 20px; flex-wrap: wrap; }
        .member-page .favorite-book { display: grid; grid-template-columns: 100px minmax(0, 1fr); gap: 18px; flex: 1; min-width: 210px; align-items: center; }
        .member-page .favorite-book:nth-child(even) .book-cover { background: #c8cfbd; border-color: #a2ae91; }
        .member-page .favorite-book h3 { font-size: 14px; line-height: 1.4; margin: 0 0 9px; overflow-wrap: anywhere; }
        .member-page .favorite-book p,
        .member-page .book-link { font-size: 10px; }
        .member-page .book-link { display: inline-block; margin-top: 10px; }
        .member-page .book-cover { height: 145px; padding: 12px; background: #ddd6c5; border-left: 5px solid #b7ad92; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; }
        .member-page .book-cover strong { font: 19px/1.1 Georgia, serif; overflow-wrap: anywhere; }
        .member-page .book-cover span { font-size: 7px; line-height: 1.4; }

        /* ---------- Member area: responsive ---------- */
        @media (min-width: 1500px) {
            .member-page .account-grid { grid-template-columns: 250px minmax(0, 1fr); gap: 65px; }
            .member-page .details-grid { gap: 50px; }
            .member-page .member-main { padding-top: 65px; }
        }

        @media (max-width: 1100px) {
            .member-page .account-grid { grid-template-columns: 180px minmax(0, 1fr); gap: 30px; }
            .member-page .details-grid { grid-template-columns: 1fr; }
            .member-page .delivery-card { max-width: 560px; }
            .member-page .favorite-row { grid-template-columns: 1fr; }
            .member-page .hero-line > p { max-width: 280px; }
        }

        @media (max-width: 650px) {
            .member-page .member-main { padding: 30px 5% 45px; }
            .member-page .hero-line { display: block; margin-bottom: 28px; }
            .member-page .hero-line > p { margin-top: 20px; max-width: none; font-size: 12px; }
            .member-page .account-grid { grid-template-columns: 1fr; gap: 30px; }
            .member-page .sidebar-name { font-size: 24px !important; padding-bottom: 14px; }
            .member-page .account-sidebar nav { display: flex; flex-wrap: wrap; gap: 14px 22px; padding: 16px 0; margin-bottom: 12px; font-size: 11px; }
            .member-page .account-sidebar > form { font-size: 11px; }
            .member-page .name-fields { grid-template-columns: 1fr; gap: 0; }
            .member-page .details-grid { gap: 25px; }
            .member-page h2 { font-size: 34px; }
            .member-page .section-heading { gap: 12px; }
            .member-page .section-heading h1 { font-size: 36px; }
            .member-page .delivery-card { padding: 20px; }
            .member-page .favorite-actions { justify-content: space-between; }
            .member-page .favorite-book { min-width: 100%; }
        }
    </style>

    @stack('head')
</head>
<body class="@yield('body-class')">

    <div class="top-banner">INDEPENDENT BOOKS. UNCOMMON PERSPECTIVES.</div>

    <header class="header">
        <a href="{{ url('/') }}" class="header-logo">MARGIN<sup>®</sup></a>

        <nav class="nav-links">
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">Books</a>
            <a href="#">Our story</a>
        </nav>

        <div class="header-right">
            @guest
                <span>
                    <a href="{{ url('/login') }}">Log in</a> /
                    <a href="{{ url('/register') }}">Register</a>
                </span>
            @else
                <a href="{{ route('member-information.index') }}" data-member-account aria-label="My account"><span data-member-name>{{ auth()->user()->name }}</span> ↗</a>
            @endguest
            <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}">Bag ({{ auth()->check() ? auth()->user()->cartItems()->sum('quantity') : 0 }})</a>
        </div>
    </header>

    {{-- Step indicator: the page's own section replaces the muted default with an accent-coloured label --}}
    @unless (View::hasSection('no-steps'))
        <div class="steps">
            <span>@hasSection('step-1') @yield('step-1') @else 01 / Bag @endif</span>
            <span>@hasSection('step-2') @yield('step-2') @else 02 / Checkout @endif</span>
            <span>@hasSection('step-3') @yield('step-3') @else 03 / Confirmation @endif</span>
        </div>
    @endunless

    @yield('hero')

    <main class="@yield('main-class', 'shop-main')">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="wrap">
            <div class="brand-lg">MARGIN <span class="reg" aria-hidden="true">R</span></div>
            <p>For the curious. For the unhurried.<br>A little room for a different point of view.</p>
            <hr>
            <p class="legal">&copy; {{ date('Y') }} Margin Books. Read outside the lines.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>