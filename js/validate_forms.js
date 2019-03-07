/**

 * Created by carlos.duno on 29-11-2016.

 */

$( document ).ready(function() {



    







});


/**
 * Para obtener variables get
 * @param String name
 * @return String
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? -1 : decodeURIComponent(results[1].replace(/\+/g, " "));
}