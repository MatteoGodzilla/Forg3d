<?php
session_start();
include_once("../php/db.php");
include_once("./components/productList.php");
include_once("../php/constants.php");
include_once("../php/session.php");
include_once("../php/feedback.php");

if (!isset($_GET['email']) || empty($_GET['email'])) {
	exit;
}

$emailVenditore = $_GET['email'];
$emailUtente = getSessionEmail();
$isFollowing = false;

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

$queryFollowers = "SELECT COUNT(*) as followers FROM Follow WHERE emailVenditore = ?";
$stmtFollowers = mysqli_prepare($connection, $queryFollowers);
mysqli_stmt_bind_param($stmtFollowers, "s", $emailVenditore);
mysqli_stmt_execute($stmtFollowers);
$resultFollowers = mysqli_stmt_get_result($stmtFollowers);
$followersCount = mysqli_fetch_assoc($resultFollowers)['followers'] ?? 0;

$stmt = mysqli_prepare($connection, "SELECT 1 FROM Follow WHERE emailCompratore = ? AND emailVenditore = ?");
mysqli_stmt_bind_param($stmt, "ss", $emailUtente, $emailVenditore);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$isFollowing = mysqli_stmt_num_rows($stmt) > 0;
?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<title>Forg3d Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<link rel="stylesheet" href="./css/header.css"/>
        <link rel="stylesheet" href="./css/popups.css"/>
        <link rel="stylesheet" href="./css/form.css"/>
        <link rel="stylesheet" href="./css/sellerProduct.css"/>
        <link rel="stylesheet" href="./css/productList.css" />
	</head>
	<body>
        <?php 
            include_once("./components/header.php");
            create_header();
        ?>
        <div class="seller-info">
            <div class="seller-details">
                <h2><?= ($seller['nome'] . ' ' . $seller['cognome']) ?></h2>
                <p>Email: <?= ($emailVenditore) ?></p>
                <p>Follower: <?= $followersCount ?></p>
            </div>
            <div class="seller-actions">
                <form method="GET" action="/api/followUnfollow.php" class="follow-btn">
                    <?php if (utenteLoggato() && $emailUtente !== $emailVenditore): ?>
                        <input type="hidden" name="emailVenditore" value="<?= $emailVenditore ?>">
                        <input type="hidden" name="azione" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
                        <input type="submit" value="<?= $isFollowing ? 'Unfollow' : 'Follow' ?>"/>
                    <?php endif; ?>
                </form>
                <button id="toggleReportForm">Segnala<span class="material-symbols-outlined">warning</span></button>
            </div>
        </div>
        <form class="hidden2 report-form" method="POST" action="/api/report.php">
            <h2>Segnala venditore</h2>
            <input type="hidden" name="emailVenditore" value="<?= $emailVenditore ?>">
            <input type="hidden" name="tipo" value="venditore">	
            <label for="motivo">Motivo della segnalazione</label>
            <textarea name="motivo" id="motivo" required></textarea>
            <button type="submit">Invia segnalazione</button>
        </form>
        <?php if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
            include_once("./components/popups.php");
            include_once("./../php/constants.php");
            create_popup($_GET["message"],$_GET["messageType"]);
        } 
        ?>
        <h2>Prodotti</h2>
        <?php if (count($products) > 0): ?>
            <div class="products">
            <?php foreach ($products as $product): ?>
                <?php generateProductList($product); ?>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nessun prodotto trovato.</p>
        <?php endif; ?>
        <script src="./js/report.js"></script>
	</body>
</html>
