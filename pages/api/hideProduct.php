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
if(!isset($_GET["id"])){
    header("Location: /adminHome.php");
    exit();
}



$id = $_GET["id"];
$query = "UPDATE Prodotto SET visibile=0 WHERE id=?";

try{
    //all reports must be used as read, so this is a transaction
    $connection->begin_transaction();
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    
    //second query: update reports
    $query = "UPDATE Segnalazione INNER JOIN SegnalazioneProdotto ON Segnalazione.id = SegnalazioneProdotto.idSegnalazione
    SET Segnalazione.ispezionata=1 WHERE SegnalazioneProdotto.idProdotto=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
    $connection->commit();
    header("Location: ../checkProductReportDetail.php?id=".$id);
    exit();
    
    $connection->commit();
    header("Location: ../adminHome.php");
    exit();
    
}catch(mysqli_sql_exception $exception){
    $connection->rollback();
    header("Location: ../adminHome.php?email=");
}


?>