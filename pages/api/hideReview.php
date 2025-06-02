<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("../../php/file_utils.php");
require_once("notificationUtils.php");

if(getUserType()!==UserType::ADMIN->value){
    header("Location: ");
}

if(!isset($_GET["id"])){
    header("Location: ");
}
$id = $_GET["id"];

#Should we warn the buyer?
$query_buyer = "SELECT * FROM Recensione WHERE id=?";
$stmt = mysqli_prepare($connection, $query_buyer);
mysqli_stmt_bind_param($stmt,"i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($rows[0])){
    $report_info = $rows[0];
    $query_review = "DELETE  FROM Recensione WHERE id=?";
    $stmt = mysqli_prepare($connection, $query_review);
    mysqli_stmt_bind_param($stmt,"i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: /product.php?id=".$report_info["idProdotto"]);
    exit();
}
header("Location ");
exit();
?>