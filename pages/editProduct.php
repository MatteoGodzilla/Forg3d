<?php
require_once("../php/db.php");
require_once("./components/productVariant.php");
session_start();

if(!isset($_GET) && !isset($_GET['id'])){
    die("Prodotto mancante.");
}

$idProduct = $_GET['id'];
//$emailSessione = getSessionEmail();

//Query per cercare le informazioni da mostrare nella pagina del prodotto
$query =   "SELECT p.id, p.nome, p.fileModello, p.visibile, v.emailUtente AS venditoreEmail,
            u.nome AS venditoreNome, u.cognome AS venditoreCognome
            FROM Prodotto p
            JOIN Venditore v ON p.emailVenditore = v.emailUtente
            JOIN Utente u ON v.emailUtente = u.email
            WHERE p.id = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i", $idProduct);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($result->num_rows === 0){
    die("Prodotto mancante.");
}

$product = mysqli_fetch_assoc($result);

/*if ($product['venditoreEmail'] !== $emailSessione) {
    header("Location:".$redirectFailed);
}*/

// Query per ottenere le varianti del prodotto
$query_varianti =  "SELECT v.id, m.tipologia, m.nomeColore, m.hexColore, v.prezzo
                    FROM Variante v
                    JOIN Materiale m ON v.idMateriale = m.id
                    WHERE v.idProdotto = ?"
;

$stmt = mysqli_prepare($connection, $query_varianti);
mysqli_stmt_bind_param($stmt,"i", $idProduct);
mysqli_stmt_execute($stmt);
$result_varianti = mysqli_stmt_get_result($stmt);

// 3. Ottieni immagini
$query_immagini = "SELECT * FROM ImmaginiProdotto WHERE idProdotto = ?";
$stmt = mysqli_prepare($connection, $query_immagini);
mysqli_stmt_bind_param($stmt, "i", $idProduct);
mysqli_stmt_execute($stmt);
$result_immagini = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica prodotto</title>
</head>
<body>
    <h1>Forg3d</h1>
    <h2>Modifica Prodotto</h2>
    <form action="saveProduct.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="idProdotto" value="<?= $idProduct ?>"/>

    <label for="productName">Nome prodotto*</label><br>
    <input type="text" id="productName" name="productName" value="<?= htmlspecialchars($product['nome']) ?>" required/>
    <hr>

    <input type="checkbox" name="visible" id="visible" <?= $product['visibile'] ? 'checked' : '' ?>/>
    <label for="visible">Visibile</label>
    <hr>

    <label>Preview 3D esistente:</label><br>
    <?php if (!empty($product['fileModello'])): ?>
        <a href="/<?= htmlspecialchars($product['fileModello']) ?>" target="_blank">Visualizza file</a><br>
    <?php endif; ?>
    <label for="3dPreview">Sostituisci file 3D</label><br>
    <input type="file" name="3dPreview" id="3dPreview"/>
    <hr>

    <label>Immagini esistenti:</label><br>
    <div id="immaginiEsistenti">
        <?php while ($img = mysqli_fetch_assoc($result_immagini)): ?>
            <div class="immagine-preview">
                <img src="/<?= htmlspecialchars($img['path']) ?>" alt="immagine prodotto"/>
                <input type="checkbox" name="eliminaImmagini[]" value="<?= $img['id'] ?>"/> Elimina
            </div>
        <?php endwhile; ?>
    </div>
    <br>
    <label for="addImage">Aggiungi nuove immagini</label><br>
    <input type="file" name="immagini[]" id="addImage" multiple />
    <hr>

    <label>Varianti</label><br>
    <input type="button" id="addVariant" value="Aggiungi Variante"/>
    <div id="variantiContainer">
        <?php while ($variant = mysqli_fetch_assoc($result_varianti)) {
            generateProductVariant($variant);
        } ?>
    </div>
    <hr>

    <button type="submit">Salva modifiche</button>
</form>
</body>
</html>