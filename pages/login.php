<?php
//nessun motivo per cui un utente loggato dovrebbe finire qui
session_start();
require_once("../php/db.php");
require_once("../php/session.php");
if(utenteLoggato()){
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/popups.css" />
        <link rel="stylesheet" href="./css/loginForm.css" />
    </head>
    <body>
        	<?php 
				include_once("./components/header.php");
                include_once("./../php/session.php");
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
