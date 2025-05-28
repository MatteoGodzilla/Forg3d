<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!utenteLoggato() || getUserType()!=UserType::BUYER->value){
    header("Location: /");
}

if(!isset($_GET["id"])){
    header("Location: /");
}

$idRiga = $_GET["id"];


//ottieni la variante
$query_variante = "DELETE FROM Carrello WHERE id=? AND emailCompratore=?";
$stmt = mysqli_prepare($connection, $query_variante);
mysqli_stmt_bind_param($stmt,"is",$idRiga,getSessionEmail());
mysqli_stmt_execute($stmt);

header("Location: /cart.php");
?>