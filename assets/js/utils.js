/*jslint browser: true, plusplus: true */
var utils = (function () {
    "use strict";

    /**
     *	Lägger till inledande nollor till num på så vis att antalet siffror i resultatet blir size
     *	pre: num är exempelvis 3, size är 2
     *	post: funktion returnerar "03"
     *  @param num - siffran att nollutfylla
     *  @param size - önskad antal siffror i resultatet
     *  @return num paddat med 0:or på så vis att antalet siffror i num är size, om |num| <= size returneras num oförändrad
     */
    function pad(num, size) {
        var zeros = '',
            sizeOfNum = Math.floor(Math.log(num) / Math.LN10) + 1; // Calculate the number of digits of num
        if (sizeOfNum < size) {
            while (zeros.length < size - sizeOfNum) {
                zeros = '0' + zeros;
            }
        }
        return zeros + num;
    }

    return {

        /**
         *  Finds the next available itemId
         *  @return the next available itemId
         */
        getNextItemId: function () {

            var x = document.getElementById('shoppinglist'),
                itemId = "",
                i;

            for (i = 0; i < x.childNodes.length; i++) {
                itemId = x.childNodes[i].id;
            }
            if (typeof itemId === 'undefined') {
                return "item01";
            }
            itemId = itemId.substring('4'); // Slicar ut siffrorna
            itemId++; // Plussar på
            itemId = pad(itemId, '2'); // Paddar med inledande nollor
            itemId = 'item' + itemId; // Lägger till texten igen
            return itemId;
        },

        /**
         *  Tells if current user is a mobile user or not
         *  @return true if mobile site, false otherwise
         */
        isMobile: function () {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true;
            } else {
                return false;
            }
        }

    };

}());