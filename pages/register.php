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
        <?php 
			if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
                $isAdmin = true;
			} else {
                $isAdmin = false;
			}
        ?>

        <?php if($isAdmin){ ?>
            <h2>Registrazione Admin</h2>
        <?php } else { ?>
            <h2>Registrazione</h2>
        <?php } ?>

		<?php
			require_once("components/register.php");
            generateRegisterForm($isAdmin);
        ?>

        <?php if($isAdmin){ ?>
            <a href="login.php?isAdmin=true">Login</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <script src="./js/chooserButton.js" ></script>
        <?php } ?>

        <script src="./js/validateRegistration.js" ></script>
	</body>
</html>
