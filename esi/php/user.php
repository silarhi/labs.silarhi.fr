<?php //user.php
require __DIR__.'/lib.php';
session_open();
?>
<br />
<div class="private p-2 pt-3">
    <?php if ($_SESSION['logged'] ?? false): ?>
        Dernière connexion il y a <?= time() - $_SESSION['last_login'] ?>s
    <?php else: ?>
        Pas de dernière connexion
    <?php endif; ?>
</div>
<br />
