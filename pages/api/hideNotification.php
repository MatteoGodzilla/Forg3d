<?php
session_start();
include_once("../../php/db.php");
include_once("../../php/session.php");
require_once("../../php/feedback.php");


if(!utenteLoggato()){
    header("Location: ");
}


$email = getSessionEmail();

if(!isset($_GET["id"])){
    //global wipe
    $query = "UPDATE NotificaLetta set visibile = 0 WHERE destinatario = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"s", $email);
    mysqli_stmt_execute($stmt);
}
else{
    $query = "UPDATE NotificaLetta set visibile = 0 WHERE destinatario = ? AND idNotifica = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"si", $email,$_GET["id"]);
    mysqli_stmt_execute($stmt);
}

header("Location: /notifications.php");
?>

