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

//Get new total for cart
$query_total = "SELECT SUM(V.prezzo * C.quantita) as totale
    FROM Carrello C 
    INNER JOIN Variante V ON C.idVariante = V.id
    WHERE emailCompratore = ?";

$stmt = mysqli_prepare($connection, $query_total);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = mysqli_fetch_assoc($result)["totale"];

header("Content-Type: application/json");
echo(json_encode(["success"=>true, "total"=>$total, "newQuantity"=>$quant, "email"=>$email]));

?>
