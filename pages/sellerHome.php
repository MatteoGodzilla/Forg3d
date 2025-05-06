<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
        header("Location: /login.php");
    }
    $email = getSessionEmail();

    #Query Prodotti
    $query_products = "SELECT * FROM Prodotto Where emailVenditore = ?";
    $stmt = mysqli_prepare($connection, $query_products);
    mysqli_stmt_bind_param($stmt,"s",$email );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    #Query Materiali
    $query_materials = "SELECT * FROM Materiale Where idVenditore = ?";
    $stmt = mysqli_prepare($connection, $query_materials);
    mysqli_stmt_bind_param($stmt,"s",$email );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $materials = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/sellerHome.css" />
</head>
<body>
    <header>
        <h1>Forg3d</h1>
    </header>
    <h2><?php echo ("Ciao ".$nome); ?></h2>
    <h3>Listino prodotti</h3>
    <a href="#">Aggiungi prodotto</a>
    <?php 
        require_once("components/sellerHomeProduct.php");
        foreach($products as $prodotto){
            sellerHomeProduct($prodotto);
        }
    ?>

    <h3>Listino materiali</h3>
    <a href="editMaterial.php">Aggiungi materiale</a>
    <?php 
        require_once("components/sellerHomeMaterial.php");
        foreach($materials as $material){
            sellerHomeMaterial($material);
        }
    ?>
</body>
</html>
