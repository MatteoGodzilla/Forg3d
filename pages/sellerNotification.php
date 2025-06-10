<?php 
session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#non autorizzato
if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
    header("Location: /");
    exit();
}

?>
<!DOCTYPE html>
<html lang=it>
    <head>
        <title>Invia Notifica</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/form.css" />
        <link rel="stylesheet" href="./css/sellerNotification.css" />
    </head>
    <body>
        <?php 
            require_once("components/header.php");
            create_header();
        ?>
        <h2>Invia notifica</h2>
        <form action="/api/handleSellerNotification.php" method="POST">
            <label for="title">Titolo</label>
            <input type="text" name="title" id="title" required /> 
            <label for="description">Descrizione</label>
            <textarea name="description" id="description" required></textarea> 
            <input type="submit" value="Invia"/>
        </form>
    </body>
</html>
