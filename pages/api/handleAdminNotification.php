<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("../../php/file_utils.php");
require_once("notificationUtils.php");

if(!utenteLoggato() || getUserType() != UserType::ADMIN->value ){
    header("Location: /");
    exit();
}

if(!isset($_POST) || !isset($_POST["title"]) || !isset($_POST["description"])){
    header("Location: /adminHome.php");
    exit();
}

$title = $_POST["title"];
$description = $_POST["description"];

sendAdminNotification($connection, $title, $description);

header("Location: /adminHome.php");
?>
