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

//Query delle recensioni
$query_recensioni = "SELECT id, email, valutazione, titolo, testo FROM Recensione WHERE idProdotto = ? ORDER BY dataCreazione";
$stmt = mysqli_prepare($connection, $query_recensioni);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$resultRecensioni = mysqli_stmt_get_result($stmt);
$reviews = [];

while ($review = mysqli_fetch_assoc($resultRecensioni)) {
    $reviews[] = $review;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dettaglio Prodotto</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/form.css" />
    <link rel="stylesheet" href="./css/product.css" />
    <link rel="stylesheet" href="./css/review.css" />
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
    <p><strong>Venditore:</strong> 
        <a href="../sellerProduct.php?email=<?php echo $prodotto['venditoreEmail']; ?>"> <?php echo ($prodotto['venditoreNome'] . ' ' . $prodotto['venditoreCognome']); ?></a></p>
    <p><strong>File Modello:</strong> <a href="/<?php echo ($prodotto['fileModello']); ?>" download>Scarica</a></p>
    <h3>Varianti</h3>

    <?php 
        include_once("./components/varianteOption.php");
        foreach($varianti as $variante){
            varianteOption($variante, $tipoUtente==UserType::BUYER->value);
        }
    ?>

    <?php if ($tipoUtente==UserType::BUYER->value): ?>
        <form action="/api/addToCart.php" method="POST">
            <input type="hidden" name="idVariant" value="<?php echo $idProdotto; ?>">
            <input type="submit" value="Aggiungi al Carrello" />
        </form>

        <button id="toggleReviewForm"> 
            Scrivi una recensione
            <span class="material-symbols-outlined">arrow_drop_down</span>
        </button>
        <!--<h3>Scrivi una recensione</h3>-->
        <form class="hidden" action="/api/addReview.php" method="POST">
            <input type="hidden" name="idProduct" value="<?php echo $idProdotto; ?>">
            <!-- TODO: replace text display to star display -->
            <label for="score" >Valutazione: <span>4</span>/5</label> 
            <input name="score" type="range" min=0 max=5 step=1 value=3 />
            <label for="reviewTitle">Titolo:</label>
            <input name="title" type="text" />
            <label for="review">Descrizione:</label>
            <textarea name="review" rows="4" cols="50" placeholder="Scrivi la tua recensione..."></textarea><br>
            <input type="submit" value="Invia Recensione"/>
        </form>

        <script>
            const variant = document.querySelectorAll("div.variantOption");
            const addToCard = document.querySelector("input[name='idVariant']");
            variant.forEach(v => {
                const radioButton = v.querySelector("input[type='radio']");
                v.onclick = () => {
                    radioButton.click();
                    addToCard.value = radioButton.id;    
                }
            });
            //Automatically select the first variant
            variant[0].click();

            //Review stuff
            const toggleButton = document.querySelector("#toggleReviewForm");
            const form = document.querySelector("form.hidden");
            const slider = document.querySelector("input[type='range']");
            const scoreDisplay = document.querySelector("form span");
            console.log(scoreDisplay);
            slider.oninput = (ev) => scoreDisplay.innerText = ev.srcElement.value;

            toggleButton.onclick = () => {
                if(form.classList.contains("hidden")){
                    form.classList.remove("hidden");
                } else {
                    form.classList.add("hidden");
                }
            }

        </script>
    <?php endif; ?>

    <h3>Recensioni di altri utenti:</h3>
    <?php 
        require_once("./components/review.php");
        foreach($reviews as $review){
            createReview($review);
        }
    ?>  
</body>
</html>
