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
if(!isset($_GET["email"]) && !isset($_GET["id"])){
    header("Location: ./adminHome.php");
    exit();
}

if(isset($_GET["email"])){
    $email = $_GET["email"];
    $query = "UPDATE Segnalazione INNER JOIN SegnalazioneVenditore ON Segnalazione.id = SegnalazioneVenditore.idSegnalazione SET ispezionata=1 WHERE emailVenditore=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);
    header("Location: ../checkSellerReportDetail.php?email=".$email);
    exit();
}
else{
    $id = $_GET["id"];
    $query = "UPDATE Segnalazione INNER JOIN SegnalazioneProdotto ON Segnalazione.id = SegnalazioneProdotto.idSegnalazione SET ispezionata=1 WHERE idProdotto=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    header("Location: ../checkProductReportDetail.php?email=".$id);
    exit();
}



?>

