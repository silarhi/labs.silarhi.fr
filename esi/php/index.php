<?php // index.php
header('Surrogate-Control: abc=ESI/1.0');
header("X-Reverse-Proxy-TTL: 10");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .public {
            border: 2px dashed #198754;
        }

        .private {
            border: 2px dashed #dc3545;
        }

        .cached {
            border-style: solid;
        }
    </style>
    <script>
        const getPreferredTheme = () => {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }

        const setTheme = theme => {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }

        setTheme(getPreferredTheme())
    </script>
    <title>Varnish & ESI Demo</title>
</head>
<body class="p-2">
<div class="container public cached p-2">
    <h1 class="text-center">Varnish & ESI Demo</h1>
    <esi:include src="/menu.php" />
    <br />
    <esi:include src="/time.php" />
    <div class="row">
        <div class="col-md-8 col-lg-9">
            <div class="p-2">
                <h1>Page de contenu public</h1>
                <p class="lead">Mise en cache le
                    <span class="badge bg-secondary"><?= date('d/m/Y H:i:s') ?></span> (10s)</p>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <esi:include src="/user.php" />
            <esi:include src="/sidebar.php" />
        </div>
    </div>
</div>
<div class="container">
    <h2 class="text-center m-3">Légende des bordures</h2>
    <div class="row text-sm-center">
        <div class="col-sm-3"><span class="d-inline-block public p-1">Public</span></div>
        <div class="col-sm-3"><span class="d-inline-block public cached p-1">Public + cache</span></div>
        <div class="col-sm-3"><span class="d-inline-block private p-1">Privé</span></div>
        <div class="col-sm-3"><span class="d-inline-block private cached p-1">Privé + cache</span></div>
    </div>

    <h2 class="text-center m-4">C'est quoi ce site ?</h2>
    <p>Un POC sur les fragments ESI avec Varnish et Docker :</p>
    <ul>
        <li>L'article du blog :
            <a target="_blank" href="https://blog.silarhi.fr/varnish-fragment-esi-docker/">https://blog.silarhi.fr/varnish-fragment-esi-docker/</a>
        </li>
        <li>Sources :
            <a target="_blank" href="https://github.com/silarhi/labs.silarhi.fr/tree/main/esi">https://github.com/silarhi/labs.silarhi.fr/tree/main/esi</a>
        </li>
    </ul>
</div>
</body>
</html>
