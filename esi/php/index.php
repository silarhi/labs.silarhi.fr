<?php // index.php
	header('Surrogate-Control: abc=ESI/1.0');
	header("X-Reverse-Proxy-TTL: 10");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    	.public { border: 2px dashed green; }
    	.private { border: 2px dashed red; }
    	.cached { border-style: solid; }
    </style>
	<title>Ma super page</title>
</head>
<body class="public cached p-2">
	<esi:include src="/menu.php"/>
  	<br />
	<div class="container">
		<esi:include src="/time.php"/>
		<div class="row">
			<div class="col-md-8 col-lg-9">
				<div class="p-2">
					<h1>Page de contenu public</h1>
					<p class="lead">Mise en cache le <span class="badge badge-secondary"><?= date('d/m/Y H:i:s') ?></span> (10s)</p>
				</div>
			</div>
			<div class="col-md-4 col-lg-3">
				<esi:include src="/user.php"/>
				<esi:include src="/sidebar.php"/>
			</div>
		</div>
	</div>
</body>
</html>