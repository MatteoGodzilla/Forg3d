<?php
session_start();
require_once("../php/constants.php");
require_once("../php/session.php");
?>
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
			<?php if (utenteLoggato()): ?>
				<span>
					<?php
						$email = getSessionEmail();
						echo htmlspecialchars($email);
					?>
					<a href="./api/handleLogout.php">Logout</a>
				</span>
			<?php else: ?>
				<a href="./login.php">Login</a>
			<?php endif; ?>
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
