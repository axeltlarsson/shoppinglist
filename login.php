<?php
	// Fixa fel
	error_reporting(E_ALL ^ E_NOTICE);
	// Inkludera login-hantering
	define('INCLUDE_CHECK', true);
	require 'assets/php/checklogin.php';

?>

<!DOCTYPE html>

<head>
		<!-- Logo -->
		<link rel="SHORTCUT ICON" href="/assets/images/animated_favicon1.gif" />

		<!-- UTF8 -->
		<meta charset="UTF-8" />
		
		<title>Inköpslistan</title>
		
		<!-- CSS -->
		<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:100">
		<link id="desktopCSSNew" rel="stylesheet" type="text/css" href="/assets/css/themeMinimalismLogin.css" title="new" />
        <link id="formentera" rel="stylesheet" type="text/css" href="/assets/css/themeSaBarcaDeFormenteraLogin.css" title="formentera" />
		<link rel="stylesheet" type="text/css" href="/assets/css/themeClassicLogin.css" title="classic" />

		<!-- Anpassa efter mobiler -->
        <meta name=viewport content="width=device-width, initial-scale=0.55, user-scalable=no" />
	 	<?php 
			// Detektera user agent
			require_once('assets/php/mobile_device_detect.php');

			if (mobile_device_detect(true,true,true,true,true,true,true,false,false)) { // mobil detekterad
				// mobile
				echo '<link id="mobileCSSNew" rel="stylesheet" type="text/css" href="/assets/css/themeMinimalismMobileLogin.css" title="new" />';
			}
		?>
		
		<!-- Javascript -->
		<script type="text/javascript" src="/assets/js/switchCss.js"></script>
		<script type="text/javascript">
			/**
			 *	Sätter css till det som står i index
			 * 	pre: någon css är satt
			 * 	post: alla css-filer utom de som matchar title-attribut indikerat av index, är disablade
			 */
			function setCss(index){
				if (index == 1) {
					setActiveStyleSheet("classic");
				} else if (index == 2) {
					setActiveStyleSheet("new");
				} else if (index == 3) {
                    setActiveStyleSheet("formentera");
                }
			}

		</script>
</head>		
<?php
	if(!$_SESSION['id']):
	// Om man ej är inloggad:
?>

<html>
<body onload="focus();document.inloggningsform.password.focus()">

<!-- Inloggningsform -->
<div id="inloggningsform">
<form name="inloggningsform" action="" method="post">
	<h1>Logga in</h1>

	
	<label class="labels" for="password">Lösenord:</label>
	<input class="field" type="password" name="password" id="password" size="23" />
	<label class="labels" for="rememberMe"><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" />&nbsp;Håll mig inloggad</label>
	<br />
	<div class="err">
		<?php // Visar fel, om det finns några
        if($_SESSION['msg']['login-err']) {	 
				echo $_SESSION['msg']['login-err'];
				unset($_SESSION['msg']['login-err']);		
			} 
		?>
	</div>
	<input type="submit" name="submit" value="Login" class="loginButton" />
	
	<div id="themeChooser">
		<select name="cssChoosing" onchange="if (this.selectedIndex) setCss(this.selectedIndex);">
		    <option value="-">Välj tema:</option>
		    <option value="classic">Klassiskt tema</option>
		    <option value="new">Minimalism</option> 
            <option value="formentera">Sa Barca de Formentera</option>
		</select>
	</div>


</form>
</div>
<?php
	else:
	// Om man är inloggad
	header("Location: /");
	endif;
	// Stänger if-else-konstruktionen
?>
</body>
</html>