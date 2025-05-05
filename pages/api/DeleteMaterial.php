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
$query = "DELETE FROM Materiale WHERE id=? AND idVenditore=?";
#execute
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"is", $_GET["id"],$email);
mysqli_stmt_execute($stmt);
header("Location: /sellerHome.php");

?>