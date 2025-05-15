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
if(!isset($_GET["email"])){
    header("Location: ./adminHome.php");
    exit();
}

$email = $_GET["email"];
$query = "UPDATE Segnalazione INNER JOIN SegnalazioneVenditore ON Segnalazione.id = SegnalazioneVenditore.idSegnalazione SET ispezionata=1 WHERE emailVenditore=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"s",$_GET["email"]);
mysqli_stmt_execute($stmt);

header("Location: ../checkSellerReportDetail.php?email=".$email);
exit();

?>

