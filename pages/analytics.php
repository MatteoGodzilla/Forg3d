<?php
    require_once("../php/db.php");
    require_once("../php/session.php");
    require_once("../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
    $email = getSessionEmail();
    $analytics = array();

    //check if a limit i specified
    $time_constraint = " 0";
    if(isset($_GET["limit"])){
        if($_GET["limit"]==="week"){
            $time_constraint = " DATE_SUB(NOW(),INTERVAL 1 WEEK)";
        }
        if($_GET["limit"]==="month"){
            $time_constraint = " DATE_SUB(NOW(),INTERVAL 1 MONTH)";
        }
        if($_GET["limit"]==="year"){
            $time_constraint = " DATE_SUB(NOW(),INTERVAL 1 YEAR)";
        }
    }

    //Buyer analytics: number of purchases,money spent,number of reports and of reviews made,number of sellers being followed
    if(getUserType() == UserType::BUYER->value){
        //purchases
        $count_purchases = "SELECT COUNT(id) as Tot FROM Ordine WHERE emailCompratore = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $count_purchases);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Acquisti totali effettuati:"] = $row[0]["Tot"]; 

        //money spent
        $money_spent = "SELECT COUNT(prezzo*quantita) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailCompratore = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_spent);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro speso:"] = $row[0]["Tot"];

        //reports
        $count_reports = "SELECT COUNT(id) as Tot FROM Segnalazione WHERE emailSegnalatore = ? AND ultimaModifica>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $count_reports);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Segnalazioni effettuate:"] = $row[0]["Tot"]; 

        //reviews
        $count_reviews = "SELECT COUNT(id) as Tot FROM Recensione WHERE email = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $count_reviews);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Recensioni scritte:"] = $row[0]["Tot"]; 

        //followed sellers
    }
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
        <link rel="stylesheet" href="./css/analytics.css" />
    </head>
	<body>
        <?php
            require_once("components/header.php");
            require_once("../php/session.php");
            create_header();

        ?>
        <h2>Statistiche</h2>
        <?php

            require_once("components/analyticsInfo.php");
            analytics_info($analytics);

            if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                include_once("./components/popups.php");
                include_once("./../php/constants.php");
                create_popup($_GET["message"],$_GET["messageType"]);
            } 
        ?>
	</body>
    <script src="./js/darkMode.js"></script>
</html>

