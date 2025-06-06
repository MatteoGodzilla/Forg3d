<?php
session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() ){
    header("Location: /login.php");
    exit();
}

if(getUserType()!=UserType::SELLER->value){
     header("Location: /sellerHome.php");
     exit();
}

$email = getSessionEmail();
$query_orders = "SELECT P.id as idProdotto,M.nomeColore as variante, P.nome as nome ,
O.emailCompratore as buyer ,O.stato as stato,OI.quantita as quantita,OI.prezzo as prezzo,
FIRST_VALUE(I.nomeFile) OVER (PARTITION BY  P.id, P.nome, O.emailCompratore, OI.quantita,O.stato,M.nomeColore,OI.prezzo) AS immagine 
FROM Ordine O INNER JOIN InfoOrdine OI ON OI.idOrdine = O.id
INNER JOIN Variante V on V.id = OI.idVariante INNER JOIN Prodotto P ON V.idProdotto = P.id
LEFT JOIN ImmaginiProdotto I ON P.id = I.idProdotto INNER JOIN Materiale M ON M.id = V.idMateriale
WHERE O.emailVenditore = ? ORDER BY O.stato";

$stmt = mysqli_prepare($connection, $query_orders);
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
    <link rel="stylesheet" href="./css/order.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
</head>
<body>
    <form action="./api/convertToOrder.php" method="POST">
        <?php
            require_once("components/header.php");
            create_header();
        ?>
        <h2>Ordini da spedire</h2>
            <?php
            include_once("./components/order.php");
            foreach($rows as $order){ 
                order($order);
            } ?>
        <h2>Ordini spediti</h2>
        <h2>Ordini completati</h2>

    </form>
</body>
</html>