<?php //time.php ?>
<div class="text-center p-2 public">
	<p class="lead text-center">Heure serveur : <span class="badge badge-secondary"><?= date('d/m/Y H:i:s') ?></span> (pas de cache)</p>
	<div class="row">
		<div class="col-3"><span class="public p-1">Public</span></div>
		<div class="col-3"><span class="public cached p-1">Public + cache</span></div>
		<div class="col-3"><span class="private p-1">Privé</span></div>
		<div class="col-3"><span class="private cached p-1">Privé + cache</span></div>
	</div>
</div>