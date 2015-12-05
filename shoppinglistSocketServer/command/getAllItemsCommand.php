<?php
require_once("commandInterface.php");
require_once("utils.php");

class GetAllItemsCommand implements Command {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function execute() {
		$response = array(
            "type" => "sentAllItems",
            "data" => $this->db->getAllItems(),    
        );
        logMsg("Syncing database to clients...", "sync");
    	return $response;
	}
}

?>