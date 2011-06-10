//------------------------------------------------------------------------------------------------------
function showLoadPreset (id, oldOutline, x, y, func, editor_id)
{
	//znbOutlinePicker 
    if (document.getElementById('znbPicker' + id))
    {
        hideAKLinePicker('znbPicker' + id);
    }
    else
    {
		hideAllAKPopups();
		if (func.indexOf('#')>0)
		{
		   _func = func.replace('#', oldOutline);
		   oldOutlineFunc = _func;
		}
		else
		{
		   oldOutlineFunc = func+'(' + "'" + oldOutline + "'" + ')';   
		} 
		 
		y = y - 8; 
        var outlinePicker = document.createElement('div');
        outlinePicker.className = 'sm-popup prPickColor-popup';    
        outlinePicker.id = 'znbPicker' + id;
        outlinePicker.style.left = x + 'px';
        outlinePicker.style.top = y + 'px';
        outlinePicker.style.width = '135px';
        outlinePicker.style.zoom = '1';
        outlinePicker.style.padding = '5px 10px';
        outlinePicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="' + oldOutlineFunc + '; hideAKLinePicker(' + "'" +  outlinePicker.id + "'" + ');"><span></span></a><div class="pu-content"><ul id="znbTheme-outlinelist' + id + '"></ul></div></div>';
        
        funcArr = [];
        funcArr[1] = 'tinyMCE.execInstanceCommand(\''+editor_id+'\',\'mceInsertContent\',false,\' '+COHeadlineText+' \');'
        funcArr[2] = 'tinyMCE.execInstanceCommand(\''+editor_id+'\',\'mceInsertContent\',false,\' '+CODescriptionText+' \');'
        
		document.body.appendChild (outlinePicker);
        createAKLineList(document.getElementById('znbTheme-outlinelist' + id), 'li', 3, oldOutline, funcArr);
        if (typeof(WCH) != 'undefined'){WCH.Apply(outlinePicker);}
    }
}
//------------------------------------------------------------------------------------------------------
function setActiveAKLineItem (parrentObj, item)
{
     var _items = parrentObj.getElementsByTagName(item.tagName)
     for (var i = 0; i < _items.length; i++)
     {
		if (_items[i].className == 'prTheme-outlinelist-active'){_items[i].className = '';}
     }
     item.className = 'prTheme-outlinelist-active';
}
//------------------------------------------------------------------------------------------------------
function hideAKLinePicker(id)
{
    if (document.getElementById(id)){
        obj = document.getElementById(id); document.body.removeChild(obj);
        if (typeof(WCH) != 'undefined'){WCH.Discard(obj);}
    }	
}
//------------------------------------------------------------------------------------------------------
       function createAKLineList(parrentObj, tagName, outlineCount, oldOutline, func)
{
    var lines = new Array("none","solid","dashed"); 
    //var _thinput = document.getElementById(inputID);
    var outlineItems = Array(lines.length);
//    var _thoutline = '';
    for (var i = 0; i < lines.length; i++){
        outlineItems[i] = document.createElement(tagName);
        outlineItems[i].className = 'themeOutline' + (i + 1);// лаже
    //outlineItems[i].id = 'themeOutline-item' + (i + 1);
        parrentObj.appendChild (outlineItems[i]);
      //  _thcolor = getStyle(outlineItems[i], 'border-style');
        outlineItems[i].innerHTML = '<a href="#null" onclick="'+func+'(' + "'" + lines[i] + "'" + ');'+" setActiveAKLineItem(document.getElementById('" + parrentObj.id + "'), this);" + '"><span></span></a>';
        
    if (func.indexOf('#')>0)
    {
       _func = func.replace('#', lines[i]);
       outlineItems[i].innerHTML = '<a href="#null" onclick="'+_func+';'+" setActiveAKLineItem(document.getElementById('" + parrentObj.id + "'), this);" + '"><span></span></a>';
    }
    else
    {
       outlineItems[i].innerHTML = '<a href="#null" onclick="'+func+'(' + "'" + lines[i] + "'" + ');'+" setActiveAKLineItem(document.getElementById('" + parrentObj.id + "'), this);" + '"><span></span></a>';   
    }
       
        
    }
}

//------------------------------------------------------------------------------------------------------