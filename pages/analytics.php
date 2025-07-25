<?php
    require_once("../php/db.php");
    require_once("../php/session.php");
    require_once("../php/feedback.php");
    require_once("../php/constants.php");
    session_start();

    if(!utenteLoggato() || getUserType()==UserType::BUYER->value){
        header("Location: /login.php");
    }
    $type = getUserType();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Analytics</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/buttons.css" />
        <link rel="stylesheet" href="./css/popups.css" />
        <?php if($type == UserType::BUYER->value) { ?>
           <link rel="stylesheet" href="./css/analytics-buyer.css" /> 
       <?php } else { ?>
            <link rel="stylesheet" href="./css/analytics.css" />
        <?php } ?>
    </head>
	<body>
        <?php
            require_once("components/header.php");
            require_once("../php/session.php");
            create_header();

        ?>
            <main>
                <div id="graphs"></div>
            </main>
        <aside>
            <h3>Timeframe</h3>
            <div id="choices">
                <label><input type = "radio" checked name="limit" value="week" >Ultima settimana</label>
                <label><input type = "radio" name="limit" value="month" >Ultimo mese</label>
                <label><input type = "radio" name="limit" value="year" >Ultimo Anno</label>
                <label><input type = "radio" name="limit" value="always" >Di sempre</label>

            </div>

            <h3>Statistiche</h3>
            <div id="stats"></div>
        </aside>

        <?php
            if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                include_once("./components/popups.php");
                include_once("./../php/constants.php");
                create_popup($_GET["message"],$_GET["messageType"]);
            }
        ?>
	</body>
    <script src="./js/darkMode.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/loadAnalytics.js"></script>
</html>

