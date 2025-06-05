<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!utenteLoggato() || getUserType()!=UserType::BUYER->value){
    header("Location: /");
}

if(!isset($_POST["ids"]) || !isset($_POST["costs"]) || !isset($_POST["quantity"])){
    header("Location: /cart.php");
}

if(sizeof($_POST["ids"]) != sizeof($_POST["costs"]) || sizeof($_POST["ids"]) != sizeof($_POST["quantity"])){
    header("Location: /cart.php");
}


?>