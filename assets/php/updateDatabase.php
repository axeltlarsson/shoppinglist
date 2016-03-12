<?php
// Anslut till MySql-databasen
require 'connect.php';

// Ta emot och escapa items från inköpslistan
$itemId=$_GET["itemId"];
$item=$_GET["item"];
$isMarked = $_GET["isMarked"];

try  {
	// Förbered och exekvera statement
	$stmt = $con->prepare('UPDATE items SET Id=:itemId, Data=:item, isMarked=:isMarked WHERE Id=:id');
	$stmt->execute(array(":itemId" => $itemId, ":item" => $item, ":isMarked" => $isMarked, ":id" => $itemId));

} catch (PDOException $ex) {
	logMsg($ex->getMessage(), "error");
}
?>