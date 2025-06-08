<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!utenteLoggato()){
    header("Location: /login.php");
}

if(getUserType()==UserType::ADMIN->value){
    header("Location: /adminHome.php");
}


if(!isset($_GET["id"])){
    header("Location: ".(getUserType()==UserType::BUYER->value? "/buyersOrders.php" :  "/sellerOrders.php" ));
}


$email = getSessionEmail();
$id = $_GET["id"];

//ottieni lo stato corrente:
$query = "SELECT id,stato FROM Ordine WHERE id=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(sizeof($rows)>0){
    $stato = $rows[0]["stato"];

    if($stato == 0){
        $query = "UPDATE Ordine SET stato = 1 WHERE id= ? AND emailVenditore=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt,"is",$id,$email);
        mysqli_stmt_execute($stmt);
    }
    else{
        //if stato = 2 ,allora non dovremmo essere qui,ma visto che la query non fa niente in tale casistica mi risparmio un else lol
        $query = "UPDATE Ordine SET stato = 2 WHERE id= ? AND emailCompratore=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt,"is",$id,$email);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: ".(getUserType()==UserType::BUYER->value? "/buyersOrders.php" :  "/sellerOrders.php" ));
?>