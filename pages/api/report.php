<?php
session_start();
include_once("../../php/db.php");
include_once("../../php/session.php");
require_once("../../php/feedback.php");

$emailUtente = getSessionEmail();
$tipo = $_POST['tipo'];

try{
    mysqli_begin_transaction($connection);

    $query ="INSERT INTO Segnalazione (emailSegnalatore, motivo) VALUES (?,?)";
    $stmt = mysqli_prepare($connection,$query);
    mysqli_stmt_bind_param($stmt, "ss", $emailUtente,$tipo);
    mysqli_stmt_execute($stmt);

    $idS = mysqli_insert_id($connection);

    if ($tipo === 'venditore' && isset($_POST['emailVenditore'])) {
        $emailVenditore = $_POST['emailVenditore'];
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
    header("Location:".feedback($_SERVER["HTTP_REFERER"], AlertType::SUCCESS->value,"Segnalazione effettuata in modo corretto", true));
} catch(e){
    mysqli_rollback($connection);
}