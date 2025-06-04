<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!utenteLoggato() || getUserType()!=UserType::BUYER->value){
    header("Location: /");
}

if(!isset($_POST["choice"])){
    header("Location: /");
}

$quantity = 1;
if(isset($_POST["quantity"])){
    $quantity = $_POST["quantity"];
}


$idVariante = $_POST["choice"];


//ottieni la variante
$query_variante = "SELECT * FROM Variante WHERE Variante.id=?";
$stmt = mysqli_prepare($connection, $query_variante);
mysqli_stmt_bind_param($stmt,"i",$idVariante);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(sizeof($rows)==0){
    //variante inesistente, annullare
    header("Location: ");
    exit();
}

$variant = $rows[0];
//aggiungi riga
$query_cart = "INSERT INTO Carrello(emailCompratore,idVariante,quantita) VALUES(?,?,?)";
$stmt = mysqli_prepare($connection, $query_cart);
mysqli_stmt_bind_param($stmt,"sii",getSessionEmail(),$idVariante,$quantity);
mysqli_stmt_execute($stmt);
header("Location: /cart.php");
?>