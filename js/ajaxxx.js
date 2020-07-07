var ajax = null;
if (window.ActiveXObject) {
  ajax = new ActiveXObject ("Microsoft.XMLHTTP");
  alert("IE");
}else if (window.XMLHttpRequest) {
  ajax = new XMLHttpRequest();
  alert("Not IE");
}else {
  alert("Your Browser doesn't support Ajax");
  return;
}
function ajaxLoad(method, URL, data, displayId) {
  method = method.toLowerCase();
  URL += "?dummy=" + (new Date()).getTime();
  if (method.toLowerCase() == "get") {
    URL += "&" + data;
    data = null;
  }
  ajax.open(method, URL);
  if (method.toLowerCase() == "post") {
    ajax.setRequsetHeader(
      "Content-Type","application/x-www-form-urlencoded");
  }
  ajax.onreadystatechange = function() {
    if (ajax.readyState == 4 && ajax.status == 200) {
      var ctype = ajax.getResponseHeader("Content-Type");
      ctype = ctype.toLowerCase();
      ajaxCallback(ctype, displayId, ajax.responseText);
      delete ajax;
      ajax = null;
    }
  }
  ajax.sed(data);
}
function ajaxCallback(ContentType, displayId, responseText) {
  if (ContentType.match("text/javascript")) {
    eval(responseText);
  }else {
    var el = document.getElementById(displayId);
    el.innerHTML = responseText;
  }
}
