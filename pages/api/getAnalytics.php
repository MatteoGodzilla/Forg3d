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
    $time_constraint = "0";
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
        $money_spent = "SELECT CAST(SUM(prezzo*quantita)/100 AS DECIMAL(10,2)) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailCompratore = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_spent);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro speso:"] = $row[0]["Tot"]."€";

        //reviews
        $count_reviews = "SELECT COUNT(id) as Tot FROM Recensione WHERE email = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $count_reviews);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Recensioni scritte:"] = $row[0]["Tot"];

        //followed sellers
        $count_followers = "SELECT COUNT(emailVenditore) as Tot FROM Follow WHERE emailCompratore = ?";
        $stmt = mysqli_prepare($connection, $count_followers);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Venditori seguiti:"] = $row[0]["Tot"];
    }
    else if(getUserType() == UserType::SELLER->value){

        //followers
        $count_followers = "SELECT COUNT(emailCompratore) as Tot FROM Follow WHERE emailVenditore = ?";
        $stmt = mysqli_prepare($connection, $count_followers);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["i tuoi followers:"] = $row[0]["Tot"];

        //Total revenue
        $money_earn = "SELECT COALESCE(CAST(SUM(prezzo*quantita)/100 AS DECIMAL(10,2)),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailVenditore = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_earn);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro ricavato:"] = $row[0]["Tot"]."€";

        //Total products sold
        $products_sell = "SELECT COALESCE(SUM(quantita),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailVenditore = ? AND dataCreazione>=".$time_constraint;
        $stmt = mysqli_prepare($connection, $products_sell);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Prodotti Venduti:"] = $row[0]["Tot"]; 

        //Most sold product
        $most_sold = "SELECT COALESCE(SUM(quantita),0) as Tot,Prodotto.Nome as Nome,Prodotto.id AS pId FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        INNER JOIN Variante ON InfoOrdine.idVariante = Variante.id INNER JOIN Prodotto ON Variante.idProdotto = Prodotto.id 
        WHERE Prodotto.emailVenditore = ? AND dataCreazione>=".$time_constraint." GROUP BY pId ORDER BY Tot";
        $stmt = mysqli_prepare($connection, $most_sold);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(sizeof($row)>0){
            $analytics["Prodotti più venduto:"] = $row[0]["Nome"]."(".$row[0]["Tot"].")";
        }
        else{
            $analytics["Prodotti più venduto:"] = "Nessuno";
        }

        //Most revenue product
        $most_sold = "SELECT COALESCE(CAST(SUM(InfoOrdine.quantita*InfoOrdine.prezzo)/100 AS DECIMAL(10,2)),0) as Tot,Prodotto.Nome as Nome,Prodotto.id AS pId FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        INNER JOIN Variante ON InfoOrdine.idVariante = Variante.id INNER JOIN Prodotto ON Variante.idProdotto = Prodotto.id 
        WHERE Prodotto.emailVenditore = ? AND dataCreazione>=".$time_constraint." GROUP BY pId ORDER BY Tot";
        $stmt = mysqli_prepare($connection, $most_sold);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(sizeof($row)>0){
            $analytics["Prodotto che ha dato più profitto:"] = $row[0]["Nome"]."(".$row[0]["Tot"].")"; 
        }
        else{
            $analytics["Prodotto che ha dato più profitto:"] = "Nessuno";
        }
    }
    echo json_encode($analytics);
?>