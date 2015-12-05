<?php
	// Fixa fel
	error_reporting(E_ALL ^ E_NOTICE);
	// Inkludera login-hantering
	define('INCLUDE_CHECK', true);
	require 'assets/php/checklogin.php';
	
	if(!$_SESSION['id']):
	// Om man ej är inloggad
	header("Location: /login.php");
?>
<?php
	else:
	// Om man är inloggad
?>
<!DOCTYPE html>

<html>
	<head>
		<!-- Logo -->
		<LINK REL="SHORTCUT ICON" HREF="/assets/images/animated_favicon1.gif" />
		
		<!-- UTF8 -->
		<meta charset="UTF-8" />

		<title>Inköpslistan för mobiler</title>

		<!-- Anpassa efter mobiler -->
		<meta name=viewport content="width=device-width, initial-scale=0.55, user-scalable=no" />
		
		<!-- Importera Roboto -->
		<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:100">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/assets/css/themeMinimalismMobile.css" title="new" />
		<link rel="stylesheet" type="text/css" href="/assets/css/themeClassic.css" title="classic" />
        <link rel="stylesheet" type="text/css" href="/assets/css/themeSaBarcaDeFormenteraMobile.css" title="formentera" />
		<?php 
			// Detektera user agent
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			if (preg_match('/chrome/i',$user_agent)) {
				echo '<link rel="stylesheet" type="text/css" href="/assets/css/mobileChrome.css" title="new" />';
                echo '<link rel="stylesheet" type="text/css" href="/assets/css/mobileChrome.css" title="formentera" />';
			} else {
				echo '<link rel="stylesheet" type="text/css" href="/assets/css/mobileStockAndroid.css" title="new" />';
                echo '<link rel="stylesheet" type="text/css" href="/assets/css/mobileStockAndroid.css" title="formentera" />';
			}

		?>

		<!-- Javascript -->
        <script type="text/javascript" src="assets/js/shoppinglistSocket.js"></script>
        <script type="text/javascript" src="assets/js/utils.js"></script>
        <script type="text/javascript" src="/assets/js/shoppinglist.js"></script>
        <script type="text/javascript" src="/assets/js/legacyShoppinglist.js"></script>
		<script type="text/javascript" src="/assets/js/switchCss.js"></script>

        <!-- jQuery -->
		<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery.moveButtons.js" ></script>	
		
	</head>
	
<body onload="shoppinglist.importDatabase()">

<div id="note">
	<!-- "Logga ut"-knapp -->
		<a class="logoff" href="?logoff">Logga ut</a>
		<!-- Knappar för att gå till edit respektive shopping mode -->
		<button class="button" id="editModeButton" onclick="legacyShoppinglist.loadEditPage();focus();document.InputForm.AddItemBox.focus();legacyShoppinglist.importDatabase()">Ändra</button> 
		<button class="button" style="display:none" id="shoppingModeButton" onclick="window.location.reload()">Shoppa</button>
	<div id="content">
				
		<header>
			<h1>Inköpslistan</h1>
		</header>
		<div id="shoppinglistmobile" >
			<!-- här fyller javascript i -->
		</div>
		
		<form style="display:none" id="shoppinglist" onsubmit="ngt.php">
			<!-- här fyller javascript i -->
		</form>
		<form style="display:none" id="inputForm" name="InputForm" onsubmit="return shoppinglist.addItem({localOnly: true})">
			<input type="text" name="AddItemBox" maxlength="30" id="addItemBox"> 	
		</form>
				
		<div id="resultDiv"></div>
	</div>
	<div id="tools"></div>
	
</div>
<footer>
</footer>
</body>
</html>

<?php
	endif;
	// Stänger if-else-konstruktionen
?>