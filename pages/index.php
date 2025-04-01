<!DOCTYPE html>
<html>
	<body>
		<?php
			require_once("../php/db.php");
			require_once("components/login.php");

			if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
				generateLoginForm(true);
			} else {
				generateLoginForm(false);
			}
		?>
	</body>
</html>