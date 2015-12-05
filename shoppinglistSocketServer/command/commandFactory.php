<?php
require_once("addItemCommand.php");
require_once("removeItemCommand.php");
require_once("updateItemCommand.php");
require_once("clearListCommand.php");
require_once("getAllItemsCommand.php");

class CommandFactory {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	/**
	 *	Returns a command made from the $data array
	 *	@param $data An json_decoded json-array that contains the keys "command", "data", "id"
	 *			and "marked"
	 * 	@throws InvalidArgumentException If no valid "command"-key is found in the $data array
	 * 	@return A Command based on the $data array
	 */
	public function makeCommand($data) {

        if (!array_key_exists("command", $data) || !is_string($data->{"command"}) || !array_key_exists("data", $data) || !is_string($data->{"data"})|| !array_key_exists("id", $data) || !is_string($data->{"id"}) || !array_key_exists("marked", $data)) {
            var_dump($data);
            throw new InvalidArgumentException("Kunde inte skapa Command från ^");
        }

		$command = $data->{"command"};
		$item = new Item($data->{"data"}, $data->{"id"}, $data->{"marked"});
        
		switch ($command) {
			case 'addItem' :
                if(!(strlen(trim($data->{"data"})) > 0) || !(strlen(trim($data->{"id"})) > 0)) {
                    throw new InvalidArgumentException('Det saknas värden för nycklarna "data", "id" eller "marked".');
                }
				return new AddItemCommand($item, $this->db);
				break;
			case 'removeItem' :
                if(!(strlen(trim($data->{"id"})) > 0)) {
                    throw new InvalidArgumentException('Det saknas värden för nycklen "id".');
                }
				return new RemoveItemCommand($item, $this->db);
				break;
			case 'updateItem' :
                if(!(strlen(trim($data->{"data"})) > 0) || !(strlen(trim($data->{"id"})) > 0)) {
                    throw new InvalidArgumentException('Det saknas värden för nycklarna "data", "id" eller "marked".');
                }
				return new UpdateItemCommand($item, $this->db);
				break;
			case 'clearList' :
				return new ClearListCommand($this->db);
				break;
			case 'getAllItems' :
				return new GetAllItemsCommand($this->db);
				break;
			default:
				throw new InvalidArgumentException("Kunde inte skapa Command från: " . $command);
				break;
		}
	}
}
?>