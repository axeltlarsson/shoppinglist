<?php
	// Fixa fel
	error_reporting(E_ALL ^ E_NOTICE);
	// Inkludera login-hantering
	define('INCLUDE_CHECK', true);
	require 'assets/php/checklogin.php';
	
	if(!$_SESSION['id']):
	// Om man ej är inloggad
	header("Location: /login");

?>
<?php
	else:
	// Om man är inloggad
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Logo -->
		<LINK REL="SHORTCUT ICON" HREF="/assets/images/icons/web_hi_res_512.png" />
		
		<meta charset="UTF-8" />
	
		<!-- Kolla user agent -->
		<?php
			require_once('assets/php/mobile_device_detect.php');
			mobile_device_detect(true,true,true,true,true,true,true,'/mobile_index',false);
		?>
		<!-- CSS för Desktop -->
		<link rel="stylesheet" type="text/css" href="/assets/css/themeMinimalism.css" title="new" />
		<link rel="stylesheet" type="text/css" href="/assets/css/themeClassic.css" title="classic" />
        <link rel="stylesheet" type="text/css" href="/assets/css/themeSaBarcaDeFormentera.css" title="formentera" />
		<!-- Importera Roboto -->
		<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100">
		
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery.moveButtons.js" ></script>		
		
		<!-- Javascript -->
		<script type="text/javascript" src="assets/js/shoppinglistSocket.js"></script>
        <script type="text/javascript" src="assets/js/utils.js"></script>
        <script type="text/javascript" src="/assets/js/shoppinglist.js"></script>
		<script type="text/javascript" src="/assets/js/legacyShoppinglist.js"></script>
		<script type="text/javascript" src="/assets/js/switchCss.js"></script>
        
		<!-- CSS för Mobile -->
		<link rel="stylesheet" media="only screen and (max-width: 800px)" type="text/css" href="/assets/css/mobileChrome.css" />
        
		<title>Inköpslistan</title>
	</head>
<body onload="focus();document.InputForm.AddItemBox.focus();shoppinglist.importDatabase()">
<div id="note">
	<a class="logoff" href="?logoff">Logga ut</a>
<div id="content">
	
	<div id="reset">
		<button title="Rensa listan" class="button" id="resetButton" onclick="shoppinglist.clearList(true);focus();InputForm.AddItemBox.focus()">X</button> 
	</div>
	<header>
		<h1>Inköpslistan</h1>
	</header>
	<form id="shoppinglist" onsubmit="ngt.php">
		<!-- här fyller javascript i -->
	</form>
	
	<form id="inputForm" name="InputForm" onsubmit="return shoppinglist.addItem({localOnly: true})">
		<input type="text" name="AddItemBox" maxlength="40" id="addItemBox">
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