<?php
require_once("../php/db.php"); // Connessione al database
require_once("../php/session.php"); // Gestione sessione
session_start();

// Controlla se l'ID del prodotto è stato passato
if (!isset($_GET) || !isset($_GET['id'])) {
    die("");// Modifica all pagina home
}

if (!$prodotto['visibile']) {
    $emailUtente = getSessionEmail();
    $tipoUtente = getTipoUtente();

    // Se non è admin e non è il venditore stesso
    if ($tipoUtente !== 'admin' && $emailUtente !== $prodotto['venditoreEmail']) {
        die("");// Modifica all pagina home
    }
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

// Query per ottenere le varianti del prodotto
$query_varianti =  "SELECT v.id, m.tipologia, m.nomeColore, m.hexColore, v.prezzo
                    FROM Variante v
                    JOIN Materiale m ON v.idMateriale = m.id
                    WHERE v.idProdotto = ?"
;

$stmt = mysqli_prepare($connection, $query_varianti);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$varianti = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Dettaglio Prodotto</title>
</head>
<body>
    <h1><?php echo ($prodotto['nome']); ?></h1>
    <p><strong>Venditore:</strong> <?php echo ($prodotto['venditoreNome'] . ' ' . $prodotto['venditoreCognome']); ?> (<?php echo htmlspecialchars($prodotto['venditoreEmail']); ?>)</p>
    <p><strong>File Modello:</strong> <a href="/<?php echo ($prodotto['fileModello']); ?>" download>Scarica</a></p>
    <h2>Varianti disponibili</h2>
    <?php while ($variante = $varianti->fetch_assoc()): ?>
        <p><strong>Materiale:</strong> <?php echo ($variante['tipologia']); ?></p>
        <p><strong>Colore:</strong> <?php echo $variante['nomeColore']; ?> (#<?php echo $variante['hexColore']; ?>)</p>
        <p><strong>Prezzo:</strong> €<?php echo number_format($variante['prezzo'] / 100, 2, ',', '.'); ?></p>
        <hr>
    <?php endwhile; ?>

    <?php if ($tipoUtente==TipoUtente::COMPRATORE): ?>
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
    <?php endif; ?>
</body>
</html>
