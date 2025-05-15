<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
        header("Location: /login.php?isAdmin=1");
    }


    #Query Approvazioni
    $query_richieste = "SELECT COUNT(emailUtente) AS total FROM Venditore WHERE stato=0";
    $stmt = mysqli_prepare($connection, $query_richieste);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $pendingRequests = $row[0]["total"]; 

    #Query Segnalazione venditori
    $query_segnalazioni_venditore = "SELECT COUNT(id) AS total FROM Segnalazione INNER JOIN SegnalazioneVenditore 
    ON Segnalazione.id= SegnalazioneVenditore.idSegnalazione Where ispezionata = 0";
    $stmt = mysqli_prepare($connection, $query_segnalazioni_venditore);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $sellerReports = $row[0]["total"]; 

    #Segnalazione prodotti
    $query_segnalazioni_venditore = "SELECT COUNT(id) AS total FROM Segnalazione INNER JOIN SegnalazioneProdotto
    ON Segnalazione.id= SegnalazioneProdotto.idSegnalazione Where ispezionata = 0";   
    $stmt = mysqli_prepare($connection, $query_segnalazioni_venditore);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $productReports = $row[0]["total"]; 

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/adminHome.css" />
    <link ref="stylesheet"  href="./css/header.css">

</head>
<body>

    <?php
        require_once("components/header.php");
        create_header();
    ?>
    <h2>Bentornato</h2>
    <?php 
        require_once("components/adminCommands.php");
        adminInterface($pendingRequests,$sellerReports,$productReports);
    ?>
</body>
</html>