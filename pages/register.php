<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Registrazione</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/register.css" />
    </head>
	<body>
        <header>
            <h1>Forg3d</h1>
        </header>
        <h2>Registrazione</h2>
		<?php
			//require_once("../php/db.php");
			require_once("components/register.php");

            generateRegisterForm(false);
			//if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
			//	generateRegisterForm(true);
			//} else {
			//	generateRegisterForm(false);
			//}
        ?>
        <a href="login.php">Login</a>
        <script src="./js/chooserButton.js" ></script>
        <script src="./js/validateRegistration.js" ></script>
	</body>
</html>
