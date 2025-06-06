<?php 
session_start();
include_once("../../php/db.php");
include_once("../../php/session.php");
require_once("../../php/feedback.php");

function read($id,$user,$conn){
    echo $user;
    $query = "INSERT INTO NotificaLetta(idNotifica,emailCompratore) VALUES(?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt,"is", $id,$user);
    mysqli_stmt_execute($stmt);
}

if(!utenteLoggato()){
    header("Location: ");
}


$email = getSessionEmail();

if(!isset($_GET["id"])){
    //global wipe
    $query = "SELECT id FROM Notifica 
    WHERE id NOT IN (SELECT idNotifica FROM NotificaLetta WHERE emailCompratore = ?) 
    AND (emailVenditore is NULL) OR emailVenditore IN (SELECT emailVenditore FROM Follow WHERE emailCompratore=?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"ss", $email,$email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ids = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach($ids as $id){
        read($id["id"],$email,$connection);
    }
}
else{
    read($_GET["id"],$email,$connection);
}

header("Location: /notifications.php");
?>

