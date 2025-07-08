<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
    $email = getSessionEmail();
    
    global $stmt;
    switch(getUserType()){
        case UserType::BUYER->value:
            $query_notifiche = "SELECT id, emailMittente,creazione,titolo, descrizione FROM Notifica WHERE 
            ((emailMittente in (SELECT emailVenditore FROM Follow WHERE emailCompratore=?) AND emailDestinatario is NULL) OR ( emailMittente is NULL AND emailDestinatario is NULL) OR emailDestinatario = ?)  AND
            id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "sss", $email,$email,$email);
            break;
        case UserType::SELLER->value:
            $query_notifiche = "SELECT id, emailMittente,creazione,titolo, descrizione FROM Notifica WHERE 
            (emailMittente is null) AND 
            (emailDestinatario is NULL OR emailDestinatario = ?) AND
            id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "ss", $email,$email);
            break;
        case UserType::ADMIN->value:
            $query_notifiche = "SELECT id, emailMittente,creazione,titolo, descrizione FROM Notifica WHERE 
            (emailMittente is null AND emailDestinatario is NULL) AND 
            id NOT in (SELECT idNotifica FROM NotificaLetta WHERE destinatario=?)
            ORDER BY creazione DESC";
            $stmt = mysqli_prepare($connection, $query_notifiche);
            mysqli_stmt_bind_param($stmt, "s", $email);
            break;
    }

    //Query Notifiche
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $notifs = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //repeat per le lette
    $query_lette = "SELECT id, emailMittente,creazione,titolo, descrizione FROM Notifica INNER JOIN NotificaLetta ON Notifica.id = NotificaLetta.idNotifica WHERE destinatario = ? AND visibile=1";
    $stmt = mysqli_prepare($connection, $query_lette);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $notifs_read = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/notifications.css" />
    <link rel="stylesheet" href="./css/form.css" />
</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
    ?>
    <?php if(getUserType()==UserType::ADMIN->value){ ?>
        <a href="adminNotification.php" id="admin">Invia notifica globale</a>
    <?php } else if(getUserType()== UserType::SELLER->value) { ?>
        <a href="sellerNotification.php" id="seller">Invia notifica</a>
    <?php } ?>

    <?php if(sizeof($notifs)>0) {?>
        <h2>Notifiche nuove</h2>
        <a href="./api/readNotification.php" id ="readAll">Segna tutte come lette</a>
    <?php } ?>

    <?php 
        require_once("components/notification.php");
        foreach($notifs as $notification){
            createNotification($notification);
        }
    ?>

    <?php if(sizeof($notifs_read)>0) {?>
        <h2>Notifiche lette</h2>
        <a href="./api/hideNotification.php" id ="deleteAll">Cancella tutte</a>
    <?php } ?>

    <?php 
        require_once("components/notification.php");
        foreach($notifs_read as $notification){
            createNotificationAsRead($notification);
        }
    ?>
    
    <?php if(sizeof($notifs_read)==0 && sizeof($notifs) ==0){?>
        <p>Nulla da leggere qua! Quanto ti arriveranno nuovi messaggi,lo vedrai dalla campanella nella parte alta dello schermo!</p>
    <?php } ?>
    
    <script src="js/darkMode.js"></script>
</body>
</html>
