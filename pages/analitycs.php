<?php
    require_once("../php/db.php");
    require_once("../php/session.php");
    require_once("../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
    $email = getSessionEmail();

    //Buyer analytics: number of purchases,money spent,number of reports and of reviews made,number of sellers being followed (Maybe list them?)

    //Seller analytics: number of sales made,top X products for sales,total number of money made, number of followers.

    //Admin analytics: number of registered buyers and sellers,number of reports and bans.

    //(Span out between week,month and year?)

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Analytics</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/buttons.css" />
        <link rel="stylesheet" href="./css/popups.css" />
        <link rel="stylesheet" href="./css/registerForm.css" />
    </head>
	<body>
        <?php
            require_once("components/header.php");
            create_header();
            require_once("../php/session.php");
            include_once("./../php/constants.php");
            include_once("./components/popups.php");


            if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                create_popup($_GET["message"],$_GET["messageType"]);
            } 

        ?>
	</body>
    <script src="./js/darkMode.js"></script>
</html>

