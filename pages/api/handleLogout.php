<?php
    require_once("../../php/session.php");
    caricaSessione();
    if(!utenteLoggato()){
        header("Location: /");
        exit();
    }
    terminaSessione();
    header("Location: /login.php");
?>