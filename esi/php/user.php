<?php //user.php
require __DIR__.'/lib.php';
$session = session_read();
?>
<br />
<div class="private p-2 pt-3">
    <?php if ($session['logged'] ?? false): ?>
        Dernière connexion il y a <?= time() - $session['last_login'] ?>s
    <?php else: ?>
        Pas de dernière connexion
    <?php endif; ?>
</div>
<br />
