<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARGIN - Independent Books</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f2f0e9;
            color: #1a1a1a;
            font-family: 'IBM Plex Mono', monospace;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid #1a1a1a;
        }
        .header-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-decoration: none;
            color: #1a1a1a;
            position: relative;
        }
        .header-logo sup {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1rem;
            position: absolute;
            top: 0.5rem;
            right: -1.2rem;
            color: #d4462e;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .nav-links a {
            text-decoration: none;
            color: #1a1a1a;
        }
        .nav-links a.active {
            color: #d4462e;
        }
        .header-right {
            display: flex;
            gap: 2rem;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .header-right a {
            text-decoration: none;
            color: #1a1a1a;
        }
        .top-banner {
            background-color: #1a1a1a;
            color: #f2f0e9;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.5rem 2rem;
        }
        .sub-banner {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 1rem 2rem;
            border-bottom: 2px solid #1a1a1a;
            color: #1a1a1a;
        }
        .footer {
            background-color: #1a1a1a;
            color: #f2f0e9;
            padding: 3rem 2rem;
            margin-top: 5rem;
        }
        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }
        .footer-logo sup {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1rem;
            color: #666;
        }
        .footer-text {
            max-width: 400px;
            margin-bottom: 3rem;
            line-height: 1.5;
        }
        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 1.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="top-banner">
        INDEPENDENT BOOKS. UNCOMMON PERSPECTIVES.
    </div>

    <header class="header">
        <a href="/" class="header-logo">MARGIN<sup>®</sup></a>
        <nav class="nav-links">
            <a href="/" class="active">Home</a>
            <a href="#">Books</a>
            <a href="#">Our story</a>
        </nav>
        <div class="header-right">
            <a href="/login">Log in / Register</a>
            <a href="#">Bag (0)</a>
        </div>
    </header>

    <div class="sub-banner">
        <span>INDEPENDENT BY DESIGN</span>
        <span>HAND-PICKED, NOT ALGORITHMIC</span>
        <span>BOOKS WORTH KEEPING</span>
        <span>SHIPPED WITH CARE</span>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-logo">MARGIN<sup>®</sup></div>
        <div class="footer-text">
            For the curious. For the unhurried.<br>
            A little room for a different point of view.
        </div>
        <div class="footer-bottom">
            © 2026 MARGIN BOOKS. READ OUTSIDE THE LINES.
        </div>
    </footer>
</body>
</html>
