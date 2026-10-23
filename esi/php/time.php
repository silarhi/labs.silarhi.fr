<?php // time.php
require __DIR__.'/lib.php';

fragment('/time.php', 'public', 0, static function (): void { ?>
    Heure serveur : <strong class="font-monospace"><?= date('d/m/Y H:i:s') ?></strong>, recalculée à chaque requête
<?php });
