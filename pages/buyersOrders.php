<?php
session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() ){
    header("Location: /login.php");
    exit();
}

if(getUserType()!=UserType::BUYER->value){
     header("Location: /sellerHome.php");
     exit();
}

$email = getSessionEmail();
$query_orders = "SELECT DISTINCT P.id as idProdotto,M.nomeColore as variante, P.nome as nome, 
    O.emailVenditore as seller, O.id as OrderId, O.stato as stato, OI.quantita as quantita, OI.prezzo as prezzo,
    FIRST_VALUE(I.nomeFile) OVER (PARTITION BY O.id, P.id, P.nome, O.emailVenditore, OI.quantita, O.stato, M.nomeColore, OI.prezzo) AS immagine 
    FROM Ordine O 
    INNER JOIN InfoOrdine OI ON OI.idOrdine = O.id
    INNER JOIN Variante V on V.id = OI.idVariante 
    INNER JOIN Prodotto P ON V.idProdotto = P.id
    INNER JOIN Materiale M ON M.id = V.idMateriale
    LEFT JOIN ImmaginiProdotto I ON P.id = I.idProdotto 
    WHERE O.emailCompratore = ? ORDER BY O.stato";

$stmt = mysqli_prepare($connection, $query_orders);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

$paidOrders = 0;
$shippedOrders = 0;
$completedOrders = 0;
foreach($rows as $order){ 
    if($order["stato"] == 0)$paidOrders++;
    if($order["stato"] == 1)$shippedOrders++;
    if($order["stato"] == 2)$completedOrders++;
} ?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrello</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/order.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
</head>
<body>
    <?php
        require_once("components/header.php");
        create_header();
?>
    <h2>Ordini pagati</h2>
    <button class="buyer">
        Mostra ordini (<?= $paidOrders ?>)
        <span class="material-symbols-outlined">arrow_drop_down</span>
    </button>
    <section class="hidden">
    <?php
    include_once("./components/order.php");
    for($i = 0;$i<$paidOrders;$i++){ 
        order($rows[$i]);
    } ?>
    </section>

    <h2> Ordini spediti </h2>
    <button class="buyer">
        Mostra ordini (<?= $shippedOrders ?>)
        <span class="material-symbols-outlined">arrow_drop_down</span>
    </button>
    <section class="hidden">
    <?php for($i = 0;$i<$shippedOrders;$i++){ 
        order($rows[$paidOrders+$i]);
    } ?>
    </section>

    <h2> Ordini in spedizione </h2>
    <button class="buyer">
        Mostra ordini (<?= $completedOrders ?>)
        <span class="material-symbols-outlined">arrow_drop_down</span>
    </button>
    <section class="hidden">
    <?php for($i = 0;$i<$completedOrders;$i++){ 
        order($rows[$paidOrders+$shippedOrders+$i]);
        } ?>
    </section>
    <script src="./js/toggleOrders.js"></script>
</body>
</html>
