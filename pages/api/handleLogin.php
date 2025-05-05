<?php
require_once("../../php/db.php");
require_once("../../php/constants.php");
require_once("../../php/utils/session.php");

const REDIRECT_FAILED = "../login.php";
const REDIRECT_COMPRATORE = "../page2.php";
const REDIRECT_VENDITORE = "../page3.php";
const REDIRECT_ADMIN = "../page4.php";

if(utenteLoggato()){
    header("Location: /");
    exit();
}

if(!isset($_POST) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["type"])){
    header("Location:".$redirectFailed);
    exit();
}

$email = $_POST["email"];
$clearPassword = $_POST["password"];
$type = (int)$_POST["type"];

//Check login
$query = "";
switch ($type) {
    case UserType::BUYER->value:
        $query = "SELECT U.email, U.password FROM Utente U
            JOIN Compratore C ON (U.email = C.emailUtente)
            WHERE U.email = ?
        ";
        break;
    case UserType::SELLER->value:
        $query = "SELECT U.email, U.password FROM Utente U
            JOIN Venditore V ON (U.email = V.emailUtente)
            WHERE U.email = ?
        ";
        break;
    case UserType::ADMIN->value:
        $query = "SELECT U.email, U.password FROM Utente U
            JOIN Admin A ON (U.email = A.emailUtente)
            WHERE U.email = ?
        ";
        break;
    default:
        //Redirect back
        header("Location:".REDIRECT_FAILED);
        exit();
}

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt,"s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($rows[0])){
    //it exists

    // To generate the password, run
    // php -a
    // echo(password_hash(<password>,PASSWORD_DEFAULT));

    $dbPassword = $rows[0]["password"];
    if(password_verify($clearPassword, $dbPassword)){
        //login was successful
        //set session token

        switch($type){
            case UserType::BUYER->value:
                header("Location:".REDIRECT_COMPRATORE);
                setSession($email,UserType::BUYER->value);
                exit();
            case UserType::SELLER->value:
                header("Location:".REDIRECT_VENDITORE);
                setSession($email,UserType::SELLER->value);
                exit();
            case UserType::ADMIN->value:
                header("Location:".REDIRECT_ADMIN);
                setSession($email,UserType::ADMIN->value);
                exit();
        }
    }
}

//Redirect back
header("Location:".REDIRECT_FAILED);
exit();

?>
