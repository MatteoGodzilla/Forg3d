<!DOCTYPE html>
<html lang="it">
	<head>
		<title>Forg3d Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css/home.css">
	</head>
	<body>
		<header>
			<h1>Forg3d</h1>
			<a href="./login.php">Login</a>
		</header>
		<input type="search" name="search" id="search" placeholder="Ricerca">
		<?php
			require_once("./components/homeProductPlaceholder.php");
			for ($i=1; $i <= 100; $i++) {
				generateProductPlaceholder($i);
			}
		?>
	</body>
</html>
