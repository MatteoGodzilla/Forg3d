<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Registrazione</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/registerForm.css" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/chooserButton.css" />
    
    </head>
	<body>
        <?php
            require_once("components/header.php");
            require_once("../php/session.php");
            create_header();
        ?>

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
        <script src="./js/darkMode.js" ></script>
	</body>
</html>
