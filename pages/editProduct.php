<?php
require_once("../php/db.php");
require_once("./components/productVariant.php");

//TODO: CHECK SESSION IS SET FOR SELLER AND SELLER OWNS ID

if(!isset($_GET) && !isset($_GET['id'])){
    die("Prodotto mancante.");
}

$idProduct = $_GET['id'];

//Query per cercare le informazioni da mostrare nella pagina del prodotto
$query =   "SELECT p.id, p.nome, p.fileModello, p.visibile, v.emailUtente AS venditoreEmail,
            u.nome AS venditoreNome, u.cognome AS venditoreCognome
            FROM Prodotto p
            JOIN Venditore v ON p.emailVenditore = v.emailUtente
            JOIN Utente u ON v.emailUtente = u.email
            WHERE p.id = ?"
;

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"i", $idProduct);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($result->num_rows === 0){
    die("Prodotto mancante.");
}

$product = mysqli_fetch_assoc($result);

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
    <label for="productName">Nome prodotto*</label>
    <input type="text" id="productName" name="productName" value="<?php echo($product["nome"])?>"/>
    <hr>
    <input type="checkbox" name="visible" id="visible" checked="<?php echo($product["visibile"] ? 'checked' : ''); ?>"/>
    <label for="visible">Visibile*</label>
    <hr>
    <label for="3dPreview">File preview 3d*</label>
    <input type="file" name="3dPreview" id="3dPreview" />
    <!-- actual preview -->
    <hr>
    <label for="addImage">Immagini**</label>
    <input type="button" id="addImage" value="Aggiungi Immagine" />
    <hr>
    <label for="addVariant">Varianti**</label>
    <input type="button" id="addVariant" value="Aggiungi Variante" />
    <?php
        while($variant = mysqli_fetch_assoc($result_varianti)){
            generateProductVariant($variant);
        }
    ?>
</body>
</html>