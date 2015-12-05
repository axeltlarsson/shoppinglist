<?php
require_once("/usr/local/bin/web/testServer/command/getAllItemsCommand.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class GetAllItemsTest extends PHPUnit_Framework_TestCase {
	private $addItemCommand, $db;
	
	public function setUp() {
		$this->db = new Database();
		$this->getAllItemsCommand = new GetAllItemsCommand($this->db);
	}

	public function testGetAllItemsCommand() {
		$expected = array(
            "type" => "sentAllItems",
            "data" => $this->db->getAllItems(),   
        );
		$actual = $this->getAllItemsCommand->execute();

		$this->assertEquals($expected, $actual, "GetAllItemsCommand did not return correct array.");
	}

	public function testGetAllItemsCommandChangedInbetween() {
		$expected = $this->db->getAllItems();
		$this->db->addItem(new Item("4 kg mjöl", "item45"));
		$actual = $this->getAllItemsCommand->execute();

		$this->assertNotEquals($expected, $actual, "GetAllItemsCommand did not return correct array.");
	}

	public function tearDown() {
		$this->db->removeItem(new Item("4 kg mjöl", "item45"));
	}



}
?>