<?php
session_start();
require_once("../php/db.php");
require_once("./api/homeProduct.php");
include_once("./components/productList.php");
require_once("../php/constants.php");
require_once("../php/session.php");

$email = getSessionEmail();
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
        foreach ($prodotti as $product) {
            generateProductList($product);
        }
        ?>
	</body>
</html>
