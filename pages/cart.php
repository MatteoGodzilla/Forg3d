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


$query_cart = "SELECT C.id as id,M.nomeColore as variante,P.nome as nome,C.quantita as quantita, V.id as idVariante,
    P.id as idProdotto, V.prezzo as prezzo,
    FIRST_VALUE(I.nomeFile) OVER (PARTITION BY  C.id, M.nomeColore, P.nome, C.quantita,P.id, V.id, V.prezzo) AS immagine
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
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrello</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/cart.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/updateQuantities.js"></script>
</head>
<body>
    <form action="./api/convertToOrder.php" method="POST">
        <?php
            require_once("components/header.php");
            create_header();
        ?>
        <h2>Il tuo carrello</h2>
            
        <?php if(sizeof($rows)!=0){ ?>
            <input type="submit" value="Paga e completa l'ordine">
            <?php
            include_once("./components/cart_row.php");
            foreach($rows as $cart_row){ 
                cart_row($cart_row);
            } ?>
        <?php } else { ?>
            <h3>(Nessun item in carrello,vai a fare shopping!)</h3>
        <?php }?>
    </form>
</body>
</html>