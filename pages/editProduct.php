<?php
//TODO: check that at least a variant is present before submitting
require_once("../php/db.php");
require_once("../php/session.php");
require_once("./components/productVariant.php");
require_once("./components/sellerEditProductImage.php");
require_once("./components/sellerEditProductMaterial.php");
session_start();

if(!utenteLoggato() || getUserType() != UserType::SELLER->value){
    header("Location: /");
    exit();
}

$email = getSessionEmail();

if(isset($_GET) && isset($_GET['id'])){
    $idProduct = $_GET['id'];

    //Query per cercare le informazioni da mostrare nella pagina del prodotto
    $query =   "SELECT p.id, p.nome, p.fileModello, p.visibile, p.varianteDefault, v.emailUtente AS venditoreEmail,
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
                         ) 
                         AND m.visibile = 1
                         AND m.idVenditore = ?
                         ";

    $stmt = mysqli_prepare($connection, $query_materiali);
    mysqli_stmt_bind_param($stmt, "is", $idProduct, $email);
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

        <label for="visible">Visibile</label>
        <input type="checkbox" name="visible" id="visible" 
            <?php echo(isset($product['visibile']) && $product['visibile'] == 2 ? 'checked' : '')?>/>

        <label>Preview 3D esistente:</label>
        <?php if (isset($product['fileModello'])){ ?>
            <a href="<?= htmlspecialchars($product['fileModello']) ?>" target="_blank">Visualizza file</a>
        <?php } ?>
        <label for="3dPreview">Sostituisci file 3D</label>
        <input type="file" name="3dPreview" id="3dPreview"/>

        <label>Immagini esistenti:</label>
        <div id="immaginiEsistenti">
            <?php 
                if(isset($immagini)){ 
                    while ($img = mysqli_fetch_assoc($immagini)){ 
                        generateEditImage($img);
                    }
                }
            ?>
        </div>

        <label for="addImage">Aggiungi nuove immagini</label>
        <input type="file" name="images[]" id="addImage" multiple />

        <label>Varianti</label>

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
            if(isset($varianti)){
                while ($variant = mysqli_fetch_assoc($varianti)){
                    generateEditVariant($variant);
                }
            }
        ?> 
        </div>

        <button type="submit">Salva modifiche</button>
    </form>

    <script>
        const addVariantButton = document.querySelector("#addVariant");
        const variantContainer = document.querySelector("#variantContainer");
        const selectBox = document.querySelector("#selectBox");
        let defaultRadioButtons = document.querySelectorAll("input[type='radio']");

        addVariantButton.onclick = () => {
            if(selectBox.selectedIndex >= 0){
                fetch(`/api/getMaterial.php?id=${selectBox.value}`)
                    .then(res => res.json())
                    .then(obj => {
                        const hiddenDiv = document.createElement("div");
                        hiddenDiv.setAttribute("class","variantInfo");
                        variantContainer.appendChild(hiddenDiv);

                        const hiddenId = document.createElement("input");
                        hiddenId.setAttribute("type","hidden");
                        hiddenId.setAttribute("name","materialIds[]");
                        hiddenId.setAttribute("value",selectBox.value);
                        hiddenDiv.appendChild(hiddenId);
                        
                        const defaultButton = document.createElement("input");
                        defaultButton.setAttribute("type", "radio");
                        defaultButton.setAttribute("name", "defaultVariant");
                        defaultButton.setAttribute("value", selectBox.value);
                        defaultButton.setAttribute("id", selectBox.value);
                        let alreadyPresent = false;
                        for(let button of defaultRadioButtons){
                            alreadyPresent = alreadyPresent || button.checked;
                        }
                        if(!alreadyPresent){
                            defaultButton.setAttribute("checked", "checked");
                        }
                        hiddenDiv.appendChild(defaultButton);

                        const labelDefault = document.createElement("label");
                        labelDefault.setAttribute("for", selectBox.value);
                        labelDefault.innerText = "Default";
                        hiddenDiv.appendChild(labelDefault);

                        const labelName = document.createElement("label");
                        labelName.innerText = `${obj["nomeColore"]} (${obj["tipologia"]})`;
                        hiddenDiv.appendChild(labelName);
                        
                        /*
                        const labelColor = document.createElement("label");
                        labelColor.innerText = obj["hexColore"];
                        hiddenDiv.appendChild(labelColor);
                        */
                        //colore

                        const variantCost = document.createElement("input");
                        variantCost.setAttribute("type","number");
                        variantCost.setAttribute("name","variantCosts[]");
                        variantCost.setAttribute("value","00");
                        hiddenDiv.appendChild(variantCost);

                        const labelRemove = document.createElement("label");
                        labelRemove.setAttribute("for", `removeVariant[${selectBox.value}]`);
                        labelRemove.innerText = "Rimuovi"; 
                        hiddenDiv.appendChild(labelRemove);

                        const removeVariant = document.createElement("input");
                        removeVariant.setAttribute("type","checkbox");
                        removeVariant.setAttribute("name",`removeVariant[${selectBox.value}]`);
                        removeVariant.setAttribute("id",`removeVariant[${selectBox.value}]`);
                        removeVariant.setAttribute("value",selectBox.value);
                        hiddenDiv.appendChild(removeVariant);

                        const svg = document.createElementNS("http://www.w3.org/2000/svg","svg");
                        svg.setAttribute("width","40");
                        svg.setAttribute("height","40px");
                        const ellipse = document.createElementNS("http://www.w3.org/2000/svg","ellipse");
                        ellipse.setAttribute("stroke","black");
                        ellipse.setAttribute("fill","#"+obj["hexColore"]);
                        ellipse.setAttribute("stroke-width","2");
                        ellipse.setAttribute("rx","18");
                        ellipse.setAttribute("ry","18");
                        ellipse.setAttribute("cx","18");
                        ellipse.setAttribute("cy","18");
                        svg.appendChild(ellipse);
                        hiddenDiv.appendChild(svg);

                        selectBox.options.remove(selectBox.selectedIndex);
                        console.log(selectBox.options);
                        defaultRadioButtons = document.querySelectorAll("input[type='radio']");
                    })
            }
        }  
    </script>
</body>
</html>
