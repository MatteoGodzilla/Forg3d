<?php
require_once("../../php/db.php");

const REDIRECT_FAILED = "../index.php";
const REDIRECT_COMPRATORE = "../page2.php";
const REDIRECT_VENDITORE = "../page3.php";
const REDIRECT_ADMIN = "../page4.php";

if(!isset($_POST) || !isset($_POST["name"]) || !isset($_POST["surname"]) || !isset($_POST["cellphone"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["type"])){
    header("Location:".$redirectFailed);
    exit();
}


$email = $_POST["email"];
$type = $_POST["type"];
$clearPassword = $_POST["password"];

$hashedPassword = password_hash($clearPassword,PASSWORD_DEFAULT);

$BaseQuery = "INSERT INTO Utente(nome,cognome,email,password,telefono) VALUES(?,?,?,?,?)";
$SpecificQuery = "";
$token = "";

switch ($type) {
    case "0":
        $SpecificQuery = "INSERT INTO Compratore(emailUtente) VALUES(?)";
        break;
    case "1":
        $SpecificQuery = "INSERT INTO Venditore(emailUtente) VALUES(?)";
        break;
    case "2":
        if(!isset($_POST["admin_token"])){
            header("Location:".$redirectFailed);
            exit();
        }
        $token = $_POST["admin_token"];
        $SpecificQuery = "SELECT used from AdminToken WHERE token = ?";
        break;
    default:
        //Redirect back
        header("Location:".REDIRECT_FAILED);
        exit();
}



echo "lol";
if($type!="2"){
    //transaction of 2 inserts
    $connection->begin_transaction();
    try{
        $stmt = mysqli_prepare($connection, $BaseQuery); //base query
        mysqli_stmt_bind_param($stmt,"sssss", $_POST["name"],$_POST["surname"],$email,$hashedPassword,$_POST["cellphone"]);
        mysqli_stmt_execute($stmt);
    
        $stmt = mysqli_prepare($connection, $SpecificQuery); //specific Buyer/Seller query
        mysqli_stmt_bind_param($stmt,"s", $email);
        mysqli_stmt_execute($stmt);
        $connection->commit();
        header("Location:".REDIRECT_COMPRATORE);
    }catch (mysqli_sql_exception $exception) {
        $connection->rollback();
    }

}else{
    $stmt = mysqli_prepare($connection, $SpecificQuery); //check token
    mysqli_stmt_bind_param($stmt,"s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if(!isset($rows[0])){
        //invalid token
        header("Location:".$redirectFailed);
        exit();
    }

    try{
        //2 inserts + one update transaction
        $connection->begin_transaction();
        $stmt = mysqli_prepare($connection, $BaseQuery); //base query
        mysqli_stmt_bind_param($stmt,"sssss", $_POST["name"],$_POST["surname"],$email,$hashedPassword,$_POST["cellphone"]);
        mysqli_stmt_execute($stmt);

        $SpecificQuery = "UPDATE AdminToken SET used=1, email=? WHERE token=?";
        $stmt = mysqli_prepare($connection, $SpecificQuery); //set token as used
        mysqli_stmt_bind_param($stmt,"ss", $email, $token);
        mysqli_stmt_execute($stmt);

        $SpecificQuery = "INSERT INTO Admin(emailUtente) VALUES(?)";
        $stmt = mysqli_prepare($connection, $SpecificQuery); //set token as used
        mysqli_stmt_bind_param($stmt,"s", $email);
        mysqli_stmt_execute($stmt);
    
        $connection->commit();
        header("Location:".REDIRECT_ADMIN);
    }catch (mysqli_sql_exception $exception) {
        $connection->rollback();
        header("Location:".REDIRECT_FAILED);
    }
    
}


?>