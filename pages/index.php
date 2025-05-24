<?php
session_start();

include_once("../php/db.php");
include_once("./components/productList.php");
include_once("./components/sellerList.php");
include_once("../php/constants.php");
include_once("../php/session.php");

$email = getSessionEmail();
$products = [];
$sellers = [];

if(isset($_GET['search'])){
	$query = $_GET['search'];

	$like = '%' . $query . '%';

	$query_venditori = "SELECT v.emailUtente, u.nome, u.cognome
						FROM Venditore v
						JOIN Utente u ON u.email = v.emailUtente
						WHERE v.stato!=3 AND (u.nome LIKE ? OR u.cognome LIKE ?)";

	$stmt_seller = mysqli_prepare($connection, $query_venditori);
	mysqli_stmt_bind_param($stmt_seller, "ss", $like, $like);
	mysqli_stmt_execute($stmt_seller);
	$result = mysqli_stmt_get_result($stmt_seller);

	if ($result) {
    	while ($row = mysqli_fetch_assoc($result)) {
        	$sellers[] = $row;
    	}
	}

	$query_prodotti = "SELECT p.id, p.nome, p.fileModello, p.visibile,
						u.nome AS venditoreNome, u.cognome AS venditoreCognome
						FROM Prodotto p
						JOIN Venditore v ON p.emailVenditore = v.emailUtente
						JOIN Utente u ON u.email = v.emailUtente
						WHERE v.stato!=3 AND p.visibile = 1 AND p.nome LIKE ?";

	$stmt = mysqli_prepare($connection, $query_prodotti);
	mysqli_stmt_bind_param($stmt, "s", $like);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($result) {
    	while ($row = mysqli_fetch_assoc($result)) {
        	$products[] = $row;
    	}
	}

}else {
	// Query di default (tutti i prodotti visibili)
    $query_prodotti = 	"SELECT p.id, p.nome, p.fileModello, p.visibile,
		       			u.nome AS venditoreNome, u.cognome AS venditoreCognome
						FROM Prodotto p
						JOIN Venditore v ON p.emailVenditore = v.emailUtente
						JOIN Utente u ON u.email = v.emailUtente
						WHERE p.visibile = 1 AND NOT stato=3";

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
		<link rel="stylesheet" href="./css/header.css">
		<link rel="stylesheet" href="./css/home.css">
	</head>
	<body>
			<?php 
				include_once("./components/header.php");
				create_header();
			?>
		<form method="GET" action="index.php">
  			<input type="search" name="search" placeholder="<?php if(!isset($_GET['search'])) echo 'Cerca prodotti o venditori...'; else echo($query) ?>"/>
		</form>
		<?php if (isset($_GET['search'])): ?>
				<h2>Venditori</h2>
				<?php if (count($sellers) > 0): ?>
					<section class="seller-list">
					<?php foreach ($sellers as $seller): ?>
						<?php generateSellerList($seller); ?>
					<?php endforeach; ?>
					</section>
			<?php else: ?>
				<p>Nessun venditore trovato.</p>
			<?php endif; ?>
		<h2>Prodotti</h2>
		<?php if (count($products) > 0): ?>
			<section class="product-list">
				<?php foreach ($products as $product): ?>
					<?php generateProductList($product); ?>
				<?php endforeach; ?>
			</section>
		<?php else: ?>
			<p>Nessun prodotto trovato.</p>
		<?php endif; ?>
		<?php else: ?>
			<h2>Prodotti</h2>
			<section class="product-list">
				<?php foreach ($products as $product): ?>
					<?php generateProductList($product); ?>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>
	</body>
</html>

