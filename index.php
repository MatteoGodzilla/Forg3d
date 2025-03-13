<!DOCTYPE html>
<html>
	<body>
		<?php
			require_once("php/db.php");
			require_once("php/test.php");

			$query = "SELECT * FROM Utente";
			$result = $connection->execute_query($query);
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			//var_dump($arr);

			foreach($arr as $line){
				generateP();
			}
		?>
	</body>
</html>