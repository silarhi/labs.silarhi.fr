<?php // user.php
require __DIR__.'/lib.php';

$session = session_read();

fragment('/user.php', 'private', 0, static function () use ($session): void { ?>
    <?php if ($session['logged'] ?? false) { ?>
        Dernière connexion il y a <strong><?= time() - $session['last_login'] ?>s</strong>
    <?php } else { ?>
        <span class="text-secondary">Pas de dernière connexion</span>
    <?php } ?>
<?php });
