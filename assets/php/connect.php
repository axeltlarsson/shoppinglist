<?php
require_once("utils.php");
$dataBaseName = 'dbname';
$user = 'dbname';
$host = 'localhost';
$password = 'password';

try {	
	// Skapa ett PDO-objekt
	$con = new PDO("mysql:host=$host;dbname=$dataBaseName;charset=utf8", $user, $password);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $ex) {
	logMsg($ex->getMessage(), "error");
}
?>