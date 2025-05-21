<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

if(!isset($_GET["email"])){
    header("Location: checkSellerReports.php");
}

$email=$_GET["email"];

#query (seleziona reports non ispezionati sullo specifico venditore, stato = 0)
$query = "SELECT id,emailSegnalatore,emailVenditore,motivo,ultimaModifica FROM Segnalazione INNER JOIN SegnalazioneVenditore ON 
Segnalazione.id = SegnalazioneVenditore.idSegnalazione WHERE emailVenditore=? AND ispezionata = 0 ORDER BY ultimaModifica LIMIT 50";


$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

#query per le info venditore
$query = "SELECT * FROM Venditore INNER JOIN Utente ON Utente.email = Venditore.emailUtente WHERE emailUtente=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

//user not found
if(sizeof($rows)==0){
    header("Location: checkSellerReports.php");
}

$user = $rows[0];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/reportDetail.css" />
</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
    ?>

    <?php 
        require_once("components/sellerInfo.php");
        sellerInfo($user);
    ?>

    <?php 
        require_once("components/sellerReportDetail.php");
        foreach($reports as $report){
            sellerReportDetail($report);
        }
    ?>
</body>
</html>