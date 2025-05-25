<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("../../php/file_utils.php");
require_once("notificationUtils.php");

if(!utenteLoggato() || getUserType() != UserType::SELLER->value ){
    header("Location: /");
    exit();
}

$email = getSessionEmail(); 

if(!isset($_POST) || !isset($_POST["title"]) || !isset($_POST["description"])){
    header("Location: /sellerHome.php");
    exit();
}

$title = $_POST["title"];
$description = $_POST["description"];

sendSellerNotification($connection, $email, $title, $description);

header("Location: /sellerHome.php");
?>
