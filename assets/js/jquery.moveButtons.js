$(document).ready(function(){
	// Flytta "Logga ut"- och "Rensa"-knapparna till en ny div längs ner på sidan
	if (getActiveStyleSheet() !== "classic") {
		$(".logoff").appendTo("#tools");
		$("#resetButton").appendTo("#tools");
		$("#resetButton").html("Rensa listan");
		// Flytta även "Ändra/Shoppa"-knappen om vi är på mobilenhet
		if (isMobile()) {
			$('#editModeButton, #shoppingModeButton').appendTo("#tools");
		}
	}
});