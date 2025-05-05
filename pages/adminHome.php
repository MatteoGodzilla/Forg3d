<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
        header("Location: /login.php?isAdmin=1");
    }


    #Query Approvazioni
    $query_segnalazioni_venditore = "SELECT COUNT(id) AS total FROM Venditore WHERE stato=0";
    $result = $conn->query($query_segnalazioni_venditore);
    $row = $result->fetch_assoc();
    $pendingRequests = $row["total"];

    #Query Segnalazione venditori
    $query_segnalazioni_venditore = "SELECT COUNT(id) AS total FROM Segnalazione INNER JOIN SegnalazioneVenditore 
    ON Segnalazione.Id= SegnalazioneVenditore.idSegnalazione Where ispezionata = 0";
    $result = $conn->query($query_segnalazioni_venditore);
    $row = $result->fetch_assoc();
    $sellerReports =  $row["total"];

    #Segnalazione prodotti
    $query_segnalazioni_venditore = "SELECT COUNT(id) AS total FROM Segnalazione INNER JOIN SegnalazioneProdotto
    ON Segnalazione.Id= SegnalazioneProdotto.idSegnalazione Where ispezionata = 0";    $result = $conn->query($query_segnalazioni_prodotto);
    $row = $result->fetch_assoc();
    $productReports =  $row["total"];

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <h1>Bentornato</h1>
    <p><?php echo "Richieste di approvazione in sospeso:".$pendingRequests ?></p>
    <a>Placeholder</a><br>
    <p><?php echo "Nuove segnalazioni di venditori:".$sellerReports ?></p>
    <a>Placeholder</a><br>
    <p><?php echo "Nuvoe segnalazioni di prodotti:".$productReports ?></p>
    <a>Placeholder</a><br>
</body>
</html>