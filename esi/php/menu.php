<?php // menu.php
require __DIR__.'/lib.php';

// Private, cached per session: vcl_hash adds the session cookie to the key
$session = session_read();
$logged = $session['logged'] ?? false;

fragment('/menu.php', 'private', cache_for(60), static function () use ($logged): void { ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span class="d-inline-flex align-items-center gap-2">
            <span class="status-dot-square bg-<?= $logged ? 'success' : 'secondary' ?>"></span>
            <strong><?= $logged ? 'Connecté' : 'Anonyme' ?></strong>
            <span class="text-secondary"><?= $logged ? 'ce menu est mis en cache pour votre session seulement' : 'ce menu est partagé par tous les visiteurs anonymes' ?></span>
        </span>
        <?php if ($logged) { ?>
            <a class="btn btn-sm" href="<?= h(base_path()) ?>/logout.php">Déconnexion</a>
        <?php } else { ?>
            <a class="btn btn-sm btn-primary" href="<?= h(base_path()) ?>/login.php">Connexion</a>
        <?php } ?>
    </div>
<?php });
