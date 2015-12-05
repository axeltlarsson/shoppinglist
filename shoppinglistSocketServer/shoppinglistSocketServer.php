#! /usr/bin/php
<?php

require_once('./websockets.php');
require_once("database.php");
require_once("command/commandFactory.php");
require_once("item.php");
require_once("utils.php");

class ShoppingListSocketServer extends WebSocketServer {
  protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

  protected function process($user, $message) {
  	$msg = json_decode($message);
    try {
      $command = $this->commandFactory->makeCommand($msg);  
    } catch (InvalidArgumentException $e) {
      logMsg($user->{"id"} . " sent an invalid message." . $message . $e, "error");
      $this->send($user,json_encode(array(
            "type" => "error",
            "data" => "Invalid message.")));
      return;
    }
    
    if (isset($this->fictionalDelay)) {
        sleep($this->fictionalDelay);
    }
        

    // Exekvera Command och returnera resultat
    $result = json_encode($command->execute());
      
      if (json_decode($result)->{"type"} == "error") {
          logMsg(json_decode($result)->{"data"}, "error");
      }
    foreach ($this->users as $u) {
        $message = htmlspecialchars($message);
        $this->send($u,$result);
    }

  }
    
  protected function connected($user) {
  /* Prototyp-kod: */
    logMsg("user with id: " . $user->{"id"}. " connected to the server.", "userConnected");
    
  
    /*-----------------------------------------------
      Ta reda på eventuell cookie-lagrad user-data
    -------------------------------------------------*/
  /*
    // Ta fram cookie-strängen från headers
    $cookie_string =  $user->{"headers"}["cookie"];

    
    // Ta fram "shoppinglistUser"-cookien från cookie-strängen 
    $cookieStartPos = strpos($cookie_string, "shoppinglistUser");
    $cookieEndPos = strpos($cookie_string, ";", $cookieStartPos);

    if ($cookieEndPos < $cookieStartPos) { //  Sista cookien i strängen
      $cookie = substr($cookie_string, $cookieStartPos);  
    } else { // ej den sista cookien i strängen
      $cookie = substr($cookie_string, $cookieStartPos, $cookieEndPos - $cookieStartPos);
    }

    $cookieValueStartPos = strpos($cookie, "=") + 1;
    $cookieUser = substr($cookie, $cookieValueStartPos);
    $user->setName($cookieUser);
  */
  }
  
  protected function closed($user) {
      logMsg("user with id: " . $user->{"id"}. " disconnected from the server.", "userDisconnected");
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
  }
}

$db = new Database();
$shoppingListSocketServer = new ShoppingListSocketServer("0.0.0.0","9001", $db, new CommandFactory($db), $argv[1]);
try {
    $shoppingListSocketServer->run();
}
catch (Exception $e) {
    $shoppingListSocketServer->stdout($e->getMessage());
}
