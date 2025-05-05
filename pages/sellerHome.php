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
</head>
<body>
    <h1><?php echo ("Ciao ".$nome); ?></h1>
    <h2>I tuoi Prodotti</h2>
    <?php foreach ($products as $prodotto): ?>
        <p><strong>Nome:</strong> <?php echo ($prodotto['nome']); ?></p>
        <p><strong>Pubblico:</strong> <?php echo (($prodotto['visibile']) ? "Si":"No") ?> </p>
    <?php endforeach; ?>

    <h2>I tuoi Materiali</h2>
    <?php foreach ($materials as $materiale): ?>
        <p><strong>Nome:</strong> <?php echo ($materiale['nomeColore']); ?></p>
        <p><strong>Tipo:</strong> <?php echo ($materiale['tipologia']); ?></p>
        <p><strong>Hex code:</strong> <?php echo ($materiale['hexColore']); ?></p>
        <?php endforeach; ?>
</body>
</html>