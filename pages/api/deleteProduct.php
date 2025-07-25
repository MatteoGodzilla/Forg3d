<?php 
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("notificationUtils.php");
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

//Check that the product is not banned
$query_check = "SELECT visibile FROM Prodotto WHERE id = ?";
$stmt = mysqli_prepare($connection, $query_check);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$check = mysqli_fetch_assoc($result);

if($check["visibile"] != 0){
    //Fake removal in database
    $query_remove = "UPDATE Prodotto SET visibile=0 WHERE id = ? AND emailVenditore = ?";
    $stmt = mysqli_prepare($connection, $query_remove);
    mysqli_stmt_bind_param($stmt, "is", $id, $email);
    mysqli_stmt_execute($stmt);
    //Send notification of removal, if it was visible
    if($check["visibile"] == 2){ 
        sendRemovedProduct($connection, $email);
    }
}

header("Location: /sellerHome.php");


?>
