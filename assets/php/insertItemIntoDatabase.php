<?php
// Kontrollera att man är inloggad
define('INCLUDE_CHECK', true);
require 'checklogin.php';
// Om man ej är inloggad
if(!$_SESSION['id']):
	header("Location: /login.php");
// Om man är inloggad
else:
	// Stänger if-else-konstruktionen
endif;

// Anslut till MySql-databasen
require 'connect.php';

// Sätt in item i databasen
$itemId=$_GET["itemId"];
$item=$_GET["item"];
try  {
    // Förbered och exekvera statement
    $stmt = $con->prepare("INSERT INTO items (Id, Data) VALUES(:itemId, :item)");
    $stmt->execute(array(":itemId" => $itemId, ":item" => $item));

} catch (PDOException $ex) {
    logMsg($ex->getMessage(), "error");
}



?>