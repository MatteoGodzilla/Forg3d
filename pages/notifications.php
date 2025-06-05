<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
    $email = getSessionEmail();


    //Query Notifiche
    $query_notifiche = "SELECT titolo, descrizione FROM Notifica WHERE emailVenditore = ? ORDER BY creazione DESC";
    $stmt = mysqli_prepare($connection, $query_notifiche);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $notifs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/sellerHome.css" />
    <link rel="stylesheet" href="./css/header.css" />
</head>
<body>
    
    <?php
        require_once("components/header.php");
        create_header();
    ?>
    <h3>Notifiche nuove</h3>
    <?php if(getUserType()==UserType::ADMIN->value || getUserType()== UserType::SELLER->value){ ?>
        <a href="sellerNotification.php">Invia Notifica</a>
    <?php }?>
    <div class="notifContainer">
    <?php 
        require_once("components/sellerHomeNotif.php");
        foreach($notifs as $notification){
            sellerHomeNotification($notification);
        }
    ?>
    </div>
    <h3>Notifiche lette</h3>
</body>
</html>
