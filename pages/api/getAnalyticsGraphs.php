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

    if(getUserType() == UserType::BUYER->value){

    }
    else if(getUserType() == UserType::SELLER->value){
        //revenue graph
        $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y') AS year,
        COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
        INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? Order by year";
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m-%d') AS day,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? Order by day";
            }
            if($_GET["limit"]==="month"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 AS week,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? 
                  AND YEAR(Ordine.dataCreazione) = YEAR(CURDATE())
                    GROUP BY month, week
                    ORDER BY month, week";
            }
            if($_GET["limit"]==="year"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? Order by month";
            }
        }

        $stmt = mysqli_prepare($connection, $revenue);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Storico vendite:"] = $rows;
    }
    echo json_encode($analytics);
?>