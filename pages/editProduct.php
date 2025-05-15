<?php
require_once("../php/db.php");
require_once("../php/session.php");
require_once("./components/productVariant.php");
session_start();

if(!utenteLoggato() || getUserType() != UserType::SELLER->value){
    header("Location: /");
    exit();
}

if(isset($_GET) && isset($_GET['id'])){
    $idProduct = $_GET['id'];

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

    //Query per ottenere le varianti che si possono aggiungere nel prodotto
    $query_materiali = "SELECT m.id, m.tipologia, m.nomeColore, m.hexColore 
                         FROM Materiale m 
                         WHERE m.id NOT IN (
                             SELECT v.idMateriale
                             FROM Variante v 
                             WHERE v.idProdotto = ?
                         ) 
                         AND m.visibile = 1
                         ";

    $stmt = mysqli_prepare($connection, $query_materiali);
    mysqli_stmt_bind_param($stmt, "i", $idProduct);
    mysqli_stmt_execute($stmt);
    $materiali = mysqli_stmt_get_result($stmt);

    // Query per ottenere le varianti già presenti del prodotto
    $query_varianti =  "SELECT m.id, m.tipologia, m.nomeColore, m.hexColore, v.prezzo
                        FROM Variante v
                        JOIN Materiale m ON v.idMateriale = m.id
                        WHERE v.idProdotto = ?";

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
}

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
    <form action="api/handleProduct.php" method="POST" enctype="multipart/form-data">
        <?php 
            if(isset($idProduct)){
        ?>
                <input type="hidden" name="id" <?php echo("value=".$idProduct) ?> />
        <?php 
            }
        ?>

        <label for="productName">Nome prodotto*</label><br>
        <input type="text" id="productName" name="productName" required
            <?php echo(isset($product) ? "value='".$product['nome']."'" : "")?> 
        />
        <hr>

        <input type="checkbox" name="visible" id="visible" 
            <?php echo(isset($product['visibile']) && $product['visibile'] == 2 ? 'checked' : '')?>/>
        <label for="visible">Visibile</label>
        <hr>

        <label>Preview 3D esistente:</label><br>
        <?php if (isset($product['fileModello'])){ ?>
            <a href="<?= htmlspecialchars($product['fileModello']) ?>" target="_blank">Visualizza file</a><br>
        <?php } ?>
        <label for="3dPreview">Sostituisci file 3D</label><br>
        <input type="file" name="3dPreview" id="3dPreview"/>
        <hr>

        <label>Immagini esistenti:</label><br>
        <div id="immaginiEsistenti">
            <?php 
                if(isset($immagini)){ 
                    while ($img = mysqli_fetch_assoc($immagini)){ 
            ?>
                        <div class="immagine-preview">
                            <img src="<?= htmlspecialchars($img['nomeFile']) ?>" alt="immagine prodotto"/>
                            <input type="checkbox" name="deletedImages[]" value="<?= $img['id'] ?>"/> Elimina
                        </div>
            <?php
                    }
                }
            ?>
        </div>
        <br>
        <label for="addImage">Aggiungi nuove immagini</label><br>
        <input type="file" name="images[]" id="addImage" multiple />
        <hr>

        <label>Varianti</label><br>

        <select id="selectBox">
        <?php 
            if(isset($materiali)){ 
                while($m = mysqli_fetch_assoc($materiali)){
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
            if(isset($varianti)){
                while ($variant = mysqli_fetch_assoc($varianti)){
        ?>
            <input type="hidden" name="materialIds[]" value="<?= $variant["id"] ?>" multiple/>
            <label><?= $variant["nomeColore"]?> (<?= $variant["tipologia"]?>)</label>
            <label><?= $variant["hexColore"]?></label>
            <input type=number" name="variantCosts[]" value="<?= $variant["prezzo"] ?>" multiple/>
            <label for="removeVariant[]">Rimuovi</label>
            <input type="checkbox" name="removeVariant[]" value="<?= $variant["id"] ?>" multiple/>
            <br>
        <?php 
                }
            }
        ?> 
        </div>
        <hr>

        <button type="submit">Salva modifiche</button>
    </form>

    <script>
        const addVariantButton = document.querySelector("#addVariant");
        const variantContainer = document.querySelector("#variantContainer");
        const selectBox = document.querySelector("#selectBox");

        addVariantButton.onclick = () => {
            if(selectBox.selectedIndex >= 0){
                fetch(`/api/getMaterial.php?id=${selectBox.value}`)
                    .then(res => res.json())
                    .then(obj => {
                        const hiddenId = document.createElement("input");
                        hiddenId.setAttribute("type","hidden");
                        hiddenId.setAttribute("name","materialIds[]");
                        hiddenId.setAttribute("value",selectBox.value);
                        variantContainer.appendChild(hiddenId);

                        const label1 = document.createElement("label");
                        label1.innerText = `${obj["nomeColore"]} (${obj["tipologia"]})`;
                        variantContainer.appendChild(label1);
                        const label2 = document.createElement("label");
                        label2.innerText = obj["hexColore"];
                        variantContainer.appendChild(label2);

                        const variantCost = document.createElement("input");
                        variantCost.setAttribute("type","number");
                        variantCost.setAttribute("name","variantCosts[]");
                        variantCost.setAttribute("value","00");
                        variantContainer.appendChild(variantCost);

                        const label3 = document.createElement("label");
                        label2.innerText = "Rimuovi"; 
                        variantContainer.appendChild(label2);

                        const removeVariant = document.createElement("input");
                        removeVariant.setAttribute("type","checkbox");
                        removeVariant.setAttribute("name","removeVariant[]");
                        removeVariant.setAttribute("value",selectBox.value);
                        variantContainer.appendChild(removeVariant);

                        selectBox.options.remove(selectBox.selectedIndex);
                        console.log(selectBox.options);
                    })
            }
        }  
    </script>
</body>
</html>
