/*jslint vars: true */
/*jslint browser: true*/
/*global $, alert, WebSocket, console, legacyShoppinglist, shoppinglistSocket, utils, document */
var shoppinglist = (function () {
    'use strict';

    function webSocketSupport() {
        if (typeof WebSocket !== 'undefined') {
            return true;
        } else {
            return false;
        }
    }

    return {

        /**
         *  Adds an item locally on the HTMl page.
         *  @param item - the item object to add
         *  @param item.localOnly - if the adding of this new item has not (yet) been confirmed
         *      with the server, the item is added with the CSS class "localOnly"
         */
        addItem: function (item) {
            var id = item.id || utils.getNextItemId(),
                data = item.data || $("#addItemBox").val(),
                isMarked = item.isMarked || false,
                localOnly = item.localOnly || false;

            if (!webSocketSupport() || !shoppinglistSocket.isConnected()) {
                if (utils.isMobile()) {
                    return legacyShoppinglist.addItem("shoppinglistMobile", id);
                } else {
                    return legacyShoppinglist.addItem("shoppinglist", id);
                }
            }

            if (localOnly && !$("#" + item.id).length) { // not yet confirmed server side
                shoppinglistSocket.addItem(data, id);
                if (utils.isMobile() && !$("#inputForm").is(":visible")) {
                    $("#shoppinglistmobile").append('<div title="This item is not confirmed server side yet." id="' + id + '"></div');
                    $("#" + id).append('<p onclick="shoppinglist.toggleBought(\'' + id + '\')" class="item localOnly">' + data + '</p>');

                } else {

                    $("#shoppinglist").append('<div title="This item is not confirmed server side yet." id="' + id + '"></div>');
                    $("#" + id).append('<input class="itemBox localOnly" type="text" onblur="shoppinglist.updateItem({localOnly: true, id: \'' + id + '\'});" name="myItems[]" value="' + data + '"><button class="itemRemoveButton" tabindex="-1" type="button" onclick="shoppinglist.removeItem({localOnly: true, id: \'' + id + '\'});">x</button>').hide()
                        .slideDown("fast");
                }
            } else { // assume adding this item is confirmed server side
                if (utils.isMobile()) {
                    if ($("#" + id).length) { // item already exists locally

                        // Set server side confirmed
                        $("#" + id).children().removeClass("localOnly");
                        $("#" + id).attr("title", "");


                    } else if (!$("#inputForm").is(":visible")) { // somebody else added this item
                        $("#shoppinglistmobile").append('<div id="' + id + '"></div');
                        $("#" + id).append('<p onclick="shoppinglist.toggleBought(\'' + id + '\')" class="item">' + data + '</p>');
                        $("#" + id).children().removeClass("localOnly");
                        $("#" + id).attr("title", "");
                    }

                } else {
                    if ($("#" + id).length && data === $("#" + id).children().val()) { // item already exists locally

                        // Set server side confirmed
                        $("#" + id).children().removeClass("localOnly");
                        $("#" + id).attr("title", "");
                    } else { // somebody else added this item
                        $("#shoppinglist").append('<div id="' + id + '"></div>');
                        $("#" + id).append('<input class="itemBox" type="text" onblur="shoppinglist.updateItem({localOnly: true, id: \'' + id + '\'});" name="myItems[]" value="' + data + '"><button class="itemRemoveButton" tabindex="-1" type="button" onclick="shoppinglist.removeItem({localOnly: true, id: \'' + id + '\'});">x</button>').hide()
                            .slideDown("fast");
                    }
                }
                if (isMarked === "1") {
                    $("#" + id).addClass("marked");
                }
            }
            $("#addItemBox").val("");
            return false;
        },

        /**
         *  Removes an item locally from the HTML page.
         *  @param id - the id of the item to remove
         */
        removeItem: function (item) {
            var id = item.id || false,
                localOnly = item.localOnly || false;

            if (!webSocketSupport() || !shoppinglistSocket.isConnected()) {
                alert("Not connected to the WebSocket!");
                return legacyShoppinglist.removeElement('shoppinglist', id);
            }

            if (localOnly) {
                shoppinglistSocket.removeItem(id);
            }
        
            $("#" + id).fadeTo("fast", 0.0).slideUp("fast", function () {
                this.remove();
                var itemsLeft = document.getElementsByClassName('itemBox').length;
                if (itemsLeft === 0) {
                    console.debug("Last item removed, clearing list");
                    shoppinglist.clearList(localOnly);
                }
            });
        },

        /**
         *  Updates an item locally on the HTML page.
         *  @param item.data - the new data for the item, if false - the function finds the data from the local HTML instead
         *  @param item.id - the id of the item to update
         */
        updateItem: function (item) {

            var id = item.id,
                data = item.data || $("#" + id).children().val(),
                isMarked = $("#" + id).hasClass("marked"),
                localOnly = item.localOnly || false;
            
            if (!localOnly) {
                isMarked = item.isMarked || false;
            }
            
            if (!webSocketSupport() || !shoppinglistSocket.isConnected()) {
                alert("Not connected to the WebSocket!");
                return legacyShoppinglist.updateDatabase(id);
            }

            if (localOnly) {
                shoppinglistSocket.updateItem({
                    "data": data,
                    "id": id,
                    "isMarked": isMarked
                });
            } else {
                if (utils.isMobile()) {
                    $("#" + id).children(".item").text(data);
                } else {
                    $("#" + id).children().val(data);
                }
            }
            if (isMarked) {
                $("#" + id).addClass("marked");
            } else {
                $("#" + id).removeClass("marked");
            }

        },

        toggleBought: function (id) {
            var item = {
                "id": id,
                "localOnly": true,
                "data": $("#" + id).children().text()
            };
            if (document.getElementById(id).className.match(/(?:^|\s)marked(?!\S)/)) { // denna post är markerad
                // ta bort markerings-klassen
                document.getElementById(id).className = document.getElementById(id).className.replace(/(?:^|\s)marked(?!\S)/g, '');

            } else if (!document.getElementById(id).className.match(/(?:^|\s)marked(?!\S)/)) { // denna post är ej markerad
                // lägg till markerings-klassen
                document.getElementById(id).className += " marked";
                item.isMarked = true;
            }

            // Upddatera databasen
            var data = $("#" + id).children().text();
            this.updateItem(item);
        },

        /**
         *  Clears the list locally and invokes the shoppinglistSocket.clear() function if localOnly is true.
         *  @param localOnly - pass in true here if called upon by local JS code.
         */
        clearList: function (localOnly) {
            console.debug("Clearing list.");
            if (!webSocketSupport() || !shoppinglistSocket.isConnected()) {
                alert("Not connected to the WebSocket!");
                return legacyShoppinglist.clearDatabase();
            }

            if (localOnly) {
                shoppinglistSocket.clear();
            }

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
        },

        /**
         *  Overwrites the local list with the server side list
         *  @param shoppinglist - an array with all items from the server
         *  pre: the local HTML contains either an empty or non-empty list with items
         *  post: the local HTML contains a list with the latest items from the server (and any previously unsynced items locally)
         */
        syncToDatabase: function (shoppingList) {
            console.info("syncToDatabase()");

            // Add all server side items locally
            shoppingList.forEach(function (entry) {
                var itemData = entry.Data,
                    itemId = entry.Id,
                    itemIsMarked = entry.isMarked;
                shoppinglist.addItem({
                    localOnly: false,
                    isMarked: itemIsMarked,
                    data: itemData,
                    id: itemId
                });
            });

            // Then, send any exlusively local items server side
            $('.itemBox').each(function () {
                var $this = $(this);
                if ($this.hasClass("localOnly")) {
                    var item = {
                        "id": $this.parent().attr('id'),
                        "data": $this.val(),
                        "marked": $this.parent().hasClass("marked"),
                        "localOnly": true
                    };
                    shoppinglist.addItem(item);
                }

            });

            $('.item').each(function () {
                var $this = $(this);
                if ($this.hasClass("localOnly")) {
                    var item = {
                        "id": $this.parent().attr('id'),
                        "data": $this.val(),
                        "marked": $this.parent().hasClass("marked"),
                        "localOnly": true
                    };
                    alert("adding item upstream: " + item.id + " " + item.data + " " + item.marked + " " + item.localOnly);
                    shoppinglist.addItem(item);
                }

            });
        },

        /**
         *  Imports the database, either via WebSockets (if supported) or the regular ol' AJAX way.
         */
        importDatabase: function () {
            if (webSocketSupport()) {
                shoppinglistSocket.getAllItems();
            } else {
                alert("Falling back on AJAX connection.");
                if (utils.isMobile()) {
                    legacyShoppinglist.importMobileSite();
                } else {
                    legacyShoppinglist.importDatabase();
                }
            }
        }

    };
}());