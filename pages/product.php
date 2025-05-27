<?php
require_once("../php/db.php"); // Connessione al database
require_once("../php/session.php"); // Gestione sessione
session_start();

$varianti = [];

// Controlla se l'ID del prodotto è stato passato
if (!isset($_GET) || !isset($_GET['id'])) {
    die("");// Modifica all pagina home
}

//Prendo l'Id del prodotto cliccato
$idProdotto = $_GET['id'];

//Query per cercare le informazioni da mostrare nella pagina del prodotto
$query =   "SELECT p.id, p.nome, p.fileModello, p.visibile, v.emailUtente AS venditoreEmail,
            u.nome AS venditoreNome, u.cognome AS venditoreCognome
            FROM Prodotto p
            JOIN Venditore v ON p.emailVenditore = v.emailUtente
            JOIN Utente u ON v.emailUtente = u.email
            WHERE p.id = ?"
;

//Eseguo la connessione
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


if ($result->num_rows === 0) {
    header("Location:");//Aggiungiamo la pagina che ci pare
}

//Ottengo il risultato
$prodotto = mysqli_fetch_assoc($result);
$tipoUtente = getUserType();
$emailUtente = getSessionEmail();

if (!$prodotto['visibile']) {
    // Se non è admin e non è il venditore stesso
    if ($tipoUtente != UserType::ADMIN->value && $emailUtente !== $prodotto['venditoreEmail']) {
        die("");// Modifica all pagina home
    }
}

// Query per ottenere le varianti del prodotto
$query_varianti =  "SELECT v.id, m.tipologia, m.nomeColore, m.hexColore, v.prezzo
                    FROM Variante v
                    JOIN Materiale m ON v.idMateriale = m.id
                    WHERE v.idProdotto = ?"
;

$stmt = mysqli_prepare($connection, $query_varianti);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$resultvarianti = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($resultvarianti)) {
			$varianti[] = $row;
		}


//Query delle immagini
$quey_immagini = "SELECT id,nomeFile FROM ImmaginiProdotto WHERE idProdotto= ?";
$stmt = mysqli_prepare($connection, $quey_immagini);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$resultImmagini = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dettaglio Prodotto</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/product.css" />
</head>
<body>

    <?php
        include_once("./components/header.php");
        create_header();
        include_once("./components/image-container.php");
        create_image_container($resultImmagini);
    ?>

    <script src="./js/image-container.js"></script>
    <h2><?php echo ($prodotto['nome']); ?></h2>
    <p><strong>Venditore:</strong> <a href="../sellerProduct.php?email=<?php echo $prodotto['venditoreEmail']; ?>"> <?php echo ($prodotto['venditoreNome'] . ' ' . $prodotto['venditoreCognome']); ?> (<?php echo ($prodotto['venditoreEmail']); ?>)</a></p>
    <p><strong>File Modello:</strong> <a href="/<?php echo ($prodotto['fileModello']); ?>" download>Scarica</a></p>
    <h3>Varianti</h3>

    <?php 
        include_once("./components/varianteOption.php");
        foreach($varianti as $variante){
            varianteOption($variante);
        }
    ?>

    <?php if ($tipoUtente==UserType::BUYER->value): ?>
        <form action="aggiungi_carrello.php" method="POST">
            <input type="hidden" name="idProdotto" value="<?php echo $idProdotto; ?>">
            <button type="submit">Aggiungi al Carrello</button>
        </form>

        <h3>Scrivi una recensione</h3>
        <form action="salva_recensione.php" method="POST">
            <textarea name="recensione" rows="4" cols="50" placeholder="Scrivi la tua recensione..."></textarea><br>
            <input type="hidden" name="idProdotto" value="<?php echo $idProdotto; ?>">
            <button type="submit">Invia Recensione</button>
        </form>

        <h3>Segnala prodotto</h3>
        <form method="POST" action="/api/report.php">
			<input type="hidden" name="emailVenditore" value="<?= $emailVenditore ?>">
			<input type="hidden" name="tipo" value="prodotto">	
            <textarea name="motivo" required placeholder="Motivo della segnalazione"></textarea>
			<button type="submit">Invia segnalazione</button>
    	</form>
    <?php endif; ?>


</body>
</html>
