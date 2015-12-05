<?php

class Item {
	
	public $Data, $Id, $isMarked;

	public function __construct($data, $id, $marked = false) {
		$this->Data = $data;
		$this->Id = $id;
		$this->isMarked = $marked;
	}

	public function updateData($newData) {
		$this->Data = $newData;
	}
	
	public function toggleMarked() {
		$this->isMarked = !($this->isMarked);
	}

	public function getData() {
		return $this->Data;
	}

	public function getId() {
		return $this->Id;
	}

	public function isMarked() {
		return $this->isMarked;
	}

	public function __toString() {
		return "data: " . $this->Data . " id: " . $this->Id . " isMarked: " . $this->isMarked; 
	}
}

?>