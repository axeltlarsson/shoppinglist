<?php
require_once("commandInterface.php");
require_once("utils.php");

class ClearListCommand implements Command {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}
	
	public function execute() {
		// Försök rensa databasen
        if ($this->db->clear()) { // success
        	$response = array(
	          "type" => "clearedList",    
	        );
            logMsg("Cleared the list.", "clearedList");
        } else { // fail
        	$resonse = array(
        		"type" => "error",
        		"data" => "Det gick inte att rensa listan.",
        	);
        }

        return $response;
	}
}

?>