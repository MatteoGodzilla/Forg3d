<?php
session_start();
include_once("../php/db.php");
include_once("./components/productList.php");
include_once("../php/constants.php");
include_once("../php/session.php");

if (!isset($_GET['email']) || empty($_GET['email'])) {
	exit;
}

$emailVenditore = $_GET['email'];

$queryInfo = "SELECT u.nome, u.cognome
                FROM Venditore v
                JOIN Utente u ON v.emailUtente = u.email
                WHERE v.emailUtente = ?";

$stmtSeller = mysqli_prepare($connection, $queryInfo);
mysqli_stmt_bind_param($stmtSeller, "s", $emailVenditore);
mysqli_stmt_execute($stmtSeller);
$resultSeller = mysqli_stmt_get_result($stmtSeller);

if (!$resultSeller || mysqli_num_rows($resultSeller) < 0) {
	echo "Seller non trovato";
}

$seller = mysqli_fetch_assoc($resultSeller);

// Recupera i prodotti visibili
$products = [];
$query_prodotti =  "SELECT p.id, p.nome, p.fileModello, p.visibile,
                        u.nome AS venditoreNome, u.cognome AS venditoreCognome
                    FROM Prodotto p
                    JOIN Venditore v ON p.emailVenditore = v.emailUtente
                    JOIN Utente u ON u.email = v.emailUtente
                    WHERE p.visibile = 1 AND v.emailUtente = ?";

$stmtProduct = mysqli_prepare($connection, $query_prodotti);
mysqli_stmt_bind_param($stmtProduct, "s", $emailVenditore);
mysqli_stmt_execute($stmtProduct);
$result_p = mysqli_stmt_get_result($stmtProduct);

while ($row = mysqli_fetch_assoc($result_p)) {
	$products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<title>Forg3d Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css/home.css">
		<link rel="stylesheet" href="./css/header.css">
	</head>
	<body>
			<?php 
				include_once("./components/header.php");
				create_header();
			?>
        <h2>Prodotti di <?= $seller['nome'] . ' ' . $seller['cognome'] ?></h2>
		<?php if (count($products) > 0): ?>
			<section class="product-list">
				<?php foreach ($products as $product): ?>
					<?php generateProductList($product); ?>
				<?php endforeach; ?>
			</section>
		<?php else: ?>
			<p>Nessun prodotto trovato.</p>
		<?php endif; ?>
	</body>
</html>