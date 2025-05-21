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
if(!isset($_GET["email"]) || !isset($_GET["newStatus"])){
    header("Location: /adminHome.php");
    exit();
}



$email = $_GET["email"];
$query = "UPDATE Venditore SET stato=? WHERE emailUtente=?";
if($_GET["newStatus"]==1 || $_GET["newStatus"]==2){
    $query = $query." AND stato = 0"; // to accept or deny a request the user must be on pending
}

try{
    //all reports must be used as read, so this is a transaction
    $connection->begin_transaction();
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"is",$_GET["newStatus"], $_GET["email"]);
    mysqli_stmt_execute($stmt);
    
    //second query: update reports
    if($_GET["newStatus"]==3){
        $query = "UPDATE Segnalazione INNER JOIN SegnalazioneVenditore ON Segnalazione.id = SegnalazioneVenditore.idSegnalazione SET Segnalazione.ispezionata=1 WHERE emailVenditore=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt,"s",$_GET["email"]);
        mysqli_stmt_execute($stmt);
        $connection->commit();
        header("Location: ../checkSellerReportDetail.php?email=".$email);
        exit();
    }
    $connection->commit();
    header("Location: ../checkAdmissionRequests.php");
    exit();
    
}catch(mysqli_sql_exception $exception){
    $connection->rollback();
    header("Location: ../adminHome.php?email=");
}


?>