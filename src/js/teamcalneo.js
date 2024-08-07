/**
 * Miscellaneous Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2024 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

/**
 * Initializes Ajax and creates xmlHttp object
 *
 * This function attempts to create an XMLHttpRequest object for making Ajax requests.
 * It first tries to create a standard XMLHttpRequest object. If that fails, it attempts to create an ActiveXObject for older versions of Internet Explorer.
 * If all attempts fail, it alerts the user that their browser does not support Ajax.
 *
 * @param {string} where - The DOM element ID where the output will be displayed.
 * @returns {XMLHttpRequest|boolean} The created XMLHttpRequest object, or false if the browser does not support Ajax.
 */
function getXMLHttpRequest(where) {
  let xmlHttp;
  try {
    //
    // Good browsers
    //
    xmlHttp = new XMLHttpRequest();
  } catch (e) {
    //
    // Bad browser: IE
    //
    try {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Your browser does not support AJAX!");
        return false;
      }
    }
  }
  xmlHttp.output = where;
  return xmlHttp;
}

/**
 * Callback function for xmlHttp object
 *
 * This function is called whenever the readyState of the xmlHttp object changes.
 * It updates the inner HTML of the DOM element specified by the `output` property of the xmlHttp object.
 *
 * @param {XMLHttpRequest} obj - The xmlHttp object whose state has changed.
 */
function stateChanged(obj) {
  document.getElementById(obj.output).innerHTML = "<img src=\"images/ajax-loader.gif\" alt=\"\" title=\"Processing...\">";
  if (obj.readyState === 4) {
    document.getElementById(obj.output).innerHTML = xmlHttp.responseText;
    obj = null;
  }
}

/**
 * Calls a PHP script to check whether a path exists
 *
 * This function sends an Ajax request to a PHP script to verify if a given path exists on the server.
 *
 * @param {string} reldir - The relative directory to the server root.
 * @param {string} url - The URL to be checked.
 * @param {string} where - The DOM element ID where the output will be displayed.
 */
function ajaxCheckPath(reldir, url, where) {
  let checkUrl = "helpers/ajax_checkpath.php?reldir=" + reldir + "&url=" + url;
  xmlHttp = getXMLHttpRequest(where);
  if (xmlHttp === false) {
    alert("No Ajax possible!");
    return;
  }
  if (xmlHttp.overrideMimeType) {
    xmlHttp.overrideMimeType('text/xml');
  }
  xmlHttp.callback = function () {
    stateChanged(xmlHttp);
  };
  xmlHttp.onreadystatechange = xmlHttp.callback;
  xmlHttp.open("POST", checkUrl, true);
  xmlHttp.send(null);
}

/**
 * Calls a PHP script to check database settings
 *
 * This function sends an Ajax request to a PHP script to verify the provided database settings.
 *
 * @param {string} server - The MySQL server name.
 * @param {string} user - The MySQL user name.
 * @param {string} pass - The MySQL password.
 * @param {string} db - The MySQL database name.
 * @param {string} prefix - The MySQL table prefix.
 * @param {string} where - The DOM element ID where the output will be displayed.
 */
function ajaxCheckDB(server, user, pass, db, prefix, where) {
  var url = "helpers/ajax_checkdb.php?server=" + server + "&user=" + user + "&pass=" + pass + "&db=" + db + "&prefix=" + prefix;
  xmlHttp = getXMLHttpRequest(where);
  if (xmlHttp === false) {
    alert("No Ajax possible!");
    return;
  }
  if (xmlHttp.overrideMimeType) {
    xmlHttp.overrideMimeType('text/xml');
  }
  xmlHttp.callback = function () {
    stateChanged(xmlHttp);
  };
  xmlHttp.onreadystatechange = xmlHttp.callback;
  xmlHttp.open("POST", url, true);
  xmlHttp.send(null);
}

/**
 * Toggles the visibility of a specified DOM element
 *
 * This function shows or hides a DOM element based on its current display state.
 *
 * @param {string} div - The ID of the DOM element to be shown or hidden.
 */
function showHide(div) {
  if (document.getElementById(div).style.display === 'block') {
    document.getElementById(div).style.display = 'none';
  } else {
    document.getElementById(div).style.display = 'block';
  }
}
