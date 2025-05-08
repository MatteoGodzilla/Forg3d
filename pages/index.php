<?php
session_start();
require_once("../php/db.php");
require_once("../php/constants.php");
require_once("../php/session.php");

$email = getSessionEmail();

#Query Nome utente 
$query_nome = "SELECT * FROM Utente Where email = ?";
$stmt = mysqli_prepare($connection, $query_nome);
mysqli_stmt_bind_param($stmt,"s",$email );
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
$nome = "";
foreach ($rows as $row) {
    $nome = $row["nome"];
}
?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<title>Forg3d Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css/home.css">
	</head>
	<body>
		<?php 
			include_once("./components/header.php");
			create_header();
		?>
		<input type="search" name="search" id="search" placeholder="Ricerca">
		<?php
			require_once("./components/homeProductPlaceholder.php");
			for ($i=1; $i <= 100; $i++) {
				generateProductPlaceholder($i);
			}
		?>
	</body>
</html>
