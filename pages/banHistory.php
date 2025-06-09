<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

//make query based on what bans to look at (products or users)
$query="";
if(isset($_GET["Products"])){
    $query = "SELECT * FROM Prodotto WHERE visibile=0";
}
else{
    $query = "SELECT * FROM Venditore WHERE stato=3";
}

//execute
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Storico Segnalazioni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet"  href="./css/reportDetail.css" />
    <link rel="stylesheet"  href="./css/bannedElements.css" />
    <link rel="stylesheet"  href="./css/header.css" />
    <link rel="stylesheet"  href="./css/adminInterface.css" />
</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
    ?>

    <h2>Bans</h2>

    <?php 
        require_once("components/ban.php");
        foreach($users as $user){
            showBan($user,isset($_GET["Products"]));
        }
    ?>
</body>
</html>
