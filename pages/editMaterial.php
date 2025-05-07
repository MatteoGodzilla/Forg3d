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
    <link rel="stylesheet" href="./css/materialForm.css" />
    <title>Modifica prodotto</title>
</head>
<body>
    <h1>Forg3d</h1>
    <h2>Modifica Materiale</h2>
    <form action="/api/handleMaterial.php<?php echo isset($_GET["id"]) ?  '?id='.$_GET["id"] : ''?>" method="POST" enctype="multipart/form-data">

    <label for="nome">Nome Materiale*</label><br>
    <input type="text" id="nome" name="nome" value="<?= isset($materiale) ? htmlspecialchars($materiale['nomeColore']) :'' ?>" required/>


    <label for="tipo">Tipologia Materiale*</label><br>
    <input type="text" id="tipo" name="tipo" value="<?= isset($materiale) ? htmlspecialchars($materiale['tipologia']) :'' ?>" required/>

    <label for="tipo">Colore*</label><br>
    <input type="color" id="colore" name="colore" value="#<?= isset($materiale) ? htmlspecialchars($materiale['hexColore']) :'' ?>" required/>

    <div>
        <svg width="100px "height="100px"> 
            <title>Indicatore Colore</title>
            <ellipse id ="color-indicator" rx="46px" ry="46px" cx="50px" cy="50px" 
                stroke="black" stroke-width="4"
                fill="#<?= isset($materiale) ? htmlspecialchars($materiale['hexColore']) :'' ?>" 
            /> 
        </svg>
        <input type="text" maxlength="7" id="colore-manuale" name="colore-manuale" value="<?= isset($materiale) ? htmlspecialchars($materiale['hexColore']) :'' ?>" required/>
    </div>

    <input type="submit" value="<?= isset($_GET["id"]) ? "Salva modifiche":"Crea nuovo materiale"?>">
</form>
</body>

<!--Script per sincronizzare elisse e color picker (TODO: Capire dove spostare questo)-->
<script>
    const colorPicker = document.getElementById("colore");
    const ellipse = document.getElementById("color-indicator");
    const textColor = document.getElementById("colore-manuale");

    colorPicker.addEventListener("input", () => {
      ellipse.setAttribute("fill", colorPicker.value);
      textColor.value = colorPicker.value;
    });

    textColor.addEventListener("input", () => {
      var color= textColor.value;
      //regex controlla se il testo è un colore valido
      if (/^#[0-9a-fA-F]{6}$/.test(color)){
        ellipse.setAttribute("fill", color);
        colorPicker.value= color;
      }
    });
    
</script>
</html>