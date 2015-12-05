<?php
require_once("/usr/local/bin/web/testServer/command/clearListCommand.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class UpdateItemCommandTest extends PHPUnit_Framework_TestCase {
	private $updateItemCommand, $db, $itemArray;	

	public function setUp() {
		$this->db = new Database();
		// Save the state of the database
		$this->itemArray = $this->db->getAllItems();

		// Clear the database
		$this->db->clear();

		// Add 30 items to the database
		for ($i=70; $i <= 99; $i++) { 
			$itemId = "item" . $i;
			$itemData = "itemdata" . $i;
			$this->db->addItem(new Item($itemData, $itemId, true));
		}
	}

	public function testClearListCommand() {
		// Make sure that database is not empty
		$this->assertEquals(count($this->db->getAllItems()), 30, "Database does not contain 30 elements.");

		// Make and execute ClearListCommand
		$clearListCommand = new ClearListCommand($this->db);
		$clearListCommand->execute();
		
		// Database should now be empty
		$this->assertEquals(count($this->db->getAllItems()), 0, "Database is not empty.");		

	}

	public function tearDown() {
		// Clear, then restore the database
		$this->db->clear();
		foreach ($this->itemArray as $item) {
			$this->db->addItem($item);
		}
	}
}
?>