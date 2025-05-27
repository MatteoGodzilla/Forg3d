<?php
session_start();
include_once("../../php/db.php");
include_once("../../php/session.php");

$emailUtente = getSessionEmail();
$tipo = $_POST['tipo'];
$motivo = $_POST['motivo'];
$emailVenditore = $_POST['emailVenditore'];
if(!utenteLoggato()){
        header("Location:".feedback("./sellerProduct.php?email=".$emailVenditore));
        exit();
}

try{
    mysqli_begin_transaction($connection);

    $query ="INSERT INTO Segnalazione (emailSegnalatore, motivo) VALUES (?,?)";
    $stmt = mysqli_prepare($connection,$query);
    mysqli_stmt_bind_param($stmt, "ss", $emailUtente,$motivo);
    mysqli_stmt_execute($stmt);

    $idS = mysqli_insert_id($connection);

    if ($tipo === 'venditore' && isset($_POST['emailVenditore'])) {
        $qVenditore = "INSERT INTO SegnalazioneVenditore (idSegnalazione, emailVenditore) VALUES (?, ?)";
        $stmtVenditore = mysqli_prepare($connection, $qVenditore);
        mysqli_stmt_bind_param($stmtVenditore, "is", $idS, $emailVenditore);
        mysqli_stmt_execute($stmtVenditore);
    } elseif ($tipo === 'prodotto' && isset($_POST['idProdotto'])) {
        $idProdotto = $_POST['idProdotto'];
        $qProdotto = "INSERT INTO SegnalazioneProdotto (idSegnalazione, idProdotto) VALUES (?, ?)";
        $stmtProdotto = mysqli_prepare($connection, $qProdotto);
        mysqli_stmt_bind_param($stmtProdotto, "ii", $idS, $idProdotto);
        mysqli_stmt_execute($stmtProdotto);
    }
    mysqli_commit($connection);
    header("Location: " . $_SERVER["HTTP_REFERER"]);
} catch(e){
    mysqli_rollback($connection);
}