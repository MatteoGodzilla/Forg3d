<?php
    require_once("../php/db.php");
    require_once("../php/session.php");
    require_once("../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Analytics</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/buttons.css" />
        <link rel="stylesheet" href="./css/popups.css" />
        <link rel="stylesheet" href="./css/analytics.css" />
    </head>
	<body>
        <?php
            require_once("components/header.php");
            require_once("../php/session.php");
            create_header();

        ?>

        <div id="choices">
            <label><input type = "radio" checked  name="limit" value="always" >Di sempre</label>
            <label><input type = "radio" name="limit" value="year" >Ultimo Anno</label>
            <label><input type = "radio" name="limit" value="month" >Ultimo mese</label>
            <label><input type = "radio" name="limit" value="week" >Ultima settimana</label>
        </div>
                <h2>Grafici</h2>
        <div id="graphs">
            
        </div>
                <h2>Statistiche</h2>
        <div id="stats">
            
        </div>

        <?php
            if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                include_once("./components/popups.php");
                include_once("./../php/constants.php");
                create_popup($_GET["message"],$_GET["messageType"]);
            }
        ?>
	</body>
    <script src="./js/darkMode.js"></script>
    <script src="./js/loadAnalytics.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</html>

