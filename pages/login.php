<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/login.css" />
    </head>
    <body>
        <header>
            <h1>Forg3d</h1>
        </header>
        <h2>Login</h2>
		<?php
			require_once("components/login.php");

			generateLoginForm(false);
			//if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
			//	generateLoginForm(true);
			//} else {
			//	generateLoginForm(false);
			//}
        ?>
        <a href="./register.php">Registrati</a>
        <script src="./js/chooserButton.js"></script>
        <script src="./js/validateLogin.js"></script>
	</body>
</html>
