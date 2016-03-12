<?php
require_once("/usr/local/bin/web/testServer/command/addItemCommand.php");
require_once("/usr/local/bin/web/testServer/command/getAllItemsCommand.php");
require_once("/usr/local/bin/web/testServer/command/commandFactory.php");
require_once("/usr/local/bin/web/testServer/item.php");
require_once("/usr/local/bin/web/testServer/database.php");

class CommandFactoryTest extends PHPUnit_Framework_TestCase {
	private $commandFactory, $db, $msg, $item;

	public function setUp() {
		$this->db = new Database();
		$this->commandFactory = new CommandFactory($this->db);

		$this->item = new Item("1 kg mjöl", "item34", true);

		$this->msg = array(
			"data" => $this->item->getData(),
			"id"   => $this->item->getId(),
			"marked" => $this->item->isMarked(),
		);
	}

	public function testMakeAddItemCommand() {
		$expectedCommand = new AddItemCommand($this->item, $this->db);

		// Definiera vad för typ av Command vi vill skapa
		$this->msg["command"] = "addItem";

		// Since CommandFactory expects json_decode json messages we must do a little trick
		$this->msg = json_decode(json_encode($this->msg));

		$actualCommand = $this->commandFactory->makeCommand($this->msg);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		// Make sure that we cannot execute the commands after we're done
		unset($expectedCommand);
		unset($actualCommand);
	}

	public function testMakeRemoveItemCommand() {
		$expectedCommand = new RemoveItemCommand($this->item, $this->db);

		// Definiera vad för typ av Command vi vill skapa
		$this->msg["command"] = "removeItem";

		// Since CommandFactory expects json_decode json messages we must do a little trick
		$this->msg = json_decode(json_encode($this->msg));

		$actualCommand = $this->commandFactory->makeCommand($this->msg);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		// Make sure that we cannot execute the commands after we're done
		unset($expectedCommand);
		unset($actualCommand);
	}

	public function testMakeUpdateItemCommand() {
		$expectedCommand = new UpdateItemCommand($this->item, $this->db);

		// Definiera vad för typ av Command vi vill skapa
		$this->msg["command"] = "updateItem";

		// Since CommandFactory expects json_decode json messages we must do a little trick
		$this->msg = json_decode(json_encode($this->msg));

		$actualCommand = $this->commandFactory->makeCommand($this->msg);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		// Make sure that we cannot execute the commands after we're done
		unset($expectedCommand);
		unset($actualCommand);
	}

	public function testMakeClearListCommand() {
		$expectedCommand = new ClearListCommand($this->db);

		// Definiera vad för typ av Command vi vill skapa
		$this->msg["command"] = "clearList";

		// Since CommandFactory expects json_decode json messages we must do a little trick
		$this->msg = json_decode(json_encode($this->msg));

		$actualCommand = $this->commandFactory->makeCommand($this->msg);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		// Make sure that we cannot execute the commands after we're done
		unset($expectedCommand);
		unset($actualCommand);
	}

	public function testMakeGetAllItemsCommand() {
		$expectedCommand = new GetAllItemsCommand($this->db);

		// Definiera vad för typ av Command vi vill skapa
		$this->msg["command"] = "getAllItems";

		// Since CommandFactory expects json_decode json messages we must do a little trick
		$this->msg = json_decode(json_encode($this->msg));

		$actualCommand = $this->commandFactory->makeCommand($this->msg);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		$this->assertEquals($expectedCommand, $actualCommand);
		
		// Make sure that we cannot execute the commands after we're done
		unset($expectedCommand);
		unset($actualCommand);
	}

	/**
	 *	@expectedException        InvalidArgumentException
	 */
	public function testInvalidRemoveItemCommand() {
		// Skicka in ett icke giltig msg
		$invalidMsg = array(
            "command" => "removeItem",
            "data" => "",
            "id" => new Item("hej", "hej", true),
            "marked" => ""
        );
        
		$invalidMsg = json_decode(json_encode($invalidMsg));
		$this->commandFactory->makeCommand($invalidMsg);
	}

}

?>

