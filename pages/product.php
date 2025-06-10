<?php
require_once("../php/db.php"); // Connessione al database
require_once("../php/session.php"); // Gestione sessione
require_once("../php/replyUtils.php");
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
                    WHERE v.idProdotto = ?
                    AND v.visibile = 1" ;

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
$query_recensioni = "SELECT id, email, valutazione, titolo, testo, inRispostaA FROM Recensione WHERE idProdotto = ? ORDER BY dataCreazione";
$stmt = mysqli_prepare($connection, $query_recensioni);
mysqli_stmt_bind_param($stmt,"i", $idProdotto);
mysqli_stmt_execute($stmt);
$resultRecensioni = mysqli_stmt_get_result($stmt);

$reviews = [];
while ($review = mysqli_fetch_assoc($resultRecensioni)) {
    $reviews[] = $review;
}
//Crea l'albero delle recensioni
$graph = createReviewTree($reviews);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dettaglio Prodotto</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/form.css" />
    <link rel="stylesheet" href="./css/product.css" />
    <link rel="stylesheet" href="./css/popups.css">
    <link rel="stylesheet" href="./css/review.css" />
</head>
<body>
    <?php
        include_once("./components/header.php");
        create_header();
    ?>
    
    <main>
        <?php 
            include_once("./components/image-container.php");
            create_image_container($resultImmagini);
        ?>
        <script src="./js/image-container.js"></script>
        <h2><?php echo htmlspecialchars($prodotto['nome']); ?></h2>
        <p><strong>Venditore:</strong> 
            <a href="../sellerProduct.php?email=<?php echo htmlspecialchars($prodotto['venditoreEmail']); ?>"> <?php echo htmlspecialchars($prodotto['venditoreNome']) . ' ' . htmlspecialchars($prodotto['venditoreCognome']); ?></a></p>
        <?php if(isset($prodotto['fileModello'])){ ?>
            <button id="showModel">Mostra modello 3D</button>
            <div class="hidden" id="model-viewer"></div>
        <?php } ?>
        <h3>Varianti</h3>

        <?php if ($tipoUtente!=UserType::BUYER->value): ?>
            <?php 
                include_once("./components/varianteOption.php");
                foreach($varianti as $variante){
                    varianteOption($variante, true);
                }
            ?>
        <?php endif?>

        <?php if ($tipoUtente==UserType::BUYER->value): ?>
            <form action="/api/addToCart.php" method="POST">
                <input type="hidden" name="idVariant" value="<?php echo $idProdotto; ?>">
            <?php 
                include_once("./components/varianteOption.php");
                foreach($varianti as $variante){
                    varianteOption($variante, true);
                }
            ?>
                <input type="submit" value="Aggiungi al Carrello" />
            </form>


            <button id="toggleReportForm"> 
                Segnala il prodotto
                <span class="material-symbols-outlined">arrow_drop_down</span>
            </button>
            <!--<h3>Segnala prodotto</h3>-->
            <form id="reportForm" class="hidden2" action="/api/report.php" method="POST">
                <input type="hidden" name="idProdotto" value="<?php echo $idProdotto ?>">
                <input type="hidden" name="tipo" value="prodotto">
                <label for="motivo">Descrizione segnalazione:</label>
                <textarea id="motivo" name="motivo" rows="4" cols="50" required placeholder="Motivo della segnalazione"></textarea>
                <input type="submit" value="Invia segnalazione"/>
            </form>
            <?php if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                    include_once("./components/popups.php");
                    include_once("./../php/constants.php");
                    create_popup($_GET["message"],$_GET["messageType"]);
                } 
            ?>
            <script src="./js/report.js"></script>
        <?php endif; ?>

        <?php if(isset($prodotto['fileModello'])){ ?>
            <script src="stl_viewer/stl_viewer.min.js"></script>
            <script>
                //This variable is used inside productWithModel, but it needs the path from php
                //it is meant to be a global variable
                modelPath = "..<?= $prodotto['fileModello'] ?>";
            </script>
            <script src="js/productWithModel.js"></script>
        <?php } else { ?>
            <script src="js/productWithoutModel.js"></script>
        <?php } ?>
    </main>

    <aside>
        <h3>Recensioni</h3>
        <?php if ($tipoUtente==UserType::BUYER->value){ ?>
            <button id="toggleReviewForm"> 
                Scrivi una recensione
                <span class="material-symbols-outlined">arrow_drop_down</span>
            </button>
            <!--<h3>Scrivi una recensione</h3>-->
            <form id="reviewForm" class="hidden" action="/api/addReview.php" method="POST">
                <input type="hidden" name="idProduct" value="<?= $idProdotto ?>">
                <!-- TODO: replace text display to star display -->
                <label for="score" >Valutazione: <span>4</span>/5</label> 
                <input id="score" name="score" type="range" min=0 max=5 step=1 value=3 />
                <label for="reviewTitle">Titolo:</label>
                <input id ="reviewTitle" name="title" type="text" />
                <label for="review">Descrizione:</label>
                <textarea id="review" name="review" rows="4" cols="50" placeholder="Scrivi la tua recensione..."></textarea><br>
                <input type="submit" value="Invia Recensione"/>
            </form>
            <script src="./js/productReview.js"></script>
        <?php } ?>
        <?php 
            require_once("./components/review.php");
            foreach($graph->children as $review){
                createReview($review, $idProdotto, 0);
            }
        ?> 
    </aside>
     
    <script src="js/replyToReview.js"></script>
</body>
</html>
