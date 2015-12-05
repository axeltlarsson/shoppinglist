<?php
require_once("/usr/local/bin/web/testServer/item.php");
class ItemTest extends PHPUnit_Framework_TestCase {

	protected $item, $data, $id, $item2;

	protected function setUp() {
		$this->data = "en post i inköpslistan";
		$this->id = "item23";
		$this->item = new Item($this->data, $this->id);
		$this->item2 = new Item($this->data, "item24", true);
	}

	public function testMarked() {
		$this->assertFalse($this->item->isMarked());
		$this->assertTrue($this->item2->isMarked());
	}

	public function testUpdateData() {
		$this->assertEquals($this->data, $this->item->getData());
		$this->item->updateData("nytt värde på här");
		$this->assertEquals("nytt värde på här", $this->item->getData());
	}

	public function testToggleMarked() {
		$this->assertFalse($this->item->isMarked());
		$this->item->toggleMarked();
		$this->assertTrue($this->item->isMarked());

	}

	public function testGetId() {
		$this->assertEquals($this->id, $this->item->getId(), "Wrong Id");
	}
}
?>
