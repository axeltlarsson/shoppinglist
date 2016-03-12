<?php
require_once("/usr/local/bin/web/testServer/command/addItemCommand.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class AddItemTest extends PHPUnit_Framework_TestCase {
	private $addItemCommand, $item, $db;
	
	public function setUp() {
		$data = array(
			"command" => "addItem",
			"data" => "margarin",
			"id"   => "item34",
			"marked" => false,
		);

		$this->item = new Item($data["data"], $data["id"], $data["marked"]);
		$this->db = new Database();

		$this->addItemCommand = new AddItemCommand($this->item, $this->db);
	}

	public function testAddItemCommand() {
		$this->addItemCommand->execute();
		// Item should now be in database
		$this->assertEquals($this->db->getItemById($this->item->getId()), $this->item);
	}

	public function tearDown() {
		$this->db->removeItem($this->item);
	}

}
?>