<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

#query (seleziona venditori non verificati, stato = 0)
$query = "SELECT emailUtente,nome,cognome,telefono FROM Venditore INNER JOIN Utente ON Venditore.emailUtente = Utente.email WHERE stato=0";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$sellers = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/request.css" />
    <link rel="stylesheet" href="./css/adminInterface.css" />
</head>
<body>
	<?php 
		include_once("./components/header.php");
		create_header();
	?>
    <h2>Richieste in attesa</h2>
    <?php 
        require_once("components/sellerRequest.php");
        foreach($sellers as $seller){
            sellerRequest($seller);
        }
    ?>
    <script src="js/darkMode.js"></script>
</body>
</html>
