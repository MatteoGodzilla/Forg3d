<?php
require_once("../../php/db.php");

$redirectFailed = "../index.php";
$redirectCompratore = "../page2.php";
$redirectVenditore = "../page3.php";

if(!isset($_POST) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["type"])){
    header("Location:".$redirectFailed);
    exit();
}

$email = $_POST["email"];
$clearPassword = $_POST["password"];
$type = $_POST["type"];

if($type == "0"){
    //Compratore

    $query = "SELECT U.email, U.password FROM Utente U
        JOIN Compratore C ON (U.email = C.emailUtente)
        WHERE U.email = ?
    ";
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
            //set session token

            //Redirect
            header("Location:".$redirectCompratore);
            exit();
        }
    }
} elseif ($type == "1") {
    //Venditore

    //Redirect
    //header("Location:".$redirectVenditore);
    exit();
}

//Redirect back
header("Location:".$redirectFailed);
exit();

?>