/*jslint browser: true, plusplus: true*/
/*global WebSocket, console, alert, log, shoppinglist, generateInterval*/
var shoppinglistSocket = (function () {
    'use strict';

    var connection, reconnectionAttempts = 1;

    function connect() {

        // Connect to the shoppinglist web socket server, store the connection in "connection"
        if (typeof WebSocket !== 'undefined') {
            try {
                connection = new WebSocket("ws://listan.axellarsson.nu:9001");
            } catch (ex) {
                console.error(ex);
            }
        } else {
            alert("Unfortunately this browser does not appear to support Web Sockets.");
            return false;
        }

        /*-----------------------------------
                    Attach common event
                        handlers
        -----------------------------------*/
        connection.onopen = function () {
            // Reset reconnectionAttempts
            reconnectionAttempts = 1;
            console.info("Connection opened.");
            // Trigger a sync with server
            shoppinglistSocket.getAllItems();
        };

        connection.onerror = function (error) {
            console.error('WebSocket Error ' + error);
        };

        connection.onclose = function () {
            console.info("Connection closed.");

            // Try to reconnect
            var time = generateInterval(reconnectionAttempts);
            setTimeout(function () {
                console.warn("Trying to reconnect... attempt #" + reconnectionAttempts);
                reconnectionAttempts += 1;
                connect();
            }, time);

        };

        connection.onmessage = function (e) {
            var msg = JSON.parse(e.data);
            
            switch (msg.type) {
            case "addedItem":
                console.info("Server: addedItem");
                shoppinglist.addItem(msg);
                break;
            case "removedItem":
                console.info("Server: removedItem");
                shoppinglist.removeItem(msg);
                break;
            case "updatedItem":
                console.info("Server: updatedItem");
                shoppinglist.updateItem(msg);
                break;
            case "clearedList":
                console.info("Server: clearedList");
                shoppinglist.clearList();
                break;
            case "setName":
                console.info("Server: setName");
                break;
            case "sentAllItems":
                console.info("Server: sentAllItems");
                shoppinglist.syncToDatabase(msg.data);
                break;
            case "error":
                console.error("FEL: " + msg.data);
                break;
            default:
                console.error("Okänt fel...");
                break;
            }
        };
    }

    connect();

    /*
     *  Exponential backoff algorithm uses this to try
     *  and reconnect less and less often as time passes
     *  @param k - the k:th attemp to reconnect
     *  @return a random number of seconds between 0 and k²-1
     *  but never more than 15 seconds
     */
    function generateInterval(k) {
        var maxInterval = (Math.pow(2, k) - 1) * 1000;

        if (maxInterval > 15 * 1000) {
            maxInterval = 15 * 1000; // truncate maxInterval if more than 15 seconds
        }

        // Return
        return Math.random() * maxInterval; // returns a random number 
    }

    /*-----------------------------------
               Public functions
        -----------------------------------*/
    return {

        addItem: function (data, id) {
            var msg = {
                "command": "addItem",
                "data": data,
                "id": id,
                "marked": false
            };
            console.debug("Sending message to server: " + data + ": " + id);
            connection.send(JSON.stringify(msg));
        },

        /**
         *	Removes the item with the specified id from the database
         *	@param id - the id of the item to remove
         */
        removeItem: function (id) {
            var msg = {
                "command": "removeItem",
                "data": "",
                "id": id,
                "marked": false
            };
            console.debug("Sending message to server: " + msg);
            connection.send(JSON.stringify(msg));
        },

        updateItem: function (item) {
            var msg = {
                "command": "updateItem",
                "data": item.data,
                "id": item.id,
                "marked": item.isMarked || false
            };
            console.debug("Sending message to server: " + msg);
            connection.send(JSON.stringify(msg));
        },

        getAllItems: function () {
            var msg = {
                "command": "getAllItems",
                "data": "",
                "id": "",
                "marked": false
            };
            console.debug("Sending message to server: " + msg);
            connection.send(JSON.stringify(msg));

        },

        clear: function () {
            var msg = {
                "command": "clearList",
                "data": "",
                "id": "",
                "marked": false
            };
            console.debug("Sending message to server: " + msg);
            connection.send(JSON.stringify(msg));
        },
        
        isConnected: function () {
            if (connection.readyState === WebSocket.OPEN) {
                console.debug("Connected to WebSocket!");
            } else {
                console.warn("Not connected");
            }
            return connection.readyState === WebSocket.OPEN;
        }
    };

}());