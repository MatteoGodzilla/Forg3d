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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["follow"])) {
        $stmt = mysqli_prepare($connection, "INSERT IGNORE INTO Follow (emailCompratore, emailVenditore) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $emailUtente, $emailVenditore);
        mysqli_stmt_execute($stmt);
        $isFollowing = true;
        $followersCount++;
    } elseif (isset($_POST["unfollow"])) {
        $stmt = mysqli_prepare($connection, "DELETE FROM Follow WHERE emailCompratore = ? AND emailVenditore = ?");
        mysqli_stmt_bind_param($stmt, "ss", $emailUtente, $emailVenditore);
        mysqli_stmt_execute($stmt);
        $isFollowing = false;
        $followersCount = max(0, $followersCount - 1);
    }
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
        <section class="seller-info">
        <h2><?= ($seller['nome'] . ' ' . $seller['cognome']) ?></h2>
        <p>Email: <?= ($emailVenditore) ?></p>
        <p>Follower: <?= $followersCount ?></p>
        <form method="POST" class="follow-btn">
                <?php if ($isFollowing): ?>
                    <button type="submit" name="unfollow">Unfollow</button>
                <?php else: ?>
                    <button type="submit" name="follow">Segui</button>
                <?php endif; ?>
        </form>
		<form method="POST" action="/api/report.php">
			<input type="hidden" name="emailVenditore" value="<?= $emailVenditore ?>">
			<input type="hidden" name="tipo" value="venditore">	
			<button type="submit">Segnala venditore</button>
    	</form>
    	</section>
		<?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <?php generateProductList($product); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nessun prodotto trovato.</p>
        <?php endif; ?>
	</body>
</html>