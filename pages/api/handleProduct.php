<?php 
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("../../php/file_utils.php");

if(!utenteLoggato() || getUserType() != UserType::SELLER->value ){
    header("Location: /");
    exit();
}

$emailVenditore = getSessionEmail(); 

if(!isset($_POST["productName"]) || !isset($_POST["defaultVariant"])){
    header("Location: /sellerHome.php");
    exit();
}

$name = $_POST["productName"];
$visible = 1;
if(isset($_POST["visible"])){
    $visible = 2;
}
$defaultVariant = $_POST["defaultVariant"];

if(isset($_POST["id"]) && !empty($_POST["id"])){
    //try to update existing product
    $id = $_POST["id"];
    //Check that the product is not banned
    $query_check = "SELECT visibile FROM Prodotto WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query_check);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $check = mysqli_fetch_assoc($result);

    if($check["visibile"] == 0){
        //echo("No edit priviledges for you, dear seller");
        header("Location: /sellerHome.php");
        exit();
    }

    $query_update = "UPDATE Prodotto SET nome=?, visibile=?, varianteDefault=? WHERE id=?";
    $stmt = mysqli_prepare($connection, $query_update);
    mysqli_stmt_bind_param($stmt, "siii", $name, $visible, $defaultVariant, $id);
    mysqli_stmt_execute($stmt);
    
} else {
    //add new product 
    
    $query = "INSERT INTO Prodotto(emailVenditore, nome, visibile, varianteDefault) VALUES (?,?,?,?)";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "ssii", $emailVenditore, $name, $visible, $defaultVariant);
    mysqli_stmt_execute($stmt);

    $id = mysqli_insert_id($connection);
}

//Set 3d Preview file
if(isset($_FILES["3dPreview"]) && filesize($_FILES["3dPreview"]["tmp_name"]) > 0){
    $query = "UPDATE Prodotto SET fileModello = ? WHERE id = ?";
    $newPath = store_file($_FILES["3dPreview"]["name"],$_FILES["3dPreview"]["tmp_name"], Constants::$ALLOWED_3DFILE_EXTENSIONS);
    //Upload file for 3d preview 
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $newPath, $id);
    mysqli_stmt_execute($stmt);
}

//Remove marked images
if(isset($_POST["deletedImages"])){
    $query = "DELETE FROM ImmaginiProdotto WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    for($i = 0; $i < count($_POST["deletedImages"]); $i++ ){
        $imageId = intval($_POST["deletedImages"][$i]); 
        mysqli_stmt_bind_param($stmt, "i", $imageId); 
        mysqli_stmt_execute($stmt);
    }
}

//Add new images
if(isset($_FILES["images"])){
    $query = "INSERT INTO ImmaginiProdotto(idProdotto, nomeFile) VALUES (?,?)";
    $stmt = mysqli_prepare($connection, $query);
    for($i = 0; $i < count($_FILES["images"]["name"]); $i++){
        $image = $_FILES["images"]["name"][$i];
        $tmpImage = $_FILES["images"]["tmp_name"][$i];
        if(filesize($tmpImage) > 0){
            $newPath = store_file($image, $tmpImage, Constants::$ALLOWED_IMAGE_EXTENSIONS);
            if(!empty($newPath)){
                //update reference in db
                mysqli_stmt_bind_param($stmt, "is", $id, $newPath);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}

//Handle Variants
if(isset($_POST["materialIds"]) && isset($_POST["variantCosts"])){
    $query_remove = "DELETE FROM Variante WHERE idProdotto = ?";
    $stmt = mysqli_prepare($connection, $query_remove);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt); 


    $query_add = "INSERT INTO Variante(idProdotto, idMateriale, prezzo) VALUES (?,?,?)";
    $stmt = mysqli_prepare($connection, $query_add);
    $defaultReadded = false;
    $lastMaterialId = -1;
    for($i = 0; $i < count($_POST["materialIds"]); $i++){
        $materialId = $_POST["materialIds"][$i];
        $variantCost = $_POST["variantCosts"][$i];

        if(!isset($_POST["removeVariant"]) || !in_array($materialId, $_POST["removeVariant"])){
            mysqli_stmt_bind_param($stmt, "iii", $id, $materialId, $variantCost);
            mysqli_stmt_execute($stmt);

            if($materialId === $defaultVariant){
                $defaultReadded = true;
            }

            $lastMaterialId = $materialId;
        }
    }

    if(!$defaultReadded){
        //User has decided to remove the default variant, so we set it to the last variant by default
        $query_readd_default = "UPDATE Prodotto SET varianteDefault = ? WHERE id = ?";   
        $stmt = mysqli_prepare($connection, $query_readd_default);
        mysqli_stmt_bind_param($stmt, "ii", $lastMaterialId, $id);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: /sellerHome.php");

?>
