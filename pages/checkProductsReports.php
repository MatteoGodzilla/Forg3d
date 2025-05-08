<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

#query (seleziona reports non ispezionati non verificati, stato = 0)
$query = "SELECT id,emailSegnalatore,motivo,emailVenditore FROM Segnalazione INNER JOIN SegnalazioneVenditore ON
Segnalazione.id = SegnalazioneVenditore.idSegnalazione WHERE ispezionata=0";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$sellers = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>