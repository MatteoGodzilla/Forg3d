<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

//Gli admin non possono mettere recensioni
if(!utenteLoggato() || getUserType() === UserType::ADMIN->value){
    header("Location: /");
    exit();
}

$email = getSessionEmail();

//campi mancanti
if(!isset($_POST["idProduct"]) || !isset($_POST["score"]) || !isset($_POST["title"]) || !isset($_POST["review"])){
    header("Location: /");
    exit();
}

$id = $_POST["idProduct"];
$score = $_POST["score"];
$title = $_POST["title"];
$review = $_POST["review"];

$query = "INSERT INTO Recensione(email, idProdotto, valutazione, titolo, testo) VALUES (?,?,?,?,?); ";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "siiss", $email, $id, $score, $title, $review);
mysqli_stmt_execute($stmt);

header("Location: /product.php?id=".$id);
?>
