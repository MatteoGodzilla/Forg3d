<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/login.css" />
        <link rel="stylesheet" href="./css/popups.css" />
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
            <h2>Login admin</h2>
        <?php } else { ?>
            <h2>Login</h2>
        <?php } ?>

		<?php
			require_once("components/login.php");
			generateLoginForm($isAdmin);
        ?>

        
        <?php if($isAdmin){ ?>
            <a href="./register.php?isAdmin=true">Registrati</a>
        <?php } else { ?>
            <a href="./register.php">Registrati</a>
            <script src="./js/chooserButton.js"></script>
        <?php } ?>
        <script src="./js/validateLogin.js"></script>

        <?php if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                include_once("./components/popups.php");
                include_once("./../php/constants.php");
                create_popup($_GET["message"],$_GET["messageType"]);
            } 
         ?>
	</body>


</html>
