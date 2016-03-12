<?php
require_once("/usr/local/bin/web/testServer/command/removeItemCommand.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class RemoveItemTest extends PHPUnit_Framework_TestCase {
	private $removeItemCommand, $item, $db;
	
	public function setUp() {
		$this->db = new Database();
		$this->item = new Item("margarin 300g", "item35", true);
		$this->db->addItem($this->item);

		$this->removeItemCommand = new RemoveItemCommand($this->item, $this->db);
	}

	public function testRemoveItemCommand() {
		// Test that item is in database
		$this->assertEquals($this->item, $this->db->getItemById($this->item->getId()), "Item not in database.");
		// Remove item 
		$this->removeItemCommand->execute();
		// Item should now be removed from the database
		$itemArray = $this->db->getAllItems();
		$this->assertFalse(in_array($this->item, $itemArray));
		$this->assertFalse($this->db->getItemById($this->item->getId()));
	}

	public function tearDown() {
		$this->db->removeItem($this->item);
	}

}
?>