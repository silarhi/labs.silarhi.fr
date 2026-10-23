<?php // sidebar.php
require __DIR__.'/lib.php';

fragment('/sidebar.php', 'public', cache_for(20), static function (): void { ?>
    <h3 class="mb-1">Widgets publics</h3>
    <p class="text-secondary mb-0">Le même pour tout le monde, régénéré toutes les 20 secondes.</p>
<?php });
