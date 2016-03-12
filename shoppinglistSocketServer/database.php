<?php
require_once("item.php");

class Database {

	private $db;

	function __construct() {
		$this->db = $this->connectToDb();
	}

	/**
	 *	Kopplar upp mot databasen
	 *
	 *	@return PDO $db - databasobjekt
	 */
	private function connectToDb() {
		$dataBaseName = 'dbname';
		$user = 'user';
		$host = 'localhost';
		$password = 'password';

		try {	
			// Skapa ett PDO-objekt
			$db = new PDO("mysql:host=$host;dbname=$dataBaseName;charset=utf8", $user, $password);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			// Returnera PDO-objektet
			return $db;

		} catch (PDOException $ex) {
            logMsg($ex->getMessage(), "error");
		}

	}


	/**
	 *	Lägger till ny post i inköpslistan
	 * 	@param $newItem - ny post att lägga till
	 */
	function addItem($newItem) {
        $this->db = $this->connectToDb();
		$item = $newItem->getData();
		$itemId = $newItem->getId();
		$itemIsMarked = $newItem->isMarked();

		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("INSERT INTO items (Id, Data, isMarked) VALUES(:itemId, :item, :itemIsMarked)");
			$stmt->execute(array(":itemId" => $itemId, ":item" => $item, ":itemIsMarked" => $itemIsMarked));
			if ($stmt->rowCount() == 1) {
				return true;
			}
		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
		return false;
	}

	/**
	 *	Tar bort en post från inköpslistan
	 * 	@param $item - posten att ta bort
	 */
	function removeItem($item) {
        $this->db = $this->connectToDb();
		$itemId = $item->getId();
		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("DELETE FROM items WHERE Id = :itemId");
			$stmt->execute(array(":itemId" => $itemId));
			if ($stmt->rowCount() == 1) {
				return true;
			}

		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
		return false;
	}

	/**
	 *	Uppdaterar en post i inköpslistan med nya värden
	 *	@param $item - posten att uppdatera
	 */
	function updateItem($item) {
        $this->db = $this->connectToDb();
		$itemId = $item->getId();
		$itemData = $item->getData();
		$isMarked = $item->isMarked();

		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("UPDATE items SET Data=:itemData, isMarked=:isMarked WHERE Id=:itemId");
			$stmt->execute(array(":itemData" => $itemData, ":isMarked" => $isMarked, ":itemId" => $itemId));

			if ($stmt->rowCount() == 0) {
                logMsg("VARNING: Ingenting förändrades.", "warn");
			} else if ($stmt->rowCount() == 1) {
				return true;
			}
		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
		return false;
	}

	/**
	 *	Returnerar en post i inköpslistan
	 *	@param $id - id för posten vi vill returnera
	 *	@return posten med id $id
	 */
	function getItemById($id) {
        $this->db = $this->connectToDb();
		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("SELECT Data, isMarked FROM items WHERE Id=:id");
			$stmt->execute(array(":id" => $id));

			if ($stmt->rowCount() == 0) {
                logMsg("VARNING: Ingen post med id: $id fanns i databasen.", "warn");
				return false;
			} else if ($stmt->rowCount() == 1) {
				$item = $stmt->fetch(PDO::FETCH_ASSOC);

				// Konvertera TINYINT(0) till false och TINYINT(1) till true
				$isMarked = $item['isMarked'] ? true : false;
				return new Item($item['Data'], $id, $isMarked);
			}

		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
	}

	/**
	 *	Returnerar en array med alla poster i inköpslistan
	 *	@return array med alla poster i inköpslistan
	 */
	function getAllItems() {
        $this->db = $this->connectToDb();
		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("SELECT Data, Id, isMarked FROM items");
			$stmt->execute();
			
			$items = array();
			while ($fetchedItems = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$items[] = new Item($fetchedItems['Data'],$fetchedItems['Id'], $fetchedItems['isMarked']);
			}

			return $items;

		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
	}
	

	/**
	 *	Rensar databasen
	 */
	function clear() {
        $this->db = $this->connectToDb();
		try  {
			// Förbered och exekvera statement
			$stmt = $this->db->prepare("DELETE FROM items");
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				return true;
			}
		} catch (PDOException $ex) {
			logMsg($ex->getMessage(), "error");
		}
		return false;
	}
}


?>