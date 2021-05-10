<?php //sidebar.php
header("X-Reverse-Proxy-TTL: 20");
?>
<div class="public cached p-2">
    <h1>Widgets publics</h1>
    <p class="lead">
        Mise en cache le <span class="badge bg-secondary"><?= date('d/m/Y H:i:s') ?></span> (20s)
    </p>
</div>
