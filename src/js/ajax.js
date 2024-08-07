/**
 * Ajax Functions
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
 * @param string where = DOM object ID where the output goes
 * @return string Ajax output
 */
function getXMLHttpRequest(where) {
  var xmlHttp;
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
 * @param string obj = xmlHttp object
 */
function stateChanged(obj) {
  document.getElementById(obj.output).innerHTML = "<img src=\"images/ajax-loader.gif\" alt=\"\" title=\"Processing...\">";
  if (obj.readyState == 4) {
    document.getElementById(obj.output).innerHTML = xmlHttp.responseText;
    delete obj;
  }
}

/**
 * Calls a PHP script to check whether a path exists
 *
 * @param string reldir = relativ dir to server root
 * @param string url    = URL
 * @param string where  = DOM object ID where the output goes
 */
function ajaxCheckPath(reldir, url, where) {
  var url = "helpers/ajax_checkpath.php?reldir=" + reldir + "&url=" + url;
  xmlHttp = getXMLHttpRequest(where);
  if (xmlHttp == false) {
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
 * Calls a PHP script to check database settings
 *
 * @param string server  = mySQL server name
 * @param string user    = mySQL user name
 * @param string pass    = mySQL password
 * @param string db      = mySQL database name
 * @param string prefix  = mySQL table prefix
 * @param string where   = DOM object ID where the output goes
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
