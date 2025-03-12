<!DOCTYPE html>
<html>
	<body>

		<?php
			require_once("php/db.php");

			$query = "SELECT * FROM Utente";
			$result = $connection->execute_query($query);
			$arr = $result->fetch_all();

			foreach($arr as $line){
		?>

			<p>
				<?php echo($line["email"]);?>
			</p>

		<?php
			}
		?>
	</body>
</html>