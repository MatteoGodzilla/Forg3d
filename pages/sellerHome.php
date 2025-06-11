<?php
    session_start();
    require_once("../php/db.php");
    require_once("../php/session.php");

    if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
        header("Location: /login.php");
    }
    $email = getSessionEmail();

    #Query Prodotti
    $query_products = "SELECT DISTINCT P.*, FIRST_VALUE(I.nomeFile) 
                        OVER (PARTITION BY P.id, P.nome, P.fileModello, P.visibile) AS immagine 
                        FROM Prodotto P 
                        LEFT JOIN ImmaginiProdotto I on I.idProdotto = P.id
                        Where emailVenditore = ? AND visibile > 0";
    $stmt = mysqli_prepare($connection, $query_products);
    mysqli_stmt_bind_param($stmt,"s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    #Query Materiali
    $query_materials = "SELECT * FROM Materiale Where idVenditore = ? AND visibile > 0";
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

    #Query Followers
    $query_followers = "SELECT COUNT(emailCompratore) AS followers FROM Follow WHERE emailVenditore = ?";
    $stmt = mysqli_prepare($connection, $query_followers);
    mysqli_stmt_bind_param($stmt,"s",$email );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $followers = mysqli_fetch_assoc($result)["followers"];

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/sellerHome.css" />
    <link rel="stylesheet" href="./css/header.css" />
</head>
<body>
    
    <?php
        require_once("components/header.php");
        create_header();
    ?>

    <h2>Info</h2>
    <div class="info">
        <p>Followers: <span><?= $followers ?></span></p>
    </div>

    <main>
        <h2>Listino prodotti</h2>
        <a href="editProduct.php">Aggiungi prodotto</a>
        <?php 
            require_once("components/sellerHomeProduct.php");
            foreach($products as $prodotto){
                sellerHomeProduct($prodotto);
            }
        ?>
    </main><aside>
    <h2>Listino materiali</h2>
    <a href="editMaterial.php">Aggiungi materiale</a>
    <?php 
        require_once("components/sellerHomeMaterial.php");
        foreach($materials as $material){
            sellerHomeMaterial($material);
        }
    ?>
    </aside>
</body>
</html>
