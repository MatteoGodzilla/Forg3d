<?php
require_once("../php/db.php");

$query_prodotti = "SELECT p.id, p.nome, p.fileModello, p.visibile,
                          u.nome AS venditoreNome, u.cognome AS venditoreCognome
                   FROM Prodotto p
                   JOIN Venditore v ON p.emailVenditore = v.emailUtente
                   JOIN Utente u ON u.email = v.emailUtente
                   WHERE p.visibile = 1";

$result = mysqli_query($connection, $query_prodotti);
$prodotti = [];

while ($row = mysqli_fetch_assoc($result)) {
    $prodotti[] = $row;
}
?>