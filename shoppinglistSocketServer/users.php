<?php

class WebSocketUser {

  public $socket;
  public $id;
  public $headers = array();
  public $handshake = false;

  public $handlingPartialPacket = false;
  public $partialBuffer = "";

  public $sendingContinuous = false;
  public $partialMessage = "";
  
  public $hasSentClose = false;

  // Axel-modifikationer:
  public $name;

  function __construct($id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }

  function setName($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }
}