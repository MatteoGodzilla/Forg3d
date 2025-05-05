<?php
session_start();
require_once("../php/db.php");
require_once("../php/session.php");

#non autorizzato
if(!utenteLoggato() || getUserType()!=UserType::SELLER->value){
    header("Location: /");
    exit();
}

#edit
if(isset($_GET["id"])){
    $email = getSessionEmail();
    $query = "SELECT * FROM Materiale WHERE id=? AND idVenditore=?";
    #execute
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"is", $_GET["id"],$email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if(isset($rows[0])){
        $materiale = $rows[0];
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica prodotto</title>
</head>
<body>
    <h1>Forg3d</h1>
    <h2>Modifica Materiale</h2>
    <form action="/api/handleMaterial.php"<?php echo isset($_GET["id"]) ?  '?id='.$_GET["id"] : ''?> method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $idProduct ?>"/>

    <label for="nome">Nome Materiale*</label><br>
    <input type="text" id="nome" name="nome" value="<?= isset($materiale) ? htmlspecialchars($materiale['nome']) :'' ?>" required/>
    <hr>

    <label for="tipo">Tipologia Materiale*</label><br>
    <input type="text" id="tipo" name="tipo" value="<?= isset($materiale) ? htmlspecialchars($materiale['tipologia']) :'' ?>" required/>
    <hr>

    <label for="tipo">Colore*</label><br>
    <input type="color" id="colore" name="colore" value="<?= isset($materiale) ? htmlspecialchars($materiale['hexColore']) :'' ?>" required/>
    <hr>

    <button type="submit"><?= isset($_GET["id"]) ? "Salva modifiche":"Crea nuovo materiale"?></button>
</form>
</body>
</html>