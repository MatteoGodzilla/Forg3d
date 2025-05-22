<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

if(!isset($_GET["id"])){
    header("Location: checkSellerReports.php");
}

$id=$_GET["id"];

#query (seleziona reports non ispezionati sullo specifico venditore, stato = 0)
$query = "SELECT *,Segnalazione.ultimaModifica as lastEdit FROM Segnalazione INNER JOIN SegnalazioneProdotto ON 
Segnalazione.id = SegnalazioneProdotto.idSegnalazione 
WHERE SegnalazioneProdotto.idProdotto=? AND ispezionata = 0 ORDER BY Segnalazione.ultimaModifica";


$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

#query per le info venditore
$query = "SELECT * FROM Prodotto WHERE id=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

//user not found
if(sizeof($rows)==0){
    header("Location: checkProductsReports.php");
}

$product = $rows[0];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/reportDetail.css" />
    <link rel="stylesheet" href="./css/header.css" />
</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
    ?>

    <?php 
        require_once("components/productInfo.php");
        productInfo($product);
    ?>

    <?php 
        require_once("components/productReportDetail.php");
        foreach($reports as $report){
            productReportDetail($report);
        }
    ?>
</body>
</html>