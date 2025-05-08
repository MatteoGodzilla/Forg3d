<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

#non autorizzato
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: ");
    exit();
}

#id non dato
if(!isset($_GET["email"]) || !isset($_GET["accept"])){
    header("Location: /adminHome.php");
    exit();
}



$email = getSessionEmail();
$query = "UPDATE Venditore SET stato=".($_GET["accept"]=="true"? "1":"2")." WHERE emailUtente=? AND stato=0";
#execute
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"s", $_GET["email"]);
mysqli_stmt_execute($stmt);
header("Location: /adminHome.php");

?>