<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

#non autorizzato
if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
    header("Location: ");
    exit();
}

#id non dato
if(!isset($_GET["id"])){
    header("Location: /sellerHome.php");
    exit();
}

$email = getSessionEmail();
//controlla se il prodotto ha una variante
$check_query = "SELECT COUNT(idProdotto) AS total FROM Variante WHERE idMateriale=?";
$stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($stmt,"i", $_GET["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count = mysqli_fetch_assoc($result);

if($count["total"]==0){
    $query = "DELETE FROM Materiale WHERE id=? AND idVenditore=?";
    #execute
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"is", $_GET["id"],$email);
    mysqli_stmt_execute($stmt);
}
else{
    $query = "UPDATE Materiale SET visibile = 0 WHERE id=?";
    #execute
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i", $_GET["id"]);
    mysqli_stmt_execute($stmt);
}

$query = "UPDATE Variante SET visibile = 0 WHERE idMateriale = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);
mysqli_stmt_execute($stmt);

header("Location: /sellerHome.php");

?>
