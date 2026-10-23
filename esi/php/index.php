<?php // index.php
require __DIR__.'/lib.php';

// Tells Varnish to parse the <esi:include> tags (see vcl_backend_response)
header('Surrogate-Control: abc=ESI/1.0');
$ttl = cache_for(10);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Varnish & ESI Demo · SILARHI labs</title>
    <meta name="description" content="Un POC des fragments ESI (Edge Side Includes) avec Varnish, PHP et Docker.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.7.1/dist/css/tabler.min.css" integrity="sha384-FwFRx3iCRjX2MYq9Ni5guhu0G/HAeKcgZhAMxMtZkceAylYMpaOn6hHeId2bywU9" crossorigin="anonymous">

    <!-- Before the first paint: the theme saved by the toggle (the same key as the CI demo, on the same origin),
         else the system one -->
    <script>
        (() => {
            let theme = null
            try {
                theme = localStorage.getItem('theme')
            } catch {}
            const dark = theme ? 'dark' === theme : matchMedia('(prefers-color-scheme: dark)').matches
            document.documentElement.setAttribute('data-bs-theme', dark ? 'dark' : 'light')
        })()
    </script>

    <!-- The look of the CI demo (silarhi/symfony-docker-ci): Tabler, a purple primary, dark shell, monospace labels -->
    <style>
        :root {
            --tblr-primary: #6f42c1;
            --tblr-primary-rgb: 111, 66, 193;
            --tblr-border-radius: 0.25rem;
            --tblr-border-radius-lg: 0.25rem;
            --shell-dark: #0f172a;
            --app-bg: light-dark(#f8fafc, var(--tblr-body-bg));
            --scope-public: var(--tblr-success);
            --scope-private: var(--tblr-danger);
        }

        html, body { height: 100%; }
        body { display: flex; flex-direction: column; background: var(--app-bg); }
        main { flex-shrink: 0; }

        .navbar { background: var(--shell-dark); border-bottom: 1px solid rgb(255 255 255 / 8%); }
        .navbar .nav-link.active { background: rgb(255 255 255 / 8%); border-radius: var(--tblr-border-radius); }

        .badge-version {
            font-family: var(--tblr-font-monospace);
            font-size: .75rem;
            font-weight: 400;
            color: #c5b3e6;
            background: rgb(111 66 193 / 25%);
            border: 1px solid rgb(111 66 193 / 50%);
        }

        .btn-theme-toggle {
            position: relative;
            display: inline-flex;
            width: 2.25rem;
            height: 2.25rem;
            padding: 0;
            color: rgb(255 255 255 / 70%);
            background: transparent;
            border: 0;
            border-radius: var(--tblr-border-radius);
            transition: color .2s, background-color .2s;
        }
        .btn-theme-toggle:hover { color: #fff; background: rgb(255 255 255 / 10%); }
        .btn-theme-toggle:focus-visible { outline: 2px solid #b7a0e0; outline-offset: 2px; }
        .theme-icon {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .3s ease-in-out, transform .3s ease-in-out;
        }
        .theme-icon svg { width: 1.25rem; height: 1.25rem; }
        .theme-icon-sun { opacity: 0; transform: scale(0) rotate(90deg); }
        :root[data-bs-theme='dark'] .theme-icon-moon { opacity: 0; transform: scale(0) rotate(-90deg); }
        :root[data-bs-theme='dark'] .theme-icon-sun { opacity: 1; transform: none; }

        .site-footer {
            margin-top: auto;
            padding: 1.25rem 0;
            color: rgb(255 255 255 / 70%);
            background: var(--shell-dark);
        }
        .site-footer a { color: #b7a0e0; }
        .site-footer .list-inline-item:not(:last-child)::after { content: '|'; margin-left: .5rem; opacity: .4; }

        .label-mono,
        .status-bar,
        .tag-mono,
        .section-header-meta,
        .fragment-header,
        .fragment-footer,
        .code-panel-meta {
            font-family: var(--tblr-font-monospace);
            font-size: .75rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .tag-mono {
            display: inline-block;
            padding: .125rem .5rem;
            background: var(--tblr-bg-surface);
            border: 1px solid var(--tblr-border-color);
        }

        .status-dot-square { display: inline-block; flex-shrink: 0; width: .5rem; height: .5rem; }

        .status-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .5rem 1.5rem;
            padding: .625rem 1rem;
            background: var(--tblr-bg-surface);
            border: 1px solid var(--tblr-border-color);
        }
        .status-bar-label { display: inline-flex; align-items: center; gap: .5rem; margin-right: auto; font-weight: 700; }

        .page-hero-title { margin-bottom: .75rem; font-size: 2.25rem; letter-spacing: -.02em; }

        .section-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .5rem 1rem;
            padding-bottom: .75rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--tblr-border-color);
        }
        .section-header-title { margin: 0; font-size: 1.25rem; }
        .section-header-meta { color: var(--tblr-secondary); }

        /* A fragment: the color of the dot and of the top edge says who shares it, a dashed edge that Varnish
           does not keep it */
        .fragment {
            --scope-color: var(--scope-public);
            display: flex;
            flex-direction: column;
            height: 100%;
            background: var(--tblr-bg-surface);
            border: 1px solid var(--tblr-border-color);
            border-top: 3px solid var(--scope-color);
        }
        .fragment-private { --scope-color: var(--scope-private); }
        .fragment-uncached { border-style: dashed; border-top-style: dashed; }
        .fragment-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            padding: .5rem 1rem;
            border-bottom: 1px solid var(--tblr-border-color);
        }
        .fragment-src { padding: 0; font-size: .75rem; text-transform: none; letter-spacing: 0; color: var(--tblr-body-color); background: none; }
        .fragment-tags { display: inline-flex; gap: .375rem; }
        .fragment-tag {
            display: inline-flex;
            align-items: center;
            gap: .375rem;
            padding: .0625rem .375rem;
            color: var(--tblr-secondary);
            border: 1px solid var(--tblr-border-color);
        }
        .fragment-tag .status-dot-square { background: var(--scope-color); }
        .fragment-body { flex-grow: 1; padding: 1rem; }
        .fragment-footer {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: .25rem 1rem;
            padding: .5rem 1rem;
            color: var(--tblr-secondary);
            background: light-dark(#f8fafc, rgb(0 0 0 / 15%));
            border-top: 1px solid var(--tblr-border-color);
        }
        /* The TTL left before Varnish fetches the fragment again, set by the script at the bottom */
        .fragment-ttl { position: absolute; inset: auto 0 0; height: 2px; }
        .fragment-ttl-bar {
            display: block;
            height: 100%;
            background: var(--scope-color);
            transform-origin: left;
            transform: scaleX(var(--ttl-left, 1));
            transition: transform 1s linear;
        }
        .fragment-expired .fragment-ttl-bar { background: var(--tblr-warning); }

        .legend { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; }
        .legend-swatch { display: inline-block; width: 1.5rem; height: .75rem; margin-right: .375rem; vertical-align: -1px; border: 2px solid var(--tblr-secondary); }

        .code-panel { display: flex; flex-direction: column; height: 100%; overflow: hidden; background: var(--shell-dark); border-radius: var(--tblr-border-radius); }
        .code-panel-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            padding: .625rem 1rem;
            border-bottom: 1px solid rgb(255 255 255 / 8%);
        }
        .code-panel-title { font-family: var(--tblr-font-monospace); font-size: .8125rem; color: #fff; }
        .code-panel-meta { color: #64748b; }
        .code-panel pre {
            flex-grow: 1;
            margin: 0;
            padding: 1rem;
            color: #cbd5e1;
            font-size: .8125rem;
            line-height: 1.7;
            background: transparent;
            border-radius: 0;
        }
        .code-panel .hl { color: #6ee7b7; }
        .code-panel .cm { color: #64748b; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand gap-2" href="<?= h(base_path()) ?>/">
            ESI Demo
            <span class="badge badge-version">varnish 8</span>
        </a>
        <div class="d-flex align-items-center gap-1 ms-auto">
            <ul class="navbar-nav d-none d-sm-flex">
                <li class="nav-item"><a class="nav-link" href="/">CI Demo</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= h(base_path()) ?>/" aria-current="page">ESI</a></li>
                <li class="nav-item"><a class="nav-link" href="/php">PHP</a></li>
            </ul>
            <button type="button" class="btn-theme-toggle" aria-label="Mode sombre" aria-pressed="false">
                <span class="theme-icon theme-icon-moon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/></svg></span>
                <span class="theme-icon theme-icon-sun"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></g></svg></span>
            </button>
        </div>
    </div>
</nav>

<main class="container my-4">
    <div class="status-bar mb-3">
        <span class="status-bar-label"><span class="status-dot-square bg-success"></span>Page index.php</span>
        <span><span class="text-secondary">Cache:</span> <strong>public</strong></span>
        <span><span class="text-secondary">TTL:</span> <strong><?= $ttl ?>s</strong></span>
        <span><span class="text-secondary">Généré:</span> <strong><?= date('H:i:s') ?></strong></span>
    </div>

    <header class="card mb-5">
        <div class="card-body p-4 p-lg-5">
            <div class="label-mono text-primary mb-2">Varnish · Edge Side Includes</div>
            <h1 class="page-hero-title">Une page, cinq durées de cache</h1>
            <p class="lead text-secondary mb-4">
                Varnish met la page en cache 10 secondes, mais assemble à chaque requête les fragments
                <code>&lt;esi:include&gt;</code> qu'elle contient&nbsp;: chacun a sa propre durée de vie, publique ou
                par session. Rechargez la page, connectez-vous, et regardez quelles heures changent.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="https://blog.silarhi.fr/varnish-fragment-esi-docker/">Lire l'article</a>
                <a class="btn" href="https://github.com/silarhi/labs.silarhi.fr/tree/main/esi">Sources sur GitHub</a>
            </div>
        </div>
    </header>

    <div class="section-header">
        <div class="d-flex align-items-center gap-2">
            <span class="tag-mono">ESI</span>
            <h2 class="section-header-title">Fragments</h2>
        </div>
        <div class="legend section-header-meta">
            <span><span class="legend-swatch" style="border-top: 3px solid var(--scope-public)"></span>Public</span>
            <span><span class="legend-swatch" style="border-top: 3px solid var(--scope-private)"></span>Privé</span>
            <span><span class="legend-swatch"></span>En cache</span>
            <span><span class="legend-swatch" style="border-style: dashed"></span>Pas de cache</span>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-12">
            <esi:include src="/menu.php" />
        </div>
        <div class="col-12">
            <esi:include src="/time.php" />
        </div>
        <div class="col-lg-8">
            <?php fragment('/index.php', 'public', $ttl, static function (): void { ?>
                <h3 class="mb-1">Contenu public de la page</h3>
                <p class="text-secondary mb-0">
                    Ce bloc n'est pas un fragment&nbsp;: il fait partie de la page elle-même, et suit donc son TTL.
                </p>
            <?php }, include: false); ?>
        </div>
        <div class="col-lg-4 d-flex flex-column gap-3">
            <esi:include src="/user.php" />
            <esi:include src="/sidebar.php" />
        </div>
    </div>

    <div class="section-header">
        <div class="d-flex align-items-center gap-2">
            <span class="tag-mono">Code</span>
            <h2 class="section-header-title">Sous le capot</h2>
        </div>
        <span class="section-header-meta">PHP + VCL</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="code-panel">
                <div class="code-panel-header">
                    <span class="code-panel-title">php/index.php</span>
                    <span class="code-panel-meta">PHP</span>
                </div>
                <pre><code><span class="cm">// Varnish doit interpréter les balises ESI</span>
header(<span class="hl">'Surrogate-Control: abc=ESI/1.0'</span>);
<span class="cm">// La page reste 10s en cache</span>
header(<span class="hl">'X-Reverse-Proxy-TTL: 10'</span>);
?&gt;
&lt;esi:include src=<span class="hl">"/menu.php"</span> /&gt;
&lt;esi:include src=<span class="hl">"/time.php"</span> /&gt;
&lt;esi:include src=<span class="hl">"/user.php"</span> /&gt;
&lt;esi:include src=<span class="hl">"/sidebar.php"</span> /&gt;</code></pre>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="code-panel">
                <div class="code-panel-header">
                    <span class="code-panel-title">varnish/default.vcl</span>
                    <span class="code-panel-meta">VCL</span>
                </div>
                <pre><code>sub vcl_backend_response {
    if (beresp.http.Surrogate-Control ~ <span class="hl">"ESI/1.0"</span>) {
        set beresp.do_esi = true;
    }
    if (beresp.http.X-Reverse-Proxy-TTL) {
        set beresp.ttl = std.duration(
            beresp.http.X-Reverse-Proxy-TTL + <span class="hl">"s"</span>, 0s);
    }
}
<span class="cm"># menu.php et user.php : une entrée par session</span>
sub vcl_hash { hash_data(req.http.Cookie); }</code></pre>
            </div>
        </div>
    </div>
</main>

<footer class="site-footer">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
        <ul class="list-inline mb-0 font-monospace small text-center text-md-start">
            <li class="list-inline-item"><a href="https://blog.silarhi.fr/varnish-fragment-esi-docker/">Article</a></li>
            <li class="list-inline-item"><a href="https://github.com/silarhi/labs.silarhi.fr/tree/main/esi">Sources</a></li>
            <li class="list-inline-item">PHP <span class="badge badge-version"><?= h(PHP_VERSION) ?></span></li>
        </ul>
        <a class="small" href="https://sainthillier.fr">Guillaume Sainthillier - PHP Web Developer</a>
    </div>
</footer>

<script>
    // Light / dark toggle: the system theme until the visitor picks one, then their choice, saved
    ;(() => {
        const toggle = document.querySelector('.btn-theme-toggle')
        const isDark = () => 'dark' === document.documentElement.getAttribute('data-bs-theme')
        const sync = () => toggle.setAttribute('aria-pressed', String(isDark()))
        const apply = (theme) => {
            document.documentElement.setAttribute('data-bs-theme', theme)
            sync()
        }

        toggle.addEventListener('click', () => {
            const theme = isDark() ? 'light' : 'dark'
            try {
                localStorage.setItem('theme', theme)
            } catch {}
            apply(theme)
        })
        matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
            try {
                if (localStorage.getItem('theme')) {
                    return
                }
            } catch {}
            apply(event.matches ? 'dark' : 'light')
        })
        sync()
    })()

    // The TTL bars: how long each fragment has left in Varnish
    ;(() => {
        const footers = [...document.querySelectorAll('.fragment-footer[data-generated-at]')]
        if (!footers.length) {
            return
        }
        // The newest fragment was generated by this very request (time.php is never cached): its timestamp
        // is the server's "now", which cancels any offset between the server and the visitor's clocks
        const serverNow = Math.max(...footers.map((footer) => Number(footer.dataset.generatedAt)))
        const offset = Date.now() / 1000 - serverNow

        /**
         * The text next to the "Généré à" time of a fragment.
         *
         * @param {number} age seconds since Varnish fetched the fragment
         * @param {number} ttl seconds Varnish keeps it (0: not cached)
         * @returns {string}
         */
        const ttlLabel = (age, ttl) => {
            if (0 === ttl) {
                return 'À chaque requête'
            }
            // Rounded up: never "0s" while the fragment is still cached, "Expiré" when the bar empties
            const left = Math.ceil(ttl - age)

            return left > 0 ? `Expire dans ${left}s` : 'Expiré · rechargez'
        }

        const tick = () => {
            const now = Date.now() / 1000 - offset
            for (const footer of footers) {
                const ttl = Number(footer.dataset.ttl)
                const age = Math.max(0, now - Number(footer.dataset.generatedAt))
                footer.querySelector('[data-ttl-label]').textContent = ttlLabel(age, ttl)
                if (ttl > 0) {
                    footer.style.setProperty('--ttl-left', String(Math.max(0, 1 - age / ttl)))
                    footer.closest('.fragment').classList.toggle('fragment-expired', age >= ttl)
                }
            }
        }
        tick()
        setInterval(tick, 1000)
    })()
</script>
</body>
</html>
