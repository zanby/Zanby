//------------------------------------------------------------------------------------------------------
function showAKColorPicker (id, oldColor, x, y, func)
{
    if (document.getElementById('znbPicker' + id))
    {
        hideAKColorPicker('znbPicker' + id);
    }
    else
    {
		hideAllAKPopups();
 		if (func.indexOf('#')>0)
		{
		   _func = func.replace('#', oldColor);
		   oldColorFunc = _func;
		}
		else
		{
		   oldColorFunc = func+'(' + "'" + oldColor + "'" + ')';   
		} 
 
 
        var colorPicker = document.createElement('div');
        colorPicker.className = 'sm-popup znbPickColor-popup';
        colorPicker.id = 'znbPicker' + id;
        colorPicker.style.left = x + 'px';
        colorPicker.style.top = y + 'px';
        colorPicker.style.width = '245px';
        colorPicker.style.zoom = '1';
        colorPicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="' + oldColorFunc + '; hideAKColorPicker(' + "'" +  colorPicker.id + "'" + ');"><span></span></a><h1>Pick a color</h1><div class="clear"><span/></div><div class="pu-content"><ul class="znbTheme-colorlist" id="znbTheme-colorlist' + id + '"></ul></div><div class="co-buttons-pannel"><div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="' + oldColorFunc + '; hideAKColorPicker(' + "'" +  colorPicker.id + "'" + ');">Cancel</a></div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');">OK</a></div></div></div><div class="clear"><span/></div></div></div>';
        document.body.appendChild (colorPicker);
        createAKCPColorList(document.getElementById('znbTheme-colorlist' + id), 'li', oldColor, func);
        if (typeof(WCH) != 'undefined'){WCH.Apply(colorPicker);}
    }
}
//TMCE STYLE
function showAKColorPickerMCEStyle(id, oldColor, x, y, func)
{
    if (document.getElementById('znbPicker' + id))
    {
        hideAKColorPicker('znbPicker' + id);
    }
    else
    {
		hideAllAKPopups();
 		if (func.indexOf('#')>0)
		{
		   _func = func.replace('#', oldColor);
		   oldColorFunc = _func;
		}
		else
		{
		   oldColorFunc = func+'(' + "'" + oldColor + "'" + ')';   
		} 
 
 
        var colorPicker = document.createElement('div');
        colorPicker.className = 'mceMenu znbPickColor-popup';
		colorPicker.style.display="block";
        colorPicker.id = 'znbPicker' + id;
        colorPicker.style.left = x + 'px';
        colorPicker.style.top = y + 'px';
        //colorPicker.style.width = '245px';
        colorPicker.style.zoom = '1';
        //colorPicker.innerHTML = '<div class="pu-inner"><div class="pu-body"> <a class="pu-close" href="#null" onclick="hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');"><span></span></a><h1>Pick a color</h1><div class="clear"><span/></div><div class="pu-content"><ul class="znbTheme-colorlist" id="znbTheme-colorlist' + id + '"></ul></div><div class="co-buttons-pannel"><div style=""><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="' + oldColorFunc + '; hideAKColorPicker(' + "'" +  colorPicker.id + "'" + ');">Cancel</a></div><div onmouseout="this.className = ' + "'co-button'" + '" onmouseover="this.className = ' + "'co-button co-btn-active'" + '" class="co-button"><a href="#null" onclick="hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');">OK</a></div></div></div><div class="clear"><span/></div></div></div>';
        
		_defColors = "000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,008000,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF";
		
		var i, h, cl;

		h = '<span class="mceMenuLine"></span>';
		cl = _defColors.split(',');

		h += '<table class="mceColors" id="znbTheme-colorlist' + id +'"><tr>';
		for (i=0; i<cl.length; i++) {
			
		   if (func.indexOf('#')>0)
		   {
			   _func = func.replace('#', '#'+cl[i]);
			  h += '<td><a href="#null" style="background-color: #' + cl[i] + ';" onclick="'+_func+';hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');"></a></td>';
		   }
		   else
		   {
			    _func = func+"('#"+cl[i]+"')";
			  h += '<td><a href="#null" style="background-color: #' + cl[i] + ';" onclick="'+_func+';hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');"></a></td>';
			   
			   //h += colorItems[i].innerHTML = '<td><a href="#null" style="background-color: #' + cl[i] + ';" onclick="'+func+'(' + "'#" + cl[i] + "'" + ');hideAKColorPicker(' + "'" + colorPicker.id + "'" + ');"></a></td>';   
		   }
	   
			if ((i+1) % 8 == 0)
				h += '</tr><tr>';
		}

		h += '</tr></table>';

		h += '<a href="javascript:void(0);" onclick="ee_mceColorPicker(\''+func.replace(/\'/ig,"\\'")+'\', \''+id+'\', \''+id.substr(0,id.length-3)+'\',\''+oldColor+'\');" class="mceMoreColors">More colors</a>';
//aaa=func.replace(/\'/ig,'\"');alert(aaa);
		colorPicker.innerHTML = h; 
		
		
		document.body.appendChild (colorPicker);
        if (typeof(WCH) != 'undefined'){WCH.Apply(colorPicker);}
    }
}



function ee_mceColorPicker(func, CPcoID, coID, oldColor){
				
					
					
					var template = [];
					var value = [];
	
					value['color'] = oldColor;
					
					if (func.indexOf('#')>0)
				    {
					   _func = func.replace('\'#\'', 'document.getElementById(\'color\').value');
					   tinyMCELang.lang_CPfunc = 'window.opener.'+_func+';window.opener.hideAKColorPicker(\'znbPicker' + CPcoID + '\');tinyMCEPopup.close();';
				    }
				    else
				    {
				 	   tinyMCELang.lang_CPfunc = 'window.opener.'+func+'(document.getElementById(\'color\').value);window.opener.hideAKColorPicker(\'znbPicker' + CPcoID + '\');tinyMCEPopup.close();';   
				    }
			   
				    tinyMCELang.lang_CPfunc2 = tinyMCELang.lang_CPfunc.replace(/\'/ig,"\\'");
					
					template['file'] = 'color_picker_theme.htm';
					template['width'] = 400;
					template['height'] = 280;
					template['close_previous'] = "no";

					template['width'] += tinyMCE.getLang('lang_theme_zanby_colorpicker_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_theme_zanby_colorpicker_delta_height', 0);

					value['store_selection'] = true;

					
					//tinyMCELang.lang_CSS_URL = tinyMCE.CSS_URL;
					//tinyMCELang.lang_JS_URL = tinyMCE.JS_URL;
					
					showAdvancedColorPicker(template, {input_color : value['color']});
					
			return true;
			}



function showAdvancedColorPicker(template, args){


var html, width, height, x, y, resizable, scrollbars, url, name, win, modal, features;

		

		args = !args ? {} : args;

		args.mce_template_file = template.file;
		args.mce_width = template.width;
		args.mce_height = template.height;
		tinyMCE.windowArgs = args;

		html = template.html;
		if (!(width = parseInt(template.width)))
			width = 320;

		if (!(height = parseInt(template.height)))
			height = 200;

		// Add to height in M$ due to SP2 WHY DON'T YOU GUYS IMPLEMENT innerWidth of windows!!
		if (tinyMCE.isIE)
			height += 40;
		else {
		    //MSQ Bug #259 
            if (navigator.userAgent.toUpperCase().indexOf("FIREFOX/3.") != -1) {
                height += 60;
            } else {
                height += 20;
            }
            //MSQ			
        }

		x = parseInt(screen.width / 2.0) - (width / 2.0);
		y = parseInt(screen.height / 2.0) - (height / 2.0);

		resizable = (args && args.resizable) ? args.resizable : "no";
		scrollbars = (args && args.scrollbars) ? args.scrollbars : "no";

		if (template.file.charAt(0) != '/' && template.file.indexOf('://') == -1)
			//MSQ bug whith color picker when 2 or more instances (include headline)
			//url = tinyMCE.baseURL + "/themes/" + tinyMCE.getParam("theme") + "/" + template.file;
			url = tinyMCE.baseURL + "/themes/zanby/" + template.file;
		else
			url = template.file;

		//MSQ01042008
	//	var _idx = url.indexOf('/themes/') ;
//		if (_idx>0){
//			tinyMCELang.version_url=url.substr(0,_idx);
//		} else {tinyMCELang.version_url=''}
		

		
		
		//alert(url);
		// Replace all args as variables in URL
		for (name in args) {
			if (typeof(args[name]) == 'function')
				continue;

			url = tinyMCE.replaceVar(url, name, escape(args[name]));
		}

		if (html) {
			html = tinyMCE.replaceVar(html, "css", this.settings.popups_css);
			//MSQ
			//alert(version_url);
			//html = tinyMCE.replaceVar(html, "version_url", version_url);
			
			
			html = tinyMCE.applyTemplate(html, args); //alert (html.indexOf('{$version_url}')) ;
			//html.replace(/{$version_url}/gi, version_url);

			win = window.open("", "mcePopup" + new Date().getTime(), "top=" + y + ",left=" + x + ",scrollbars=" + scrollbars + ",dialog=yes,minimizable=" + resizable + ",modal=yes,width=" + width + ",height=" + height + ",resizable=" + resizable);
			if (win == null) {
				alert(tinyMCELang.lang_popup_blocked);
				return;
			}

			win.document.write(html);
			win.document.close();
			win.resizeTo(width, height);
			win.focus();
		} else {
			if ((tinyMCE.isRealIE) && resizable != 'yes' && tinyMCE.settings.dialog_type == "modal") {
				height += 10;

				features = "resizable:" + resizable + ";scroll:" + scrollbars + ";status:yes;center:yes;help:no;dialogWidth:" + width + "px;dialogHeight:" + height + "px;";

				window.showModalDialog(url, window, features);
			} else {
				modal = (resizable == "yes") ? "no" : "yes";

				if (tinyMCE.isGecko && tinyMCE.isMac)
					modal = "no";

				if (template.close_previous != "no")
					try {tinyMCE.lastWindow.close();} catch (ex) {}

				win = window.open(url, "mcePopup" + new Date().getTime(), "top=" + y + ",left=" + x + ",scrollbars=" + scrollbars + ",dialog=" + modal + ",minimizable=" + resizable + ",modal=" + modal + ",width=" + width + ",height=" + height + ",resizable=" + resizable);
				
				
				
				if (win == null) {
					alert(tinyMCELang.lang_popup_blocked);
					return;
				}

				if (template.close_previous != "no")
					tinyMCE.lastWindow = win;

				try {
					win.resizeTo(width, height);
				} catch(e) {
					// Ignore
				}

				// Make it bigger if statusbar is forced
				if (tinyMCE.isGecko) {
					if (win.document.defaultView.statusbar.visible)
						win.resizeBy(0, tinyMCE.isMac ? 10 : 24);
				}

				win.focus();
			}
		}

}




//------------------------------------------------------------------------------------------------------
function hideAKColorPicker(id)
{
    if (document.getElementById(id)){
        obj = document.getElementById(id); document.body.removeChild(obj);
        if (typeof(WCH) != 'undefined'){WCH.Discard(obj);}
    }
}
//------------------------------------------------------------------------------------------------------
function createAKCPColorList(parrentObj, tagName, oldColor, func)
{
	
	var colors = new Array(
		"#ffffff","#ffcccc","#ffcc99","#ffff99","#ffffcc","#99ff99","#99ffff","#ccffff","#ccccff","#ffccff","#cccccc","#ff6666","#ff9966","#ffff66","#ffff33","#66ff99","#33ffff","#66ffff","#9999ff","#ff99ff","#c0c0c0","#ff0000","#ff9900","#ffcc66","#ffff00","#33ff33","#66cccc","#33ccff","#6666cc","#cc66cc","#999999","#cc0000","#ff6600","#ffcc33","#ffcc00","#33cc00","#00cccc","#3366ff","#6633ff","#cc33cc","#666666","#990000","#cc6600","#cc9933","#999900","#009900","#339999","#3333ff","#6600cc","#993399","#333333","#660000","#993300","#996633","#666600","#006600","#336666","#000099","#004488","#663366","#000000","#330000","#663300","#663333","#333300","#003300","#003333","#000066","#330099","#330033"
	); 
	
 //   var _thinput = document.getElementById(inputID);
    var colorItems = Array(colors.length);
//    var _thcolor = '';
    for (var i = 0; i < colors.length; i++){
        colorItems[i] = document.createElement(tagName);
       // colorItems[i].className = 'themeColor' + (i + 1);
        parrentObj.appendChild (colorItems[i]);
      //  _thcolor = getStyle(colorItems[i], 'color');
       // colorItems[i].innerHTML = '<a href="#null" style="background-color: ' + colors[i] + ';" onclick="document.getElementById(' + "'" + inputID + "'" + ').value=' + "'" + (i + 1) + "'; applyBackgroundColor();" + '"></a>';
	   if (func.indexOf('#')>0)
	   {
		   _func = func.replace('#', colors[i]);
		  colorItems[i].innerHTML = '<a href="#null" style="background-color: ' + colors[i] + ';" onclick="'+_func+';"></a>';
	   }
	   else
	   {
	       colorItems[i].innerHTML = '<a href="#null" style="background-color: ' + colors[i] + ';" onclick="'+func+'(' + "'" + colors[i] + "'" + ');"></a>';   
	   }
	    
    }
}
//------------------------------------------------------------------------------------------------------