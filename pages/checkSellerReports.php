<?php 

session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#controllo autorizzazione
if(!utenteLoggato() || getUserType()!=UserType::ADMIN->value){
    header("Location: /login.php?isAdmin=1");
}

#query (seleziona reports non ispezionati non verificati, stato = 0)
$query = "SELECT nome,cognome,emailVenditore,telefono,COUNT(emailVenditore) as totalReports FROM Segnalazione INNER JOIN SegnalazioneVenditore ON
Segnalazione.id = SegnalazioneVenditore.idSegnalazione INNER JOIN Utente ON
Utente.email = SegnalazioneVenditore.emailVenditore  WHERE ispezionata=0 GROUP BY emailVenditore";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/reports.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/adminInterface.css" />
</head>
<body>
	<?php 
		include_once("./components/header.php");
		create_header();
	?>
    <h2>Segnalazioni Venditori</h2>
    <?php 
        require_once("components/sellerReport.php");
        foreach($reports as $report){
            sellerReport($report);
        }
    ?>
</body>
</html>