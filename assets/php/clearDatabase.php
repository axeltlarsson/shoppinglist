<?php
// Kontrollera att man är inloggad
define('INCLUDE_CHECK', true);
require 'checklogin.php';
// Om man ej är inloggad
if(!$_SESSION['id']):
	header("Location: login.php");
// Om man är inloggad
else:
	// Stänger if-else-konstruktionen
endif;

// Anslut till MySql-databasen
require 'connect.php';

try  {
    $con->exec("DELETE FROM items");

} catch (PDOException $ex) {
    logMsg($ex->getMessage(), "error");
}

echo "Databas rensad.";
mysql_close($con);
?>