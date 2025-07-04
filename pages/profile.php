<?php
    require_once("../php/db.php");
    require_once("../php/session.php");
    require_once("../php/feedback.php");
    session_start();

    if(!utenteLoggato()){
        header("Location: /login.php");
    }
    $email = getSessionEmail();

    $query = "SELECT * FROM Utente WHERE email=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"s",$email );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if(sizeof($rows)>0){
        $user = $rows[0];
    }
    else{
        terminaSessione();
        header("Location: /login.php");
    }

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Registrazione</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/header.css" />
        <link rel="stylesheet" href="./css/buttons.css" />
        <link rel="stylesheet" href="./css/popups.css" />
        <link rel="stylesheet" href="./css/registerForm.css" />
    </head>
	<body>
        <?php
            require_once("components/header.php");
            require_once("components/userInfo.php");
            require_once("../php/session.php");
            include_once("./components/popups.php");
            include_once("./../php/constants.php");
            create_header();
            if(isset($_GET["message"]) && isset($_GET["messageType"])){ 
                create_popup($_GET["message"],$_GET["messageType"]);
            } 
            userInfoForm($user);
        ?>
	</body>
    <script src="./js/checkRepeatedPassword.js" ></script>
    <script src="./js/darkMode.js"></script>
</html>

