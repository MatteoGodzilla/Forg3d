<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!utenteLoggato() || getUserType()!=UserType::BUYER->value){
    header("Location: /");
}

if(!isset($_POST["ids"]) || !isset($_POST["rows"]) || !isset($_POST["quantity"])){
    header("Location: /cart.php");
}

if(sizeof($_POST["ids"]) != sizeof($_POST["rows"]) || sizeof($_POST["ids"]) != sizeof($_POST["quantity"])){
    header("Location: /cart.php");
}

$email = getSessionEmail();

try{
    $connection->begin_transaction();
    for($i=0;$i<sizeof($_POST["ids"]);$i=$i+1){
        //parametri
        $idCart = $_POST["rows"][$i];
        $quantity = min($_POST["quantity"][$i], 9999);
        $idVariant = $_POST["ids"][$i];

        //4 query per elemento,ottenere info,una per eliminare il carrello,una per aggiungere l'ordine e una per l info ordine 


        //query 0 (ottieni emailVenditore)
        $getInfo = "SELECT Prodotto.emailVenditore as email , Variante.prezzo as prezzo  FROM Prodotto INNER JOIN Variante ON
        Prodotto.id = Variante.idProdotto INNER JOIN Carrello ON Carrello.idVariante = Variante.id WHERE 
        Carrello.id = ? AND Carrello.emailCompratore=?";
        $stmt = mysqli_prepare($connection, $getInfo);
        mysqli_stmt_bind_param($stmt,"is",$idCart,$email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $sellerEmail = "";
        $price = 0;
        if (!empty($rows)) {  
            $sellerEmail = $rows[0]["email"];
            $price = $rows[0]["prezzo"];
        }
        else{
            $connection->rollback();
            header("Location: /cart.php");
            exit();
        }
    
        //query 1
        $delete_cart_row ="DELETE FROM Carrello WHERE id=?";
        $stmt = mysqli_prepare($connection, $delete_cart_row);
        mysqli_stmt_bind_param($stmt,"i", $idCart);
        mysqli_stmt_execute($stmt);

        //query 2
        $insert_order ="INSERT INTO Ordine(emailCompratore,emailVenditore,stato) VALUES(?,?,0)";
        $stmt = mysqli_prepare($connection, $insert_order);
        mysqli_stmt_bind_param($stmt,"ss", $email,$sellerEmail);
        mysqli_stmt_execute($stmt);

        $idOrdine = mysqli_insert_id($connection);

        //query 3
        $insert_order_info ="INSERT INTO InfoOrdine(idOrdine,idVariante,prezzo,quantita) VALUES(?,?,?,?)";
        $stmt = mysqli_prepare($connection, $insert_order_info);
        mysqli_stmt_bind_param($stmt,"iiii", $idOrdine,$idVariant,$price,$quantity);
        mysqli_stmt_execute($stmt);
    }
    $connection->commit();
}
catch(mysqli_sql_exception $exception){
    echo($exception);
    $connection->rollback();
    exit();
}

header("Location: /buyersOrders.php")
?>
