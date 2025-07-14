<?php
session_start();
require_once("../php/db.php");
require_once("../php/session.php");
require_once("./components/productVariant.php");
require_once("./components/sellerEditProductImage.php");
require_once("./components/sellerEditProductMaterial.php");

if(!utenteLoggato() || getUserType() != UserType::SELLER->value){
    header("Location: /");
    exit();
}

$email = getSessionEmail();

if(isset($_GET) && isset($_GET['id'])){
    $idProduct = $_GET['id'];

    //Query per cercare le informazioni da mostrare nella pagina del prodotto
    $query =   "SELECT p.id, p.nome, p.descrizione, p.fileModello, p.visibile, p.varianteDefault, v.emailUtente AS venditoreEmail,
                u.nome AS venditoreNome, u.cognome AS venditoreCognome
                FROM Prodotto p
                JOIN Venditore v ON p.emailVenditore = v.emailUtente
                JOIN Utente u ON v.emailUtente = u.email
                WHERE p.id = ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i", $idProduct);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    //Query per ottenere le varianti che si possono aggiungere nel prodotto
    $query_materiali = "SELECT m.id, m.tipologia, m.nomeColore, m.hexColore 
                         FROM Materiale m 
                         WHERE m.id NOT IN (
                             SELECT v.idMateriale
                             FROM Variante v 
                             WHERE v.idProdotto = ?
                             AND v.visibile = 1
                         ) 
                         AND m.visibile = 1
                         AND m.idVenditore = ? ";

    $stmt = mysqli_prepare($connection, $query_materiali);
    mysqli_stmt_bind_param($stmt, "is", $idProduct, $email);
    mysqli_stmt_execute($stmt);
    $materiali = mysqli_stmt_get_result($stmt);

    // Query per ottenere le varianti già presenti del prodotto
    $query_varianti =  "SELECT m.id, m.tipologia, m.nomeColore, m.hexColore, v.prezzo
                        FROM Variante v
                        JOIN Materiale m ON v.idMateriale = m.id
                        WHERE v.idProdotto = ?
                        AND v.visibile = 1";

    $stmt = mysqli_prepare($connection, $query_varianti);
    mysqli_stmt_bind_param($stmt,"i", $idProduct);
    mysqli_stmt_execute($stmt);
    $varianti = mysqli_stmt_get_result($stmt);

    // 3. Ottieni immagini
    $query_immagini = "SELECT * FROM ImmaginiProdotto WHERE idProdotto = ?";
    $stmt = mysqli_prepare($connection, $query_immagini);
    mysqli_stmt_bind_param($stmt, "i", $idProduct);
    mysqli_stmt_execute($stmt);
    $immagini = mysqli_stmt_get_result($stmt);
} else {
    //Nuovo prodotto
    //Query per ottenere tutte le varianti 
    $query_materiali = "SELECT m.id, m.tipologia, m.nomeColore, m.hexColore 
                        FROM Materiale m 
                        WHERE m.visibile = 1
                        AND m.idVenditore = ?";
    $stmt = mysqli_prepare($connection, $query_materiali);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $materiali = mysqli_stmt_get_result($stmt);
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/productForm.css" />
    <title>Modifica prodotto</title>
</head>
<body>
	<?php 
		include_once("./components/header.php");
		create_header();
	?>
    <h2>Modifica Prodotto</h2>
    <form action="api/handleProduct.php" method="POST" enctype="multipart/form-data">
        <input type="submit" value="Salva modifiche" />
        <fieldset>
            <legend>Informazioni generali</legend>
            <?php 
                if(isset($idProduct)){
            ?>
                    <input type="hidden" name="id" <?php echo("value=".$idProduct) ?> />
            <?php 
                }
            ?>
            <label for="productName">Nome prodotto*</label>
            <input type="text" id="productName" name="productName" required
                <?php echo(isset($product) ? "value='".$product['nome']."'" : "")?> 
            />

            <label for="visible">Prodotto in elenco</label>
            <input type="checkbox" name="visible" id="visible" 
                <?php echo(isset($product['visibile']) && $product['visibile'] == 2 ? 'checked' : '')?> 
            />

            <label for="description">Descrizione</label>
            <!-- Has to be on the same line, otherwise the text area grabs the tabs -->
            <textarea name="description" id="description"><?php if(isset($product['descrizione'])) echo($product['descrizione'])?></textarea>


            <?php if (isset($product['fileModello']) && $product["fileModello"] != ""){ ?>
                <button id="showModel">Mostra modello 3D caricato</button>
                <div class="hidden" id="model-viewer"></div>
            <?php } ?>
            <label for="3dPreview">Sostituisci file 3D (.stl)</label>
            <input type="file" name="3dPreview" id="3dPreview"/>
        </fieldset>
        <fieldset>
            <legend>Immagini</legend>
            <label for="addImage">Aggiungi nuove immagini (.jpeg, .jpg, .png,.webp)</label>
            <input type="file" name="images[]" id="addImage" multiple/>
            <div id="immaginiEsistenti">
                <?php 
                    if(isset($immagini)){ 
                        while ($img = mysqli_fetch_assoc($immagini)){ 
                            generateEditImage($img);
                        }
                    }
                ?>
            </div>
        </fieldset>
        <fieldset>
            <legend>Varianti</legend>

            <select id="selectBox">
            <?php 
                if(isset($materiali)){ 
                    while($m = mysqli_fetch_assoc($materiali)){
                        //Not enough to make a component for it
            ?>
                <option value="<?= $m["id"]?>"><?= $m["nomeColore"]?> (<?= $m["tipologia"]?>)</option>
            <?php
                    }
                }
            ?> 
            </select>
            <input type="button" id="addVariant" value="Aggiungi Variante"/>
            <div id="variantContainer">
            <?php 
                $defaultVariant = 0;
                $defaultVariantColor = "";
                if(isset($product["varianteDefault"])){
                    $defaultVariant = $product["varianteDefault"];
                }
                if(isset($varianti)){
                    while ($variant = mysqli_fetch_assoc($varianti)){
                        generateEditVariant($variant, $defaultVariant);
                        if($variant["id"] === $defaultVariant){
                            $defaultVariantColor = $variant["hexColore"];
                        }
                    }
                }
            ?> 
            </div>
        </fieldset>
    </form>

    <?php if (isset($product['fileModello']) && $product["fileModello"] != ""){ ?>
        <script src="stl_viewer/stl_viewer.min.js"></script>
        <script>
            const showButton = document.querySelector("#showModel");
            const container = document.querySelector("#model-viewer");
            const chosenColor = "#<?= $defaultVariantColor ?>";

            showButton.onclick = (ev) => {
                container.classList.remove("hidden");
                stlViewer = new StlViewer(container, {
                    auto_resize: false,
                    models:[ { id: 0, filename:"<?= $product['fileModello'] ?>"} ],
                    allow_drag_and_drop: false,
                    all_loaded_callback: () => {
                        //Just to set the initial color
                        stlViewer.set_color(0, chosenColor);
                    }
                }); 
                showButton.style.display = "none";
                ev.preventDefault();
            }
        </script>
    <?php } ?>
    <script src="./js/editProduct.js"> </script>
    <script src="./js/darkMode.js"> </script>
</body>
</html>
