<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");


if (!isset($_GET["emailVenditore"]) || !isset($_GET["azione"])) {
    header("Content-Type: application/json");
    exit();
}

$emailUtente = getSessionEmail();
$emailVenditore = $_GET["emailVenditore"];
$azione = $_GET["azione"];

if (!$emailUtente) {
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
    exit();
}

header("Location: ../sellerProduct.php?email=" . urlencode($emailVenditore));
?>