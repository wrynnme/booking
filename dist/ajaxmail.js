var HttPRequest = false;
function doCallAjax() {
	HttPRequest = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		HttPRequest = new XMLHttpRequest();
		if (HttPRequest.overrideMimeType) {
			HttPRequest.overrideMimeType('text/html');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}	
	} 
	if (!HttPRequest) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	
	var url = '/booking/tgas/mail/AjaxPHPContactForm2.php';
	var pmeters = "tTo=" + encodeURI( document.getElementById("txtTo").value) +
	"&tSubject=" + encodeURI( document.getElementById("txtSubject").value ) +
	"&tDescription=" + encodeURI( document.getElementById("txtDescription").value ) +
	"&tFormName=" + encodeURI( document.getElementById("txtFormName").value ) +
	"&tFormEmail=" + encodeURI( document.getElementById("txtFormEmail").value ) + 
	"&tFormCcEmail=" + encodeURI( document.getElementById("txtFormCcEmail").value );

	HttPRequest.open('POST',url,true);

	HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	HttPRequest.setRequestHeader("Content-length", pmeters.length);
	HttPRequest.setRequestHeader("Connection", "close");
	HttPRequest.send(pmeters);
			
			
	HttPRequest.onreadystatechange = function() {

		if(HttPRequest.readyState == 3) { // Loading Request
			document.getElementById("mySpan").innerHTML = "Now is Loading...";
		}
		if(HttPRequest.readyState == 4) { // Return Request
			if(HttPRequest.responseText == 'Y') {
				document.getElementById("mySpan").innerHTML = "Email Sending";
				document.getElementById("tbContact").style.display = 'none';
			}else {
				document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
			}
		}			
	}
}