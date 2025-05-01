<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Forg3d Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./css/login.css" />
    </head>
    <body>
        <header>
            <h1>Forg3d</h1>
        </header>
        <h2>Login</h2>
		<?php
			require_once("../php/db.php");
			require_once("components/login.php");

			if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
				generateLoginForm(true);
			} else {
				generateLoginForm(false);
			}
        ?>
        <a href="#">Sign Up</a>
        <script>
            //TODO: add this script only when entering as a non-admin
            const toggleButton = document.querySelector("#typeSwitcher");
            const submitButton = document.querySelector("input[type='submit']");
            const formType = document.querySelector("input[name='type']");
            let type = 0;

            //console.log(toggleButton);
            //console.log(submitButton);
            toggleButton.onclick = (evt) => {
                if(type == 0){
                    type = 1;
                    formType.value = type;
                    //toggleButton.innerText = "Venditore";
                    toggleButton.classList.add("seller");
                    toggleButton.classList.remove("buyer");
                    submitButton.classList.add("seller");
                    submitButton.classList.remove("buyer");
                } else {
                    type = 0;
                    formType.value = type;
                    //toggleButton.innerText = "Compratore";
                    toggleButton.classList.remove("seller");
                    toggleButton.classList.add("buyer");
                    submitButton.classList.remove("seller");
                    submitButton.classList.add("buyer");
                }
                evt.preventDefault();
                console.log(type);
            }
            toggleButton.click();
        </script>
	</body>
</html>
