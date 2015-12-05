/*jslint browser: true, plusplus: true, vars: true */
/*global $, alert, console, updateDatabase, utils */
// Tar bort element
var legacyShoppinglist = (function () {

    "use strict";

    // Ta bort "items"
    function removeItemFromDatabase(itemId) {
        if (itemId === "") {
            alert("tomt id-fält " + itemId);
            return;
        }
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById("resultDiv").innerHTML = xmlhttp.responseText;
            }
        };
        var queryString = "?itemId=" + itemId;
        xmlhttp.open("GET", "/assets/php/removeItemFromDatabase.php" + queryString, true);
        xmlhttp.send();
    }

    // Sparar "items" från "shoppinglist" till MySql-databasen
    function insertIntoDatabase(item, itemId) {
        if (item === "") {
            alert("tomt item-fält");
            return;
        }
        if (itemId === "") {
            alert("tomt id-fält " + itemId);
            return;
        }
        var xmlhttp, queryString = "?item=" + item;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.getElementById("resultDiv").innerHTML = xmlhttp.responseText;
            }
        };

        queryString += "&itemId=" + itemId;
        xmlhttp.open("GET", "/assets/php/insertItemIntoDatabase.php" + queryString, true);
        xmlhttp.send();
    }



    return {
        /**
         * Lägger till "items" i "shoppinglist" från "inputForm"
         *  @param divName - HTML-elementet till vilket vi lägger till den nya posten
         *  @param itemId - valfri
         *
         */
        addItem: function (divName, itemId) {
            itemId = itemId || utils.getNextItemId(); // Avänd antingen argumentet eller ta reda på itemId via utils.getNextItemId()

            // Ta reda på den nya postens innehåll
            var item = $("#addItemBox").val();
            if (!item) { // tomt nytt innehåll
                return false; // gör inget
            }

            // Spara i MySql-databasen
            insertIntoDatabase(item, itemId);

            // Appenda en ny div med id=itemId till divName (=shoppinglist förmodligen)
            $("#" + divName).append('<div id=' + itemId + '></div>');

            // Appenda en ny input box till ovan skapade div med lite snygg animation
            $("#" + itemId)
                .append('<input class="itemBox" type="text" onblur="legacyShoppinglist.updateDatabase(\'' + itemId + '\')" name="myItems[]"><button class="itemRemoveButton" tabIndex="-1" type="button"  onClick="legacyShoppinglist.removeElement(\'shoppinglist\', \'' + itemId + '\');">x</button>')
                .hide()
                .slideDown("fast")
                .css("opacity", 0.0)
                .fadeTo("fast", 1.0);

            // Sätt värdet på ovan skapade inputbox till item
            $("#" + itemId + " .itemBox").val(item);
            // Rensa #addItemBox
            $("#addItemBox").val("");

            // Scrolla till botten av sidan
            $("html, body").animate({
                scrollTop: $(document).height()
            }, "slow");

            return false; // för att förhindra page refresh
        },

        // Laddar in från databasen
        importDatabase: function () {

            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById("shoppinglist").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", "/assets/php/importDatabase.php", true);
            xmlhttp.send();
        },

        // Laddar in från databasen till mobila sidan
        importMobileSite: function () {

            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById("shoppinglistmobile").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", "/assets/php/importDatabaseToMobile.php", true);
            xmlhttp.send();
        },

        // Rensa databasen
        clearDatabase: function () {
            // Ta bort från sidan med snygga animationer
            var delay = 0;
            $("#shoppinglist div").each(function () {

                var item = $(this);

                setTimeout(function () {
                    item.fadeTo("slow", 0.0).slideUp("slow", function () {
                        this.remove();
                    });

                }, delay);
                delay += 100;
            });

            // Ta bort från databasen
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById("resultDiv").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", "/assets/php/clearDatabase.php", true);
            xmlhttp.send();

            // Ta bort resultatmeddelande efter en viss fördröjning
            $("#resultDiv").delay(3000).fadeTo("slow", 0.0, function () {
                $(this).empty();
            });
        },

        // Togglar bought på mobilsidan
        toggleBought: function (itemId) {
            if (itemId === "") {
                alert("tomt id-fält " + itemId);
                return;
            }

            if (document.getElementById(itemId).className.match(/(?:^|\s)marked(?!\S)/)) { // denna post är markerad
                // ta bort markerings-klassen
                document.getElementById(itemId).className = document.getElementById(itemId).className.replace(/(?:^|\s)marked(?!\S)/g, '');

            } else if (!document.getElementById(itemId).className.match(/(?:^|\s)marked(?!\S)/)) { // denna post är ej markerad
                // lägg till markerings-klassen
                document.getElementById(itemId).className += " marked";
            }

            // Upddatera databasen
            this.updateDatabase(itemId);

        },

        // Laddar in "vanliga" edit mode
        loadEditPage: function () {
            // Ta bort det som genererats av "importMobileSite()"
            document.getElementById("shoppinglistmobile").innerHTML = "";
            // Visa inputFrom, ressetButton, shoppinglist
            document.getElementById("inputForm").style.display = "block";
            //document.getElementById("resetButton").style.display = "block";
            document.getElementById("shoppinglist").style.display = "block";
            // Fixa css:n så att den överensstämmer
            document.getElementById("content").style.padding = "56px 0px 47px";
            // Visa shopping-mode-knappen
            document.getElementById("shoppingModeButton").style.display = "block";
            // Dölj ändra-knappen
            document.getElementById("editModeButton").style.display = "none";
        },

        // Rensa editPage
        clearEditPage: function () {
            // Dölj inputForm, resetButton, shoppinglist
            document.getElementById("inputForm").style.display = "none";
            document.getElementById("resetButton").style.display = "none";
            document.getElementById("shoppinglist").style.display = "none";
            // Dölj shopping-mode-knappen
            document.getElementById("shoppingModeButton").style.display = "none";
            // Visa ändra-knappen
            document.getElementById("editModeButton").style.display = "block";
        },

        removeElement: function (parentDiv, childDiv) {
            $("#" + childDiv).fadeTo("fast", 0.0).slideUp("fast", function () {
                this.remove();
            });

            /*
        if (childDiv === parentDiv) {
            alert("The parent div cannot be removed.");
        }
        else if (document.getElementById(childDiv)) {
            var child = document.getElementById(childDiv);
            var parent = document.getElementById(parentDiv);
            parent.removeChild(child);
        }
        else {
            alert(childDiv + " has already been removed or does not exist.");
            return false;
        }
    */

            // Ta bort från databasen
            removeItemFromDatabase(childDiv);
        },

        // Uppdatera databasen med "items"
        updateDatabase: function (itemId) {
            if (itemId === "") {
                alert("tomt id-fält " + itemId);
                return;
            }

            var item = document.getElementById(itemId).childNodes[0].innerHTML, // detta gäller för desktop
                isMarked = 0,
                xmlhttp = new XMLHttpRequest();
            if (item === "" || item === "undefined" || item === null) {
                item = document.getElementById(itemId).childNodes[0].value; // detta för mobile
            }

            if (document.getElementById(itemId).className.match(/(?:^|\s)marked(?!\S)/)) {
                isMarked = 1;
            }

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById("resultDiv").innerHTML = xmlhttp.responseText;
                }
            };

            var queryString = "?item=" + item;
            queryString += "&itemId=" + itemId;
            queryString += "&isMarked=" + isMarked;
            xmlhttp.open("GET", "/assets/php/updateDatabase.php" + queryString, true);
            xmlhttp.send();
        }

    };
}());