<?php //menu.php
	session_start();
	header("X-Reverse-Proxy-TTL: 60");
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light private cached">
	<div class="container">
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<?php if (isset($_SESSION['logged'])): ?>
					<li class="navbar-text">
						<span class="badge badge-success">Connecté</span>
					</li>
					<li class="nav-item">
					    <a class="nav-link" href="logout.php">Déconnexion</a>
					</li>
				<?php else: ?>
					<li class="navbar-text">
			    		<span class="badge badge-danger">Anonyme</span>
					</li>
					<li class="nav-item">
					    <a class="nav-link" href="login.php">Connexion</a>
					</li>
				<?php endif; ?>
			</ul>
			<span class="navbar-text">
	     		Mise en cache le <span class="badge badge-secondary"><?= date('d/m/Y H:i:s') ?></span> (60s)
		    </span>
		</div>
	</div>
</nav>
