<?php 
    require_once("../../php/db.php");
    require_once("../../php/session.php");
    require_once("../../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }

    $email = getSessionEmail();

    if(!isset($_POST["name"]) || !isset($_POST["surname"]) || isset($_POST["cellphone"])){
        header("Location: /userInfo.php");
    }

    $query = "UPDATE Utente SET nome = ?, cognome = ?, telefono = ? WHERE email=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"ssss",$_POST["name"] ,$_POST["surname"],$_POST["cellphone"],$email);
    mysqli_stmt_execute($stmt);

    header("Location: ".feedback("/profile.php",AlertType::SUCCESS->value,"Informazioni aggiornate con successo!"))

?>