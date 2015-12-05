<?php
// Returnerar senaste itemId från databasen
require 'connect.php';
try  {
	// Förbered och exekvera statement
	$stmt = $con->prepare("SELECT Id FROM items ORDER BY Id DESC");
	$stmt->execute();
	if ($stmt->rowCount() == 1) {
		$itemId = $stmt->fetch(PDO::FETCH_ASSOC);
		echo $itemId['Id'];
	}

} catch (PDOException $ex) {
	logMsg($ex->getMessage(), "error");
}
?>