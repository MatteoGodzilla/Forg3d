<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");


if (!isset($_GET["emailVenditore"]) || !isset($_GET["azione"])) {
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "message" => "Parametri mancanti"]);
    exit();
}

$emailUtente = getSessionEmail();
$emailVenditore = $_GET["emailVenditore"];
$azione = $_GET["azione"];

if (!$emailUtente) {
    echo json_encode(["success" => false, "message" => "Erroe utente non loggato"]);
    exit();
}

if ($azione === "follow") {
    $stmt = mysqli_prepare($connection,"INSERT IGNORE INTO Follow (emailCompratore, emailVenditore) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt,"ss", $emailUtente, $emailVenditore);
    mysqli_stmt_execute($stmt);
} elseif ($azione === "unfollow") {
    $stmt = mysqli_prepare($connection,"DELETE FROM Follow WHERE emailCompratore = ? AND emailVenditore = ?");
    mysqli_stmt_bind_param($stmt,"ss", $emailUtente, $emailVenditore);
    mysqli_stmt_execute($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Azione non valida"]);
    exit();
}

echo json_encode(["success" => true, "azione" => $azione, "email" => $emailUtente]);

header("Location: ../sellerProduct.php?email=" . urlencode($emailVenditore));
?>