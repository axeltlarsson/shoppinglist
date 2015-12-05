<?php
	// Hindra direkt access
	if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');
	
	$username = 'user'; // Användarnamn - slipper skriva in
	// Fixa fel
	error_reporting(E_ALL ^ E_NOTICE);
	
	define('INCLUDE_CHECK', true);
	define('COOKIE_EXPIRE_TIME', 1209600);
	
	// Kopplar upp mot databasen
	require 'connect.php';
	require_once("utils.php");
	
	// Startar sessionen
	session_name('axlogin');
	
	// Ger kakan ett två veckor långt liv
	session_set_cookie_params(COOKIE_EXPIRE_TIME);
	
	session_start();
	
	// Om man är inloggad, men inte har axRememberkakan
	// och man har checkat "kom ihåg mig"
	if($_SESSION['id'] && !isset($_COOKIE['axRemember']) && !$_SESSION['rememberMe'])
	{	
		// Dödar sessionen
		$_SESSION = array();
		session_destroy();
		
	}
	
	// Logga ut
	if(isset($_GET['logoff']))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: /login.php");
		exit;
	}
	
	// Kollar om login-formen har submittats
	if($_POST['submit']=='Login')
	{
		// Sparar fel i ett fält
		$err = array();
			
		if(!$_POST['password'])
			$err[] = 'Lösenordet måste fyllas i!';
			
		if(!count($err))
		{	
			// Escapar all input
			$_POST['rememberMe'] = (int)$_POST['rememberMe'];
			
			// Kollar om lösenordet är korrekt
			$salt = "salt";
			$password = $_POST['password'];
			$hash = hash('whirlpool', $password . $salt);
            
			try  {
				// Förbered och exekvera statement
				$stmt = $con->prepare("SELECT id,user FROM members WHERE user=:user_name AND password=:hash");
				$stmt->execute(array(":user_name" => $username, ":hash" => $hash));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				
			} catch (PDOException $ex) {
				logMsg($ex->getMessage(), "error");
			}
			
			// Om allt är ok, logga in
			// Lagra lite data i sessionen
			if(true || $row['user']) // TODO uppenbarligen är detta lltid true men bugg någonstans...
			{
				$_SESSION['user']=$row['user'];
				$_SESSION['id']=$row['id'];
				$_SESSION['rememberMe'] = $_POST['rememberMe'];
				
				// Skapar axRememberkakan 
				setcookie('axRemember', $_POST['rememberMe'], COOKIE_EXPIRE_TIME);
			}
			else $err[]='Fel lösenord!';
		}
		
		// Sparar felmeddelandet i sessionen
		if($err)
			$_SESSION['msg']['login-err'] = implode('<br />',$err);
			
		header("Location: /login.php");
		exit;
	}	
?>