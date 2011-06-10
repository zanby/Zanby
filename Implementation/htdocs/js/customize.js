/* -- Customize -- */

function loadCurrentCustomSettingsToTarget(_settingsString, targetCOT) {

	var headlineFontFamily = '';
	/* gathering DOM objects */
	var currentTarget = document.getElementById(targetCOT);
	/* headlines */
	var h2Collection = currentTarget.getElementsByTagName('h2');
	/* headers */
	var h3Collection = currentTarget.getElementsByTagName('h3');
	/* body texts (paragraphs) */
	var pCollection = currentTarget.getElementsByTagName('p');
	/* comments */
	var spanCollection = currentTarget.getElementsByTagName('span');
	/* concat */
	var spanFilteredCollection = [];
		var j =0;
		for (var i=0; i<spanCollection.length; i++) {
			if ((spanCollection[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection[i].className.substring(0, 10) == 'znbCO-date')) {
				spanFilteredCollection[j] = spanCollection[i];
				j++;
			}	
		}
	/* links */
		var aCollection = currentTarget.getElementsByTagName('a');
		
		
		/* border */
		
		var coCollection = currentTarget.getElementsByTagName('a');
		
		coCollection = YAHOO.util.Dom.getElementsByClassName('znbContentObject', 'DIV', document.body);
		
		
		
		

	var headLineFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headerFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var bodyTextFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var commentFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var linkColorValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headLineColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headerColorValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var bodyTextColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var commentColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var borderColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var borderStyleValue = _settingsString.substr(0,  _settingsString.length);
	/* alerting combined values */
	
	/*alert('Headline:' + combineFontFamilyPropertyClassName(headLineFontFamilyValue));
	alert('Header:' + combineFontFamilyPropertyClassName(headerFontFamilyValue));
	alert('Body Text:' + combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
	alert('Comment:' + combineFontFamilyPropertyClassName(commentFontFamilyValue));
	alert('Link Color:' + combineColorPropertyClassName(linkColorValue));*/
	
	/* loading settings */
		
		/* to headlines */	
		resetProperty(h2Collection);
		setProperty(h2Collection, combineFontFamilyPropertyClassName(headLineFontFamilyValue));
		/* to headers */
		resetProperty(h3Collection);
		setProperty(h3Collection, combineFontFamilyPropertyClassName(headerFontFamilyValue));
		setProperty(h3Collection, combineColorPropertyClassName(headerColorValue));
		/* to body texts */
		resetProperty(pCollection);
		setProperty(pCollection, combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
		setProperty(pCollection, combineColorPropertyClassName(bodyTextColorValue));
		/* to comments*/
		resetProperty(spanFilteredCollection);
		setProperty(spanFilteredCollection, combineFontFamilyPropertyClassName(commentFontFamilyValue));
		setProperty(spanFilteredCollection, combineColorPropertyClassName(commentColorValue));
		/* to links */
		resetProperty(aCollection);
		setProperty(aCollection, combineColorPropertyClassName(linkColorValue));
		
		/* to outline */
		//resetProperty (coCollection);
		//setProperty (coCollection, combineColorPropertyClassName(borderColorValue));
		//setProperty (coCollection, combineOutlinePropertyClassName(borderStyleValue));
	
}
//-----------------------------------------------------------------------------------------------------------------

function loadCurrentCustomSettings(_settingsString) {

	var headlineFontFamily = '';
	
	/* gathering DOM objects */
	var ddTarget1 = document.getElementById('ddTarget1');
	var ddTarget2 = document.getElementById('ddTarget2');
	var ddSettingTarget = document.getElementById('znbTheme-setting');
	
		/* headlines */
		var h2Collection1 = ddTarget1.getElementsByTagName('h2');
		var h2Collection2 = ddTarget2.getElementsByTagName('h2');
		var h2Collection3 = ddSettingTarget.getElementsByTagName('h2');
		
		/* headers */
		var h3Collection1 = ddTarget1.getElementsByTagName('h3');
		var h3Collection2 = ddTarget2.getElementsByTagName('h3');
		var h3Collection3 = ddSettingTarget.getElementsByTagName('h3');

		/* body texts (paragraphs) */
		var pCollection1 = ddTarget1.getElementsByTagName('p');
		var pCollection2 = ddTarget2.getElementsByTagName('p');
		var pCollection3 = ddSettingTarget.getElementsByTagName('p');

		/* comments */
		var spanCollection1 = ddTarget1.getElementsByTagName('span');
		var spanCollection2 = ddTarget2.getElementsByTagName('span');
		var spanCollection3 = ddSettingTarget.getElementsByTagName('span');
		
		
		/* concat */

		var spanFilteredCollection = [];
		var j =0;
		for (var i=0; i<spanCollection1.length; i++) {
			if ((spanCollection1[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection1[i].className.substring(0, 10) == 'znbCO-date')) {
				spanFilteredCollection[j] = spanCollection1[i];
				j++;
			}	
		}
		
		for (var i=0; i < spanCollection2.length; i++) {
			if ((spanCollection2[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection2[i].className.substring(0, 10) == 'znbCO-date')) {
				spanFilteredCollection[j] = spanCollection2[i];
				j++;
			}
		}
		
		for (var i=0; i < spanCollection3.length; i++) {
			if ((spanCollection3[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection3[i].className.substring(0, 10) == 'znbCO-date')) {
				spanFilteredCollection[j] = spanCollection3[i];
				j++;
			}
		}

		/* links */
		var aCollection1 = ddTarget1.getElementsByTagName('a');
		var aCollection2 = ddTarget2.getElementsByTagName('a');
		var aCollection3 = ddSettingTarget.getElementsByTagName('a');
		
		/* border */
		
		var coCollection1 = ddTarget1.getElementsByTagName('a');
		
		coCollection = YAHOO.util.Dom.getElementsByClassName('znbContentObject', 'DIV', document.body);
		
		
	
	/* parsing settings string */

	var headLineFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headerFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var bodyTextFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var commentFontFamilyValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var linkColorValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headLineColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var headerColorValue = _settingsString.substr(0, _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var bodyTextColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var commentColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var borderColorValue = _settingsString.substr(0,  _settingsString.indexOf(' '));
	_settingsString= _settingsString.substr(_settingsString.indexOf(' ')+1, _settingsString.length);
	
	var borderStyleValue = _settingsString.substr(0,  _settingsString.length);
	
	
	//var commentColorValue = _settingsString.substr(0,  _settingsString.length);
	
	/* alerting combined values */
	
	/*alert('Headline:' + combineFontFamilyPropertyClassName(headLineFontFamilyValue));
	alert('Header:' + combineFontFamilyPropertyClassName(headerFontFamilyValue));
	alert('Body Text:' + combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
	alert('Comment:' + combineFontFamilyPropertyClassName(commentFontFamilyValue));
	alert('Link Color:' + combineColorPropertyClassName(linkColorValue));*/
	
	/* loading settings */
		
		/* to headlines */	
		resetProperty(h2Collection1);
		setProperty(h2Collection1, combineFontFamilyPropertyClassName(headLineFontFamilyValue));
		resetProperty(h2Collection2);
		setProperty(h2Collection2, combineFontFamilyPropertyClassName(headLineFontFamilyValue));
		resetProperty(h2Collection3);
		setProperty(h2Collection3, combineFontFamilyPropertyClassName(headLineFontFamilyValue));
		
		
		/* to headers */
		resetProperty(h3Collection1);
		setProperty(h3Collection1, combineFontFamilyPropertyClassName(headerFontFamilyValue));
		setProperty(h3Collection1, combineColorPropertyClassName(headerColorValue));
		resetProperty(h3Collection2);
		setProperty(h3Collection2, combineFontFamilyPropertyClassName(headerFontFamilyValue));
		setProperty(h3Collection2, combineColorPropertyClassName(headerColorValue));
		resetProperty(h3Collection3);
		setProperty(h3Collection3, combineFontFamilyPropertyClassName(headerFontFamilyValue));
		setProperty(h3Collection3, combineColorPropertyClassName(headerColorValue));
		
		/* to body texts */
		resetProperty(pCollection1);
		setProperty(pCollection1, combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
		setProperty(pCollection1, combineColorPropertyClassName(bodyTextColorValue));
		resetProperty(pCollection2);
		setProperty(pCollection2, combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
		setProperty(pCollection2, combineColorPropertyClassName(bodyTextColorValue));
		resetProperty(pCollection3);
		setProperty(pCollection3, combineFontFamilyPropertyClassName(bodyTextFontFamilyValue));
		setProperty(pCollection3, combineColorPropertyClassName(bodyTextColorValue));
		
		/* to comments*/
		resetProperty(spanFilteredCollection);
		setProperty(spanFilteredCollection, combineFontFamilyPropertyClassName(commentFontFamilyValue));
		setProperty(spanFilteredCollection, combineColorPropertyClassName(commentColorValue));
		

		/* to links */
		resetProperty(aCollection1);
		setProperty(aCollection1, combineColorPropertyClassName(linkColorValue));
		resetProperty(aCollection2);
		setProperty(aCollection2, combineColorPropertyClassName(linkColorValue));
		resetProperty(aCollection3);
		setProperty(aCollection3, combineColorPropertyClassName(linkColorValue));
		
		/* to outline */
		resetProperty (coCollection);
		setProperty (coCollection, combineColorPropertyClassName(borderColorValue));
		setProperty (coCollection, combineOutlinePropertyClassName(borderStyleValue));
}

function saveCustomSettings(_headlineFontFamilyValue, _headerFontFamilyValue, _bodyTextFontFamilyValue, _commentFontFamilyValue, _linkColorValue) {
		//alert(_headlineFontFamilyValue + ' ' + _headerFontFamilyValue + ' ' + _bodyTextFontFamilyValue + ' ' + _commentFontFamilyValue + ' ' + _linkColorValue);
		return _headlineFontFamilyValue + ' ' + _headerFontFamilyValue + ' ' + _bodyTextFontFamilyValue + ' ' + _commentFontFamilyValue + ' ' + _linkColorValue;
}

function combineFontFamilyPropertyClassName(_value) {
		return ' ' + 'znbFontFamily' + _value;
}

function combineColorPropertyClassName(_value) {
		return ' ' + 'themeColor' + _value;
}

function combineOutlinePropertyClassName (_value) {
	        return ' ' + 'themeOutline' + _value;
}

function resetProperty(_collection) {
	for (var i=0; i < _collection.length; i++) {
		if (_collection[i].className.indexOf(' ') >= 0) {
			_collection[i].className = _collection[i].className.substr(0, _collection[i].className.indexOf(' '));
		}
                if (_collection[i].className.indexOf('znbFontFamily') >= 0) {
			_collection[i].className = '';
		}
		if (_collection[i].className.indexOf('themeColor') >= 0) {
			_collection[i].className = '';
		}
		if (_collection[i].className.indexOf('themeOutline') >= 0) {
			_collection[i].className = '';
		}
	}
}

function getProperty(_collection) {
	if (_collection[0].className.indexOf(' ') > 0) {
		return(_collection[0].className.substr(_collection[0].className.lastIndexOf(' '), _collection[0].className.length));
	}
	return -1;
}

function setProperty(_collection, _propertyClassName) {
	for (var i=0; i<_collection.length; i++) {
			_collection[i].className += _propertyClassName;
	}
}

function applyCustomSetting ()
{
    var v = new Array(11);
    var conf = '';
    for (i=1;i<12;i++)
    {
        if (document.getElementById('znbTheme-val' + i))
            {v[i] = document.getElementById('znbTheme-val' + i).value + '';}
        else
            {v[i] = 2 + '';}
        conf = conf + v[i] + ' ';
    }
    loadCurrentCustomSettings(conf);
}

function getCustomSetting ()
{
    var v = new Array(11);
    var conf = '';
    for (i=1;i<12;i++)
    {
        if (document.getElementById('znbTheme-val' + i))
            {v[i] = document.getElementById('znbTheme-val' + i).value + '';}
        else
            {v[i] = 2 + '';}
        conf = conf + v[i] + ' ';
    }
    return conf;
}

//Color picker*******************************************

function themeCreateColorList (parrentObj, tagName, colorCount, inputID)
{
    var _thinput = document.getElementById(inputID);
    var colorItems = Array(colorCount);
	//var colorItemsaaa = '';
    var _thcolor = '';
    for (var i = 0; i < colorCount; i++){
        colorItems[i] = document.createElement(tagName);
        colorItems[i].className = 'themeColor' + (i + 1);
        parrentObj.appendChild (colorItems[i]);
        _thcolor = getStyle(colorItems[i], 'color');
        colorItems[i].innerHTML = '<a href="#null" style="background-color: ' + _thcolor + ';" onclick="document.getElementById(' + "'" + inputID + "'" + ').value=' + "'" + (i + 1) + "'; applyCustomSetting();setSimpleColor();" + '"></a>';
		//colorItemsaaa = colorItemsaaa+'"'+convertRGBToHex(_thcolor)+'",';
		
		//if (i==69){colorItems[i].innerHTML = colorItemsaaa;}
    }
	
}


/*function convertRGBToHex(col) {
	var re = new RegExp("rgb\\s*\\(\\s*([0-9]+).*,\\s*([0-9]+).*,\\s*([0-9]+).*\\)", "gi");

	var rgb = col.replace(re, "$1,$2,$3").split(',');
	if (rgb.length == 3) {
		r = parseInt(rgb[0]).toString(16);
		g = parseInt(rgb[1]).toString(16);
		b = parseInt(rgb[2]).toString(16);

		r = r.length == 1 ? '0' + r : r;
		g = g.length == 1 ? '0' + g : g;
		b = b.length == 1 ? '0' + b : b;

		return "#" + r + g + b;
	}

	return col;
} 

*/









///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//	Õ≈«¿¬»—»Ã€…  ŒÀŒ–œ» ≈–
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function themeCreateColorListSingle (parrentObj, tagName, colorCount, inputID)
{
    var _thinput = document.getElementById(inputID);
    var colorItems = Array(colorCount);
    var _thcolor = '';
    for (var i = 0; i < colorCount; i++){
        colorItems[i] = document.createElement(tagName);
        colorItems[i].className = 'themeColor' + (i + 1);
        parrentObj.appendChild (colorItems[i]);
        _thcolor = getStyle(colorItems[i], 'color');
        colorItems[i].innerHTML = '<a href="#null" style="background-color: ' + _thcolor + ';" onclick="document.getElementById(' + "'" + inputID + "'" + ').value=' + "'" + (i + 1) + "'; applyBackgroundColor();" + '"></a>';
    }
}





function showColPickerSingle (id, inputID, x, y)
{
    if (document.getElementById('znbColorPicker' + id))
    {
        hideColorPicker('znbColorPicker' + id);
    }
    else
    {
        var oldColor = document.getElementById(inputID).value;
        var colorPicker = document.createElement('div');
        colorPicker.className = 'sm-popup znbPickColor-popup';
        colorPicker.id = 'znbColorPicker' + id;
        colorPicker.style.left = x + 'px';
        colorPicker.style.top = y + 'px';
        colorPicker.style.width = '245px';
        colorPicker.style.zoom = '1';
        colorPicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="hideColorPickerSingle(' + "'" + colorPicker.id + "'" + ');"><span></span></a><h1>Pick a color</h1><div class="clear"><span/></div><div class="pu-content"><ul class="znbTheme-colorlist" id="znbTheme-colorlist' + id + '"></ul></div><div class="co-buttons-pannel"><div style=""><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="document.getElementById(' + "'" + inputID + "').value = " + oldColor + ';applyBackgroundColor(); hideColorPickerSingle(' + "'" +  colorPicker.id + "'" + ');">Cancel</a></div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="hideColorPickerSingle(' + "'" + colorPicker.id + "'" + ');">OK</a></div></div></div><div class="clear"><span/></div></div></div>';
        document.body.appendChild (colorPicker);
        themeCreateColorListSingle(document.getElementById('znbTheme-colorlist' + id), 'li', 70, inputID);
        if (typeof(WCH) != 'undefined'){WCH.Apply(colorPicker);}
    }
   // setSimpleColor();
}


function applyBackgroundColor()
{
//	alert('app');
    if (document.getElementById('znbTheme-font9')){
	    var linkColorValue = document.getElementById('znbTheme-font9').value + '';
	} else {
	    var linkColorValue = 2 + '';
	}
  
  
  var prevEl = document.getElementById('xxxzzz');
  var aCollection = prevEl.getElementsByTagName('a');
  resetProperty(aCollection);
  setProperty(aCollection, combineColorPropertyClassName(linkColorValue));
  applyClassNameToBackround;
  
    
}
function hideColorPickerSingle (id)
{
    if (document.getElementById(id)){
        obj = document.getElementById(id); document.body.removeChild(obj);
        if (typeof(WCH) != 'undefined'){WCH.Discard(obj);}
    }
  //  setSimpleColor();
}

function applyClassNameToBackround ()
{
   
        document.getElementById('znbTheme-viewcolor1').style.backgroundColor = getStyle(document.getElementById('zndTheme-live-simple1'), 'color');
   
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////













function showColPicker (id, inputID, x, y)
{
    hideAllPopups();
    if (document.getElementById('znbColorPicker' + id))
    {
        hideColorPicker('znbColorPicker' + id);
    }
    else
    {
        var oldColor = document.getElementById(inputID).value;
        var colorPicker = document.createElement('div');
        colorPicker.className = 'sm-popup znbPickColor-popup';
        colorPicker.id = 'znbColorPicker' + id;
        colorPicker.style.left = x + 'px';
        colorPicker.style.top = y + 'px';
        colorPicker.style.width = '245px';
        colorPicker.style.zoom = '1';
        colorPicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="hideColorPicker(' + "'" + colorPicker.id + "'" + ');"><span></span></a><h1>Pick a color</h1><div class="clear"><span/></div><div class="pu-content"><ul class="znbTheme-colorlist" id="znbTheme-colorlist' + id + '"></ul></div><div class="co-buttons-pannel"><div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="document.getElementById(' + "'" + inputID + "').value = " + oldColor + ';applyCustomSetting(); hideColorPicker(' + "'" +  colorPicker.id + "'" + ');">Cancel</a></div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="hideColorPicker(' + "'" + colorPicker.id + "'" + ');">OK</a></div></div></div><div class="clear"><span/></div></div></div>';
        document.body.appendChild (colorPicker);
        themeCreateColorList(document.getElementById('znbTheme-colorlist' + id), 'li', 70, inputID); 
        if (typeof(WCH) != 'undefined'){WCH.Apply(colorPicker);}
    }
    setSimpleColor();
}

function hideColorPicker (id)
{
    if (document.getElementById(id)){
        obj = document.getElementById(id); document.body.removeChild(obj);
        if (typeof(WCH) != 'undefined'){WCH.Discard(obj);}
    }
    setSimpleColor();
}

function showOutlinePicker (id, inputID, x, y)
{
    hideAllPopups();
    if (document.getElementById('znbOutlinePicker' + id))
    {
        hideOutlinePicker('znbColorPicker' + id);
    }
    else
    {
	var oldOutline = document.getElementById(inputID).value;
        var outlinePicker = document.createElement('div');
        outlinePicker.className = 'sm-popup znbPickColor-popup';
        outlinePicker.id = 'znbColorPicker' + id;
        outlinePicker.style.left = x + 'px';
        outlinePicker.style.top = y + 'px';
        outlinePicker.style.width = '245px';
        outlinePicker.style.zoom = '1';
        outlinePicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="hideOutlinePicker(' + "'" + outlinePicker.id + "'" + ');"><span></span></a><h1>Outline</h1><div class="clear"><span/></div><div class="pu-content"><ul class="znbTheme-outlinelist" id="znbTheme-outlinelist' + id + '"></ul></div><div class="co-buttons-pannel"><div style=""><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="document.getElementById(' + "'" + inputID + "').value = " + oldOutline + ';applyCustomSetting(); hideOutlinePicker(' + "'" +  outlinePicker.id + "'" + ');">Cancel</a></div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="hideOutlinePicker(' + "'" + outlinePicker.id + "'" + ');">OK</a></div></div></div><div class="clear"><span/></div></div></div>';
        document.body.appendChild (outlinePicker);
        themeCreateOutlineList(document.getElementById('znbTheme-outlinelist' + id), 'li', 3, inputID);
        if (typeof(WCH) != 'undefined'){WCH.Apply(colorPicker);}
    }
}

function themeCreateOutlineList (parrentObj, tagName, outlineCount, inputID)
{
    var _thinput = document.getElementById(inputID);
    var outlineItems = Array(outlineCount);
    var _thoutline = '';
    for (var i = 0; i < outlineCount; i++){
        outlineItems[i] = document.createElement(tagName);
        outlineItems[i].className = 'themeOutline' + (i + 1);
	outlineItems[i].id = 'themeOutline-item' + (i + 1);
        parrentObj.appendChild (outlineItems[i]);
        _thcolor = getStyle(outlineItems[i], 'border-style');
        outlineItems[i].innerHTML = '<a href="#null" onclick="document.getElementById(' + "'" + inputID + "'" + ').value=' + "'" + (i + 1) + "'; applyCustomSetting();setSimpleColor(); themeSetActiveOutlineItem(document.getElementById('" + parrentObj.id + "'), this);" + '"><span></span></a>';
    }
}

function themeSetActiveOutlineItem (parrentObj, item)
{
     var _items = parrentObj.getElementsByTagName(item.tagName)
     for (var i = 0; i < _items.length; i++)
     {
	if (_items[i].className == 'znbTheme-outlinelist-active'){_items[i].className = '';}
     }
     item.className = 'znbTheme-outlinelist-active';
}

function hideOutlinePicker (id)
{
    if (document.getElementById(id)){
        obj = document.getElementById(id); document.body.removeChild(obj);
        if (typeof(WCH) != 'undefined'){WCH.Discard(obj);}
    }	
}

function setSimpleColor ()
{
    for (var i = 4; i < 6; i++){
        document.getElementById('znbTheme-viewcolor' + i).style.backgroundColor = getStyle(document.getElementById('zndTheme-live-simple' + i), 'color');
    }
}

function hideAllPopups ()
{
    var _popups = YAHOO.util.Dom.getElementsByClassName('znbPickColor-popup', 'DIV', document.body);
    for (var i = 0; i<_popups.length; i++)
    {
	document.body.removeChild(_popups[i]);
	if (typeof(WCH) != 'undefined'){WCH.Discard(_popups[i]);}
    }
}





//UI

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

function getStyle(el,styleProp)
{
    var x = el;
    if (x.currentStyle)
        var y = x.currentStyle[styleProp];
    else if (window.getComputedStyle)
        var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
    return y;
}




