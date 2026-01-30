/**
 * Miscellaneous Functions
 *
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2014-2026 by George Lewe
 * @link https://www.lewe.com
 *
 * @package TeamCal Neo
 * @since 3.0.0
 */

//-----------------------------------------------------------------------------
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
  var url = "src/Helpers/ajax_checkdb.php?server=" + server + "&user=" + user + "&pass=" + pass + "&db=" + db + "&prefix=" + prefix;
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


//-----------------------------------------------------------------------------
/**
 * Changes the width of all div elements with class "container" or "container-fluid".
 *
 * This function toggles the class of div elements between "container" and "container-fluid"
 * based on the provided width parameter.
 *
 * @param {string} width - The desired width. If 'full', changes "container" to "container-fluid".
 *                         Otherwise, changes "container-fluid" to "container".
 */
function changeWidth(width) {
  if (width === 'full') {
    // Select all div elements with the class "container"
    const containers = document.querySelectorAll('div.container');
    // Loop through each element and replace the class
    containers.forEach(container => {
      container.classList.remove('container');
      container.classList.add('container-fluid');
    });
  } else {
    // Select all div elements with the class "container-fluid"
    const containers = document.querySelectorAll('div.container-fluid');
    // Loop through each element and replace the class
    containers.forEach(container => {
      container.classList.remove('container-fluid');
      container.classList.add('container');
    });
  }
}

//-----------------------------------------------------------------------------
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

//-----------------------------------------------------------------------------
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

//-----------------------------------------------------------------------------
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
