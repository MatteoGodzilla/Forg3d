<!DOCTYPE html>
<html>
	<body>
		<?php
			require_once("../php/db.php");
			require_once("components/register.php");

			if(isset($_GET) && isset($_GET["isAdmin"]) && $_GET["isAdmin"] == "true"){
				generateRegisterForm(2);
			} else {
				generateRegisterForm(1);
			}
		?>
	</body>
</html>