<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

$join = "INNER JOIN SegnalazioneVenditore ON Segnalazione.Id = SegnalazioneVenditore.idSegnalazione";

if(isset($_GET["Products"])){
    $join = "INNER JOIN SegnalazioneProdotto ON Segnalazione.id = SegnalazioneProdotto.idSegnalazione INNER JOIN Prodotto ON Prodotto.id = SegnalazioneProdotto.idProdotto";
}

#query (seleziona reports ispezionati)
$query = "SELECT * FROM Segnalazione ".$join." WHERE ispezionata=1 ORDER BY Segnalazione.ultimaModifica";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Storico Segnalazioni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet"  href="./css/header.css" />
    <link rel="stylesheet"  href="./css/reportDetail.css" />

</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
    ?>

    <h2>Storico</h2>

    <?php 
        require_once("components/storicalReport.php");
        foreach($reports as $report){
            storicalReport($report,isset($_GET["Products"]));
        }
    ?>
</body>
</html>