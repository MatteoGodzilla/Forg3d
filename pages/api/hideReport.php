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

//rollback URL
$rollback="/adminHome.php";
if(isset($_GET["product"])){
    $rollback="/checkProductReportDetail.php?id=".$_GET["product"];
}
if(isset($_GET["seller"])){
    $rollback="/checkSellerReportDetail.php?email=".$_GET["seller"];
}



$id = $_GET["id"];
$query = "UPDATE Segnalazione SET ispezionata=1 WHERE id=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
header("Location: ".$rollback);
?>