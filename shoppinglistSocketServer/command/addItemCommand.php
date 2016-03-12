<?php
require_once("commandInterface.php");
require_once("utils.php");

class AddItemCommand implements Command {
	private $item;
	private $db;

	function __construct($item, $db) {
		$this->item = $item;
		$this->db = $db;
	}

	/**
	 *	Lägger till den nya posten i databasen
	 * 	@return $response En array som berättar om vad som skedde (eller inte skedde)
	 */
	public function execute() {
		// Försöka lägga till ny post
        if ($this->db->addItem($this->item)) { // success
          $response = array(
            "type" => "addedItem",
            "data" => $this->item->getData(),
            "id"   => $this->item->getId(),
          );
            logMsg("Added new item: " . $response["data"] . " (" . $response["id"] . ")", "addedItem");
        } else { // fail
          $response = array(
            "type" => "error",
            "data" => "Det gick inte att lägga till: " . $this->item->getData() . " med id: " . $this->item->getId(),    
          );
        }

        return $response;
	}
}

?>