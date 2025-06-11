<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

//Gli admin non possono rispondere
if(!utenteLoggato() || getUserType() === UserType::ADMIN->value){
    header("Location: /");
    exit();
}

$email = getSessionEmail();

//campi mancanti
if(!isset($_POST["idProduct"]) || !isset($_POST["idParent"]) || !isset($_POST["reply"])){
    header("Location: /");
    exit();
}

$productId = $_POST["idProduct"];
$parentId = $_POST["idParent"];
$score = 0;
//Not shown to the end user
$title = "Reply to: " . $parentId;
$review = $_POST["reply"];

$query = "INSERT INTO Recensione(email, idProdotto, valutazione, titolo, testo, inRispostaA) VALUES (?,?,?,?,?,?); ";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "siisss", $email, $productId, $score, $title, $review, $parentId);
mysqli_stmt_execute($stmt);

header("Location: /product.php?id=".$productId);
?>
