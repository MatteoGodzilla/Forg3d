<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

$query = "SELECT * FROM Materiale WHERE id = ? AND idVenditore = ? ";
$stmt = mysqli_prepare($connection, $query);
$id = $_GET["id"];
$email = getSessionEmail();
mysqli_stmt_bind_param($stmt, "is", $id, $email);
mysqli_stmt_execute($stmt);
$res = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

header("Content-Type: application/json");
echo(json_encode($res));

?>
