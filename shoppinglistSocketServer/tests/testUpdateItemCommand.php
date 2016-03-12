<?php
require_once("/usr/local/bin/web/testServer/command/addItemCommand.php");
require_once("/usr/local/bin/web/testServer/command/updateItemCommand.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class UpdateItemCommandTest extends PHPUnit_Framework_TestCase {
	private $updateItemCommand, $item, $db;
	
	public function setUp() {
		$this->db = new Database();

		$data = array(
			"command" => "addItem",
			"data" => "margarin",
			"id"   => "item34",
			"marked" => true,
		);

		$this->item = new Item($data["data"], $data["id"], $data["marked"]);
		
		// Add item to database
		$this->db->addItem($this->item);	
	}

	public function testUpdateItemCommand() {
		// Test that item is in database
		$this->assertEquals($this->item, $this->db->getItemById($this->item->getId()), "Item not properly stored in database");
		// Change item
		$this->item->updateData("smör (uppdaterat)");
		// Make an updateItemCommand
		$this->updateItemCommand = new UpdateItemCommand($this->item, $this->db);
		// Execute updateItemCommand
		$this->updateItemCommand->execute();
		// Item should now be changed in database
		$this->assertEquals($this->db->getItemById($this->item->getId()), $this->item);
	}

	public function testUpdateItemCommandNotChanged() {
		// Make an updateItemCommand
		$this->updateItemCommand = new UpdateItemCommand($this->item, $this->db);
		// Execute updateItemCommand
		$this->updateItemCommand->execute();
		// Item should not be changed in database
		$this->assertEquals($this->db->getItemById($this->item->getId()), $this->item);	
	}

	public function tearDown() {
		$this->db->removeItem($this->item);
	}

}
?>