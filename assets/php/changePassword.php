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
	
	
// Kopplar upp mot databasen
define('INCLUDE_CHECK', true);
require 'connect.php';
// Ändra lösenordet: 
$salt = "salt";

$password = "password";
$hash = hash('whirlpool', $password . $salt);
//echo $hash;
mysql_query("UPDATE members SET id='1', user='user', password='$hash' WHERE id='1'") or die(mysql_error());

?>
<!DOCTYPE html>

<html>
	<head>
		<!-- Logo -->
		<LINK REL="SHORTCUT ICON" HREF="/assets/images/animated_favicon1.gif" />
		
		<meta charset="UTF-8" />

		
		<title>Inköpslistan</title>
	</head>

	<form id="inputForm" name="InputForm" onsubmit="return addItem('shoppinglist')">
		<input type="text" name="AddItemBox" maxlength="24" id="addItemBox">	
	</form>


<footer>
</footer>
</body>
</html>
