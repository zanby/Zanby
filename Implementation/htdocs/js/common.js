function getMouseCoordinateX(e)
{
    getMouseCoordinates(e);
    return mousex;
}

function getMouseCoordinateY(e)
{
    getMouseCoordinates(e);
    return mousey;
}

function getMouseCoordinates(e)
{
    isDOM=document.getElementById;
    isOpera=isOpera5=window.opera && isDOM ;
    isOpera6=isOpera && window.print ;
    isOpera7=isOpera && document.readyState;
    isMSIE=document.all && document.all.item && !isOpera ;
    isMSIE5=isDOM && isMSIE ;
    isNetscape4=document.layers ;
    isMozilla=isDOM && navigator.appName=="Netscape";

    mousex = 0;
    mousey = 0;

    if(isNetscape4) document.captureEvents(Event.MOUSEMOVE)
    if(isMSIE || isOpera7)
    {
        mousex=event.clientX+document.body.scrollLeft;
        mousey=event.clientY+document.body.scrollTop;
        return true;
    }
    else if(isOpera)
    {
        mousex=event.clientX;
        mousey=event.clientY;
        return true;
    }
    else if(isNetscape4 || isMozilla)
    {
        mousex = e.pageX;
        mousey = e.pageY;
        return true;
    }
}

var close_dialog = function(name) {
	var dialog = xajaxRequestManager.OverlayManager.find(name);
	if ( dialog ) {
	    dialog.cancel();
	}
 };
 
var destroy_dialog = function(name) {
	var dialog = xajaxRequestManager.OverlayManager.find(name);
	if ( dialog ) {
	    dialog.destroy();
	}
 };

 
 var bookmark_redirect = function(url) {
	window.open(url, '_blank');	
 }

// Removes leading whitespaces
String.prototype.ltrim = function () {
	var re = /\s*((\S+\s*)*)/;
	return this.replace(re, "$1");

};

String.prototype.rtrim = function () {
	var re = /((\s*\S+)*)\s*/;
	return this.replace(re, "$1");
};

String.prototype.trim = function () {
	return this.ltrim().rtrim();
};

// Returns coordinates of HTML element
function getElementPosition (object)
{
	var pos = new Array(2);
	pos[0] = object.offsetLeft;
	pos[1] = object.offsetTop;
	while (object.offsetParent)
	{
	    object = object.offsetParent
	    pos[0] = pos[0] + object.offsetLeft;
	    pos[1] = pos[1] + object.offsetTop;
	}
	return pos;
}
// hide all popups (AKColorPicker, AKLinePicker)
function hideAllAKPopups ()
{
    var _popups = YAHOO.util.Dom.getElementsByClassName('znbPickColor-popup', 'DIV', document.body); 
    for (var i = 0; i<_popups.length; i++)
    {
  	   document.body.removeChild(_popups[i]);
  	   if (typeof(WCH) != 'undefined'){WCH.Discard(_popups[i]);}
    }
}

//set Days For Select Correspond with Month and Year
function setOptionsInDaysSelect(daysFieldId, month, year)
{
	var daysInMonth = month == 2 ? (year % 4 ? 28 : (year % 100 ? 29 : (year % 400 ? 28 : 29))) : ((month - 1) % 7 % 2 ? 30 : 31);
    var daysField = document.getElementById(daysFieldId);
    if (!daysField) return;

    var firstValue = daysField.options[0].value;
    var addOptins = ( firstValue == '' ) ? 1 : 0;
    
	if (daysField.options.length > daysInMonth + addOptins) {
		daysField.options.length = daysInMonth + addOptins;
        return;
	}
	if (daysField.options.length < daysInMonth + addOptins) {
		for (var i = daysField.options.length + 1 - addOptins; i <= daysInMonth; i++) {
			text = (i < 10)?"0" + i:i;
			option = new Option(i, text);
			option.label = text;
			daysField.options.add(option);
		}
	}
}
