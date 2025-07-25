<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato()){
    header("Location: /login.php");
}

if(getUserType()!=UserType::BUYER->value){
     header("Location: /");
}

$email = getSessionEmail();

$query_cart = "SELECT DISTINCT C.id as id,M.nomeColore as variante,P.nome as nome,C.quantita as quantita, V.id as idVariante,
    P.id as idProdotto, V.prezzo as prezzo,
    FIRST_VALUE(I.nomeFile) OVER (PARTITION BY C.id, M.nomeColore, P.nome, C.quantita,P.id, V.id, V.prezzo  ORDER BY I.id)  AS immagine
    FROM Carrello C 
    INNER JOIN Variante V ON C.idVariante = V.id
    INNER JOIN Prodotto P ON V.idProdotto = P.id
    INNER JOIN Materiale M ON M.id = V.idMateriale
    LEFT JOIN ImmaginiProdotto I ON I.idProdotto=P.id 
    WHERE emailCompratore = ?";

$stmt = mysqli_prepare($connection, $query_cart);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

$query_total = "SELECT SUM(V.prezzo * C.quantita) as totale
    FROM Carrello C 
    INNER JOIN Variante V ON C.idVariante = V.id
    WHERE emailCompratore = ?";

$stmt = mysqli_prepare($connection, $query_total);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = mysqli_fetch_assoc($result)["totale"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrello</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/cart.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
</head>
<body>
    <form action="./api/convertToOrder.php" method="POST">
        <?php
            require_once("components/header.php");
            create_header();
        ?>
        <main>
            <h2>Il tuo carrello</h2>
            <?php if(sizeof($rows)!=0){ ?>
                <?php
                    include_once("./components/cart_row.php");
                    foreach($rows as $cart_row){ 
                        cart_row($cart_row);
                    } 
                ?>
                <p id="total">Totale: €<?= number_format($total / 100, 2);?></p>
                <input type="submit" value="Paga e completa l'ordine">
            <?php } else { ?>
                <h3>(Nessun articolo nel carrello, vai a fare shopping!)</h3>
            <?php }?>
        </main>
        <script src="./js/updateQuantities.js"></script>
    </form>
    <script src="./js/darkMode.js"></script>
</body>
</html>
