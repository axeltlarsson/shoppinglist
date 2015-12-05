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
try  {
    // Förbered och exekvera statement
    $stmt = $con->prepare("DELETE FROM items WHERE Id=:itemId");
    $stmt->execute(array(":itemId" => $itemId));

} catch (PDOException $ex) {
    logMsg($ex->getMessage(), "error");
}
?>
