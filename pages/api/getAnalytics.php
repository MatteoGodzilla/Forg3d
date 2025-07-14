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
    $time_constraint = "652.985"; //to go below this,you would need to have assisted to the french revolution,i choose this number because is super goofy and it can be increased if necessary in the next 200 years
    if(isset($_GET["limit"])){
        if($_GET["limit"]==="week"){
            $time_constraint = "6";
        }
        if($_GET["limit"]==="month"){
            $time_constraint = "30";
        }
        if($_GET["limit"]==="year"){
            $time_constraint = "365";
        }
    }

    //Buyer analytics: number of purchases,money spent,number of reports and of reviews made,number of sellers being followed
    if(getUserType() == UserType::BUYER->value){
        //purchases
        $count_purchases = "SELECT COUNT(id) as Tot FROM Ordine WHERE emailCompratore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $count_purchases);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Acquisti totali effettuati:"] = $row[0]["Tot"]; 

        //money spent
        $money_spent = "SELECT CAST(SUM(prezzo*quantita)/100 AS DECIMAL(10,2)) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailCompratore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_spent);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro speso:"] = $row[0]["Tot"]."€";
    }
    else if(getUserType() == UserType::SELLER->value){

        //Total revenue
        $money_earn = "SELECT COALESCE(CAST(SUM(prezzo*quantita)/100 AS DECIMAL(10,2)),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailVenditore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_earn);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro ricavato:"] = $row[0]["Tot"]."€";

        //Total products sold
        $products_sell = "SELECT COALESCE(SUM(quantita),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        WHERE emailVenditore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $products_sell);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Prodotti Venduti:"] = $row[0]["Tot"]; 

        //Most sold product
        $most_sold = "SELECT COALESCE(SUM(quantita),0) as Tot,Prodotto.Nome as Nome,Prodotto.id AS pId FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        INNER JOIN Variante ON InfoOrdine.idVariante = Variante.id INNER JOIN Prodotto ON Variante.idProdotto = Prodotto.id 
        WHERE Prodotto.emailVenditore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint." GROUP BY Prodotto.id ORDER BY Tot DESC";
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
        WHERE Prodotto.emailVenditore = ?  AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint." GROUP BY pId ORDER BY Tot DESC";
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
    else{
        //total registered users
        $registered_users ="SELECT COUNT(email) as Tot FROM Utente
        WHERE email NOT IN (SELECT emailUtente FROM Admin) AND email NOT IN (SELECT emailUtente FROM Venditore WHERE stato >= 2) -- solo utenti in attesa e verificati se venditori
        AND DATEDIFF(CURDATE(), Utente.dataCreazione) <= ".$time_constraint;
        $stmt = mysqli_prepare($connection, $registered_users);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $analytics["Utenti registrati"] = $row["Tot"];

        //revenue
        $money_earn = "SELECT COALESCE(CAST(SUM(prezzo*quantita)/100 AS DECIMAL(10,2)),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $money_earn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Denaro ricavato:"] = $row[0]["Tot"]."€";

        //Total products sold
        $products_sell = "SELECT COALESCE(SUM(quantita),0) as Tot FROM InfoOrdine INNER JOIN Ordine ON Ordine.id = InfoOrdine.idOrdine
        AND DATEDIFF(CURDATE(), Ordine.dataCreazione)<=".$time_constraint;
        $stmt = mysqli_prepare($connection, $products_sell);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $analytics["Prodotti Venduti:"] = $row[0]["Tot"]; 

    }
    echo json_encode($analytics);
?>