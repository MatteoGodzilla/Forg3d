<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

#query (seleziona reports non ispezionati non verificati, stato = 0)
$query = "SELECT idProdotto,nome,emailVenditore,COUNT(idProdotto) as totalReports FROM Segnalazione INNER JOIN SegnalazioneProdotto ON
Segnalazione.id = SegnalazioneProdotto.idSegnalazione INNER JOIN Prodotto ON
Prodotto.id = SegnalazioneProdotto.idProdotto  WHERE ispezionata=0 GROUP BY idProdotto";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/reports.css" />
    <link rel="stylesheet" href="./css/header.css" />
</head>
<body>
	<?php 
		include_once("./components/header.php");
		create_header();
	?>
    <h2>Segnalazione prodotti</h2>
    <?php 
        require_once("components/productReport.php");
        foreach($products as $product){
            productReport($product);
        }
    ?>
</body>
</html>