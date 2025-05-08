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

if(!isset($_POST["productName"]) || !isset($_FILES["3dPreview"])){
    header("Location: /sellerHome.php");
    exit();
}

$name = $_POST["productName"];

if(isset($_POST["id"]) && !empty($_POST["id"])){
    //update existing product
} else {
    //add new product
    $query = "INSERT INTO Prodotto(emailVenditore, nome, fileModello, visibile) VALUES (?,?,?,?)";
    $newPath = store_file($_FILES["3dPreview"]["name"],$_FILES["3dPreview"]["tmp_name"], Constants::$ALLOWED_3DFILE_EXTENSIONS);
    //Upload file for 3d preview
    $stmt = mysqli_prepare($connection, $query);

    $visible = 0;
    if(isset($_POST["visible"])){
        $visible = 1;
    }
    mysqli_stmt_bind_param($stmt, "sssi", $emailVenditore, $name, $newPath, $visible);
    mysqli_stmt_execute($stmt);

    //var_dump($_FILES["images"]);

    if(isset($_FILES["images"])){
        $query = "INSERT INTO ImmaginiProdotto(idProdotto, nomeFile) VALUES (?,?)";
        $prodId = mysqli_insert_id($connection);
        $stmt = mysqli_prepare($connection, $query);
        for($i = 0; $i < count($_FILES["images"]["name"]); $i++){
            $image = $_FILES["images"]["name"][$i];
            $tmpImage = $_FILES["images"]["tmp_name"][$i];
            $newPath = store_file($image, $tmpImage, Constants::$ALLOWED_IMAGE_EXTENSIONS);
            if(!empty($newPath)){
                //update reference in db
                mysqli_stmt_bind_param($stmt, "is", $prodId, $newPath);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}




header("Location: /sellerHome.php");

?>
