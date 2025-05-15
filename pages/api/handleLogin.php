<?php
session_start();
require_once("../../php/db.php");
require_once("../../php/session.php");
require_once("../../php/error.php");

const REDIRECT_FAILED = "../login.php";
const REDIRECT_COMPRATORE = "../index.php";
const REDIRECT_VENDITORE = "../sellerHome.php";
const REDIRECT_ADMIN = "../adminHome.php";

if(utenteLoggato()){
    header("Location: /");
    exit();
}

if(!isset($_POST) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["type"])){
    header("Location:".error($redirectFailed,"Errore durante il login,i dati forniti erano incompleti"));
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
        $query = "SELECT U.email, V.stato, U.password FROM Utente U
            JOIN Venditore V ON (U.email = V.emailUtente)
            WHERE U.email = ?
        ";
        break;
    case UserType::ADMIN->value:
        $query = "SELECT U.email , U.password FROM Utente U
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


    $dbPassword = $rows[0]["password"];
    if(password_verify($clearPassword, $dbPassword)){
        //login was successful
        //set session token

        switch($type){
            case UserType::BUYER->value:
                setSession($email,UserType::BUYER->value);
                header("Location:".REDIRECT_COMPRATORE);
                exit();
            case UserType::SELLER->value:
                if($rows[0]["stato"]!=3){
                    setSession($email,UserType::SELLER->value);
                    header("Location:".REDIRECT_VENDITORE);
                }
                else{
                    //banned
                    header("Location:".error(REDIRECT_FAILED,"L'utente con cui stai provando a loggarti è stato bannato"));
                }

                exit();
            case UserType::ADMIN->value:
                setSession($email,UserType::ADMIN->value);
                header("Location:".REDIRECT_ADMIN);
                exit();
        }
    }
}

//Redirect back
header("Location:".error(REDIRECT_FAILED,"email o password errati!"));
exit();

?>
