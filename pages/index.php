<?php
session_start();

include_once("../php/db.php");
include_once("./components/productList.php");
include_once("../php/constants.php");
include_once("../php/session.php");

$email = getSessionEmail();
$products = [];

if(isset($_GET['search'])){
	$query = $_GET['search'];

	$like = '%' . $query . '%';

	$query_prodotti = "SELECT Prodotto.id, Prodotto.nome, Prodotto.visibile,Venditore.emailUtente AS venditoreEmail,
						Utente.nome AS venditoreNome, Utente.cognome AS venditoreCognome
					FROM Prodotto
					JOIN Venditore ON Prodotto.emailVenditore = Venditore.emailUtente
					JOIN Utente ON Utente.email = Venditore.emailUtente
					WHERE Prodotto.visibile = 1 AND
						(Prodotto.nome LIKE ? OR
						Utente.nome LIKE ? OR
						Utente.cognome LIKE ?)";

	$stmt = mysqli_prepare($connection, $query_prodotti);
	mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($result) {
    	while ($row = mysqli_fetch_assoc($result)) {
        	$products[] = $row;
    	}
	}

}else {
	// Query di default (tutti i prodotti visibili)
    $query_prodotti = "SELECT p.id, p.nome, p.fileModello, p.visibile,
                          u.nome AS venditoreNome, u.cognome AS venditoreCognome
                   FROM Prodotto p
                   JOIN Venditore v ON p.emailVenditore = v.emailUtente
                   JOIN Utente u ON u.email = v.emailUtente
                   WHERE p.visibile = 1";

    $result = mysqli_query($connection, $query_prodotti);

	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$products[] = $row;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<title>Forg3d Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css/home.css">
	</head>
	<body>
		<header>
			<?php 
			include_once("./components/header.php");
			create_header();
			?>
		</header>
		<form method="GET" action="index.php">
  			<input type="search" name="search" placeholder="<?php if(!isset($_GET['search'])) echo 'Cerca prodotti o venditori...'; else echo($query) ?>"/>
		</form>
			<?php
				if (isset($products)) {
					foreach ($products as $product) {
						generateProductList($product);
					}
				}
			?>
	</body>
</html>
