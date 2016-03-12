<?php
require_once("commandInterface.php");
require_once("utils.php");

class RemoveItemCommand implements Command {
	private $item;
	private $db;

	function __construct($item, $db) {
		$this->item = $item;
		$this->db = $db;
	}

	public function execute() {
		// Försök ta bort posten
        if ($this->db->removeItem($this->item)) { // success
          $response = array(
            "type" => "removedItem",
            "id" => $this->item->getId(),    
          );
            logMsg("Removed item: " . "(" . $response["id"] . ")", "removedItem");
        } else { // fail
          $response = array(
            "type" => "error",
            "data" => "Det gick inte att ta bort post: " . $this->item->getId(),    
          );
        }

        return $response;
	}
}

?>