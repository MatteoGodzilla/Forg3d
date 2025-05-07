<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

#non autorizzato
if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
    header("Location: ");
    exit();
}



#campi mancanti
if(!isset($_POST["nome"]) || !isset($_POST["tipo"]) || !isset($_POST["colore"])){
    header("Location: /sellerHome.php");
    exit();
}


#fields
$email = getSessionEmail();
$nome = $_POST["nome"];
$hex = str_replace("#", "", $_POST["colore"]);
$tipo = $_POST["tipo"];

//Edit
if(!isset($_GET["id"])){
    $query = "INSERT INTO Materiale(id,idVenditore,nomeColore,hexColore,tipologia) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"issss", $_GET["id"],$email,$nome,$hex,$tipo);
    mysqli_stmt_execute($stmt);
}
//Add
else{
    $query = "UPDATE  Materiale SET nomeColore=?, hexColore=?, tipologia=? WHERE  id=? AND idVenditore=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"sssis",$nome,$hex,$tipo,$_GET["id"],$email);
    mysqli_stmt_execute($stmt);
}
header("Location: /sellerHome.php");
?>