<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");

if(!isset($_GET["id"]) || !isset($_GET["variante"]) || !isset($_GET["quantita"])){
    header("Content-Type: application/json");
    echo(json_encode(["success"=>false]));
    exit();
}

$query = "UPDATE Carrello SET quantita=? WHERE idVariante=? AND id=? AND emailCompratore=?";
$stmt = mysqli_prepare($connection, $query);

//parameters
$id = $_GET["id"];
$quant = $_GET["quantita"];
$variante = $_GET["variante"];
$email = getSessionEmail();
mysqli_stmt_bind_param($stmt, "iiis", $quant,$variante,$id,$email);
mysqli_stmt_execute($stmt);

header("Content-Type: application/json");
echo(json_encode(["success"=>true,"email"=>$email]));

?>
