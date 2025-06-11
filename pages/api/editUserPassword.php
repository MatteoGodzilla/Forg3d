<?php
    require_once("../../php/db.php");
    require_once("../../php/session.php");
    require_once("../../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }

    if(!isset($_POST["password_old"]) || !isset($_POST["password_new"])){
        header("Location: /profile.php");
    }

    //check userType
    $query = "";
    $type = getUserTYpe();
    $clearPassword = $_POST["password_old"];
    $email = getSessionEmail();
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
            header("Location: /login.php");
    }

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if(isset($rows[0])){
        $dbPassword = $rows[0]["password"];
        if(password_verify($clearPassword, $dbPassword)){
            $query = "UPDATE Utente set password = ? WHERE email = ?";
            $hashedPassword = password_hash($_POST["password_new"],PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt,"ss",$hashedPassword, $email);
            mysqli_stmt_execute($stmt);
            header("Location: ".feedback("/profile.php",AlertType::SUCCESS->value,"Nuova password salvata!"));
        }
        else{
            header("Location: ".feedback("/profile.php",AlertType::ERROR->value,"Errore nell'aggiornare la password: vecchia password errata!"));
        }
    }
    else{
        header("Location: ".feedback("/profile.php",AlertType::ERROR->value,"Un errore si è verificato, riprovare"));
    }
?>