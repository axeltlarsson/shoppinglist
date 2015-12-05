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

// Importera "items" från databasen "shoppinglist"
try  {
    // Förbered och exekvera statement
    $stmt = $con->prepare("SELECT * FROM items ORDER BY items.Id");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = $row['Data'];
		$itemId = $row['Id'];
		$isMarked = $row['isMarked'];
		
		if ($isMarked) {
			echo '<div id=' . $itemId . ' class="marked">';
		} else {
			echo '<div id=' . $itemId . '>';	
		}
		echo '<p onclick="legacyShoppinglist.toggleBought(\'' . $itemId . '\')" class="item">' . $item . '</p>';
		echo "</div>";
    }

} catch (PDOException $ex) {
    logMsg($ex->getMessage(), "error");
}
?>