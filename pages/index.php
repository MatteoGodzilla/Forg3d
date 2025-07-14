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
	$filter = isset($_GET['filter']) ? $_GET['filter'] : 'tutto';
	$like = '%' . $query . '%';

	if ($filter === 'prodotti' || $filter === 'tutto') {
		if (!empty($query)) {
			$query_prodotti = "SELECT DISTINCT p.id, p.nome, p.fileModello, p.visibile,
								u.nome AS venditoreNome, u.cognome AS venditoreCognome,
								FIRST_VALUE(I.nomeFile) OVER (
                                    PARTITION BY p.id, p.nome, p.fileModello, p.visibile, u.nome, u.cognome
                                    ORDER BY I.id
								) AS immagine
							FROM Prodotto p
							JOIN Venditore v ON p.emailVenditore = v.emailUtente
							JOIN Utente u ON u.email = v.emailUtente
							LEFT JOIN ImmaginiProdotto I ON I.idProdotto = p.id
							WHERE v.stato != 3 AND p.visibile = 2 AND p.nome LIKE ?";

			$stmt = mysqli_prepare($connection, $query_prodotti);
			mysqli_stmt_bind_param($stmt, "s", $like);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		} else {
			$query_prodotti = "SELECT DISTINCT P.id, P.nome, P.fileModello, P.visibile,
								U.nome AS venditoreNome, U.cognome AS venditoreCognome,
								(SELECT I.nomeFile 
									FROM ImmaginiProdotto I 
									WHERE I.idProdotto = P.id
									ORDER BY I.id LIMIT 1) AS immagine
							FROM Prodotto P
							JOIN Venditore V ON V.emailUtente = P.emailVenditore
							JOIN Utente U ON U.email = V.emailUtente
							WHERE V.stato != 3 AND P.visibile = 2";

			$result = mysqli_query($connection, $query_prodotti);
		}

		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				$products[] = $row;
			}
		}
	}

	if ($filter === 'venditori' || $filter === 'tutto') {
		if (!empty($query)) {
			$query_venditori = "SELECT DISTINCT V.emailUtente, U.nome, U.cognome
								FROM Venditore V
								JOIN Utente U ON U.email = V.emailUtente
								WHERE V.stato != 3 AND (U.nome LIKE ? OR U.cognome LIKE ?)";

			$stmt = mysqli_prepare($connection, $query_venditori);
			mysqli_stmt_bind_param($stmt, "ss", $like, $like);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		} else {
			$query_venditori = "SELECT DISTINCT V.emailUtente, U.nome, U.cognome
								FROM Venditore V
								JOIN Utente U ON U.email = V.emailUtente
								WHERE V.stato != 3";

			$result = mysqli_query($connection, $query_venditori);
		}

		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				$sellers[] = $row;
			}
		}
	}

	if ($filter === 'seguiti') {
		$query_seguiti = "SELECT V.emailUtente, U.nome, U.cognome
						FROM Follow F
						JOIN Venditore V ON V.emailUtente = F.emailVenditore
						JOIN Utente U ON U.email = V.emailUtente
						WHERE F.emailCompratore = ?";

		if (!empty($query)) {
			$query_seguiti .= " AND (U.nome LIKE ? OR U.cognome LIKE ?)";
			$stmt = mysqli_prepare($connection, $query_seguiti);
			mysqli_stmt_bind_param($stmt, "sss", $email, $like, $like);
		} else {
			$stmt = mysqli_prepare($connection, $query_seguiti);
			mysqli_stmt_bind_param($stmt, "s", $email);
		}

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				$sellers[] = $row;
			}
		}
	}
}else {
		// NESSUNA RICERCA: mostra tutti i prodotti visibili
		$query_prodotti = "SELECT DISTINCT P.id, P.nome, P.fileModello, P.visibile,
							U.nome AS venditoreNome, U.cognome AS venditoreCognome,
							(SELECT I.nomeFile 
								FROM ImmaginiProdotto I 
								WHERE I.idProdotto = P.id
								ORDER BY I.id LIMIT 1) AS immagine
							FROM Prodotto P
							JOIN Venditore V ON V.emailUtente = P.emailVenditore
							JOIN Utente U ON U.email = V.emailUtente
							WHERE V.stato != 3 AND P.visibile = 2";

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
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/home.css" />
        <link rel="stylesheet" href="./css/productList.css" />
        <link rel="stylesheet" href="./css/sellerList.css" />
	</head>
	<body>
        <?php 
            include_once("./components/header.php");
            create_header();
        ?>
		<form method="GET" action="index.php" class="search-form">

			<?php
			$query = isset($_GET['search']) ? $_GET['search'] : '';
			$filter = isset($_GET['filter']) ? $_GET['filter'] : 'tutto';
			?>

			<div class="search-bar">
				<div class="filter-wrapper">
					<span class="material-symbols-outlined filter-icon" id="filterToggle">filter_list</span>
					<div class="filter-dropdown" id="filterDropdown">
						<label><input type="radio" name="filter" value="tutto" <?php if($filter=='tutto') echo 'checked'; ?>> Tutto</label>
						<label><input type="radio" name="filter" value="prodotti" <?php if($filter=='prodotti') echo 'checked'; ?>> Prodotti</label>
						<label><input type="radio" name="filter" value="venditori" <?php if($filter=='venditori') echo 'checked'; ?>> Venditori</label>
						<?php if ($email): ?>
							<label><input type="radio" name="filter" value="seguiti" <?php if($filter=='seguiti') echo 'checked'; ?>> Seguiti</label>
						<?php endif; ?>
					</div>
				</div>

				<input type="search" name="search"
					placeholder="Cerca prodotti o venditori..."
					value="<?php echo htmlspecialchars($query); ?>" />

				<button type="submit" class="search-icon">
					<span class="material-symbols-outlined">search</span>
				</button>
			</div>
		</form>

		<?php if (isset($_GET['search'])): ?>
			<?php if ($filter !== 'prodotti'): ?>
				<h2>Venditori</h2>
				<?php if (count($sellers) > 0): ?>
					<div class="sellers">
					<?php foreach ($sellers as $seller): ?>
						<?php generateSellerList($seller); ?>
					<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p class="not-found">Nessun venditore trovato.</p>
				<?php endif; ?>
            <?php endif; ?>

			<?php if ($filter === 'prodotti' || $filter === 'tutto'): ?>
				<h2>Prodotti</h2>
				<?php if (count($products) > 0): ?>
					<div class="products">
					<?php foreach ($products as $product): ?>
						<?php generateProductList($product); ?>
					<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p class="not-found">Nessun prodotto trovato.</p>
				<?php endif; ?>
			<?php endif; ?>

		<?php else: ?>
            <h2>Prodotti</h2>
            <div class="products">
            <?php 
                foreach($products as $product){
                    generateProductList($product);
                }
            ?>
            </div>
        <?php endif; ?>

        <script src="./js/darkMode.js"></script>
		<script src="./js/home-filter.js"></script>
	</body>
</html>

