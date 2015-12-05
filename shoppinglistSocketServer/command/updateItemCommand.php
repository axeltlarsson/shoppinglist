<?php
require_once("commandInterface.php");
require_once("utils.php");

class UpdateItemCommand implements Command {
	private $item;
	private $db;

	function __construct($item, $db) {
		$this->item = $item;
		$this->db = $db;
	}
	
	public function execute() {
		// Försöka uppdatera post
        if ($this->db->updateItem($this->item)) { // success
          $response = array(
            "type" => "updatedItem",
            "data" => $this->item->getData(),
            "id" => $this->item->getId(),
			"isMarked" => $this->item->isMarked()
          );
            logMsg("Updated item: (" . $response["id"] . ")" . " --> " . $response["data"], "updatedItem");
        } else { // fail
          $response = array(
            "type" => "warn",
            "data" => "Det gick inte att uppdatera post: " . $this->item->getId(),
          );
        }

        return $response;
	}
}

?>