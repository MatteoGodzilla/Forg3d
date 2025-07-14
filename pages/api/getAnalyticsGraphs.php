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

    if(getUserType() == UserType::SELLER->value){
        //revenue graph
        $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y') AS year,
        COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
        INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? 
        group by year Order by year";
        
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m-%d') AS day,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? and DATEDIFF(CURDATE(), Ordine.dataCreazione) <=6
                group by day
                Order by day";
            }
            if($_GET["limit"]==="month"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 AS week_number,
                CONCAT(DATE_FORMAT(Ordine.dataCreazione, '%Y-%m'),' settimana ',WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 ) AS week,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? AND DATEDIFF(CURDATE(), Ordine.dataCreazione) <=30
                    GROUP BY month, week_number
                    ORDER BY month, week_number";
            }
            if($_GET["limit"]==="year"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? AND DATEDIFF(CURDATE(), Ordine.dataCreazione) <=365
                GROUP BY month ORDER BY month";
            }
        }

        $stmt = mysqli_prepare($connection, $revenue);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Storico vendite:"] = $rows;

        //objects sold graph
        $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y') AS year,
        COALESCE(SUM(InfoOrdine.quantita),0) AS Tot From InfoOrdine
        INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? 
        group by year Order by year";
        
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m-%d') AS day,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? and DATEDIFF(CURDATE(), Ordine.dataCreazione) <=6
                group by day
                Order by day";
            }
            if($_GET["limit"]==="month"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 AS week_number,
                CONCAT(DATE_FORMAT(Ordine.dataCreazione, '%Y-%m'),' settimana ',WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 ) AS week,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? AND DATEDIFF(CURDATE(), Ordine.dataCreazione) <=30
                    GROUP BY month, week_number
                    ORDER BY month, week_number";
            }
            if($_GET["limit"]==="year"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE emailVenditore=? AND DATEDIFF(CURDATE(), Ordine.dataCreazione) <=365
                GROUP BY month ORDER BY month";
            }
        }

        $stmt = mysqli_prepare($connection, $objects_sold);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Prodotti venduti:"] = $rows;
    }
    else if(getUserType() == UserType::ADMIN->value){

        //Registered users graph
        $registered_users = "SELECT DATE_FORMAT(Utente.dataCreazione, '%Y') AS year,
        COUNT(email) as Tot FROM Utente WHERE email NOT IN (SELECT emailUtente FROM Admin) AND email NOT IN (SELECT emailUtente FROM Venditore WHERE stato >= 2)
        group by year Order by year";
        
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $registered_users = "SELECT DATE_FORMAT(Utente.dataCreazione, '%Y-%m-%d') AS day,
                COUNT(email) as Tot FROM Utente WHERE email NOT IN (SELECT emailUtente FROM Admin) AND email NOT IN (SELECT emailUtente FROM Venditore WHERE stato >= 2)
                and DATEDIFF(CURDATE(), Utente.dataCreazione) <=6
                group by day
                Order by day";
            }
            if($_GET["limit"]==="month"){
                $registered_users = "SELECT DATE_FORMAT(Utente.dataCreazione, '%Y-%m') AS month,
                WEEK(Utente.dataCreazione, 3) - WEEK(DATE_SUB(Utente.dataCreazione, INTERVAL DAY(Utente.dataCreazione) - 1 DAY), 3) + 1 AS week_number,
                CONCAT(DATE_FORMAT(Utente.dataCreazione, '%Y-%m'),' settimana ',WEEK(Utente.dataCreazione, 3) - WEEK(DATE_SUB(Utente.dataCreazione, INTERVAL DAY(Utente.dataCreazione) - 1 DAY), 3) + 1 ) AS week,
                COUNT(email) as Tot FROM Utente WHERE email NOT IN (SELECT emailUtente FROM Admin) AND email NOT IN (SELECT emailUtente FROM Venditore WHERE stato >= 2)
                AND DATEDIFF(CURDATE(), Utente.dataCreazione) <=30
                    GROUP BY month, week_number
                    ORDER BY month, week_number";
            }
            if($_GET["limit"]==="year"){
                $registered_users = "SELECT DATE_FORMAT(Utente.dataCreazione, '%Y-%m') AS month,
                COUNT(email) as Tot FROM Utente WHERE email NOT IN (SELECT emailUtente FROM Admin) AND email NOT IN (SELECT emailUtente FROM Venditore WHERE stato >= 2)
                AND  DATEDIFF(CURDATE(), Utente.dataCreazione) <=365
                GROUP BY month ORDER BY month";
            }
        }

        $stmt = mysqli_prepare($connection, $registered_users);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Storico registrazione utenti"] = $rows;

        //revenue graph
        $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y') AS year,
        COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
        INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine 
        group by year Order by year";
        
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m-%d') AS day,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE  DATEDIFF(CURDATE(), Ordine.dataCreazione) <=6
                group by day
                Order by day";
            }
            if($_GET["limit"]==="month"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 AS week_number,
                CONCAT(DATE_FORMAT(Ordine.dataCreazione, '%Y-%m'),' settimana ',WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 ) AS week,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE  DATEDIFF(CURDATE(), Ordine.dataCreazione) <=30
                    GROUP BY month, week_number
                    ORDER BY month, week_number";
            }
            if($_GET["limit"]==="year"){
                $revenue = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE  DATEDIFF(CURDATE(), Ordine.dataCreazione) <=365
                GROUP BY month ORDER BY month";
            }
        }

        $stmt = mysqli_prepare($connection, $revenue);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Storico vendite:"] = $rows;

        //objects sold graph
        $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y') AS year,
        COALESCE(SUM(InfoOrdine.quantita),0) AS Tot From InfoOrdine
        INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine 
        group by year Order by year";
        
        if(isset($_GET["limit"])){
            if($_GET["limit"]==="week"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m-%d') AS day,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE DATEDIFF(CURDATE(), Ordine.dataCreazione) <=6
                group by day
                Order by day";
            }
            if($_GET["limit"]==="month"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 AS week_number,
                CONCAT(DATE_FORMAT(Ordine.dataCreazione, '%Y-%m'),' settimana ',WEEK(Ordine.dataCreazione, 3) - WEEK(DATE_SUB(Ordine.dataCreazione, INTERVAL DAY(Ordine.dataCreazione) - 1 DAY), 3) + 1 ) AS week,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE DATEDIFF(CURDATE(), Ordine.dataCreazione) <=30
                    GROUP BY month, week_number
                    ORDER BY month, week_number";
            }
            if($_GET["limit"]==="year"){
                $objects_sold = "SELECT DATE_FORMAT(Ordine.dataCreazione, '%Y-%m') AS month,
                COALESCE(SUM(InfoOrdine.quantita),0)  AS Tot From InfoOrdine
                INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine WHERE DATEDIFF(CURDATE(), Ordine.dataCreazione) <=365
                GROUP BY month ORDER BY month";
            }
        }

        $stmt = mysqli_prepare($connection, $objects_sold);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Prodotti venduti:"] = $rows;

    }
    echo json_encode($analytics);
?>