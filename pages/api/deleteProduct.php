<?php 
require_once("../../php/db.php");
require_once("../../php/session.php");
session_start();

//Reindirizza se l'utente non è loggato
if(!utenteLoggato() || getUserType() != UserType::SELLER->value){
    header("Location: /");
    exit();
}

//Reindirizza alla home venditore se non è stato passato l'id del prodotto
if(!isset($_GET) || !isset($_GET["id"])){
    header("Location: /sellerHome.php");
    exit();
}
 
$id = $_GET["id"];
$email = getSessionEmail();
$query = "DELETE FROM Prodotto WHERE id = ? AND emailVenditore = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "is", $id, $email);
mysqli_stmt_execute($stmt);
header("Location: /sellerHome.php");


?>
