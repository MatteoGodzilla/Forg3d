<?php
    require_once("../../php/db.php");
    require_once("../../php/session.php");
    require_once("../../php/feedback.php");
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
    echo json_encode($analytics);
?>