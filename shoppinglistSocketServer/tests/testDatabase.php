<?php
require_once("/usr/local/bin/web/testServer/database.php");

class DatabaseTest extends PHPUnit_Framework_TestCase {
	protected $db, $item;

	public function setUp() {
		$this->db = new Database();
		$this->item = new Item("test item f345rån d345atabase", "item69", false);
	}

	public function testAddAndGetItem() {
		$this->db->addItem($this->item);
		$itemFromDb =  $this->db->getItemById($this->item->getId());
		$this->assertEquals($this->item, $itemFromDb);
	}

	public function testGetAllItems() {
		// Add 30 items to the database
		for ($i=70; $i <= 99; $i++) { 
			$itemId = "item" . $i;
			$itemData = "itemdata" . $i;
			$this->db->addItem(new Item($itemData, $itemId, true));
		}

		// Get an array containing all items
		$itemArray = $this->db->getAllItems();

		// Check that the 30 items we added earlier are in the array
		for ($j=70; $j <= 99; $j++) { 
			$itemId = "item" . $j;
			$itemData = "itemdata" . $j;
			$this->assertTrue(in_array(new Item($itemData, $itemId, true), $itemArray), "$itemData $itemId fanns inte i vektorn.");
		}

		// Clean up
		for ($k=70; $k <= 99; $k++) { 
			$itemId = "item" . $k;
			$itemData = "itemdata" . $k;
			$this->db->removeItem(new Item($itemData, $itemId));
		}

		// Check that the items no longer exist in the database
		$itemArray = $this->db->getAllItems();
		for ($l=70; $l <= 99; $l++) { 
			$itemId = "item" . $l;
			$itemData = "itemdata" . $l;
			$this->assertFalse(in_array(new Item($itemData, $itemId), $itemArray), "$itemData $itemId fanns fortfarande kvar i vektorn.");
		}
	}
	
	public function tearDown() {
		$this->db->removeItem($this->item);
	} 
}
?>