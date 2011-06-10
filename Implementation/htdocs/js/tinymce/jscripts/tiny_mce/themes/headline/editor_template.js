function getMenuSHButtonHTML(id, lang, img, mcmd, cmd, ui, val) {
		var h = '', m, x;

		mcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'' + mcmd + '\');';
		cmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'' + cmd + '\'';

		if (typeof(ui) != "undefined" && ui != null)
			cmd += ',' + ui;

		if (typeof(val) != "undefined" && val != null)
			cmd += ",'" + val + "'";

		cmd += ');';

		// Use tilemaps when enabled and found and never in MSIE since it loads the tile each time from cache if cahce is disabled
		if (tinyMCE.getParam('button_tile_map') && (!tinyMCE.isIE || tinyMCE.isOpera) && (m = tinyMCE.buttonMap[id]) != null && (tinyMCE.getParam("language") == "en" || img.indexOf('$lang') == -1)) {
			x = 0 - (m * 20) == 0 ? '0' : 0 - (m * 20);

			if (tinyMCE.isRealIE)
				h += '<span id="{$editor_id}_' + id + '" class="mceMenuButton" onmouseover="tinyMCE._menuButtonEvent(\'over\',this);tinyMCE.lastHover = this;" onmouseout="tinyMCE._menuButtonEvent(\'out\',this);">';
			else
				h += '<span id="{$editor_id}_' + id + '" class="mceMenuButton">';

			h += '<a href="javascript:' + cmd + '" onclick="' + cmd + 'return false;" onmousedown="return false;" class="mceTiledButton mceMenuButtonNormal" target="_self">';
			h += '<img src="'+AppTheme.images+'/decorators/spacer.gif" style="width: 20px; height: 20px; background-position: ' + x + 'px 0" title="{$' + lang + '}" /></a>';
			h += '<a href="javascript:' + mcmd + '" onclick="' + mcmd + 'return false;" onmousedown="return false;"><img src="'+AppTheme.images+'/decorators/button_menu.gif" title="{$' + lang + '}" class="mceMenuButton" />';
			h += '</a></span>';
		} else {
			if (tinyMCE.isRealIE)
				h += '<span id="{$editor_id}_' + id + '" dir="ltr" class="mceMenuButton" onmouseover="tinyMCE._menuButtonEvent(\'over\',this);tinyMCE.lastHover = this;" onmouseout="tinyMCE._menuButtonEvent(\'out\',this);">';
			else
				h += '<span id="{$editor_id}_' + id + '" dir="ltr" class="mceMenuButton">';

			h += '<a href="javascript:' + cmd + '" onclick="' + cmd + 'return false;" onmousedown="return false;" class="mceMenuButtonNormal" target="_self">';
			h += '<img src="' + img + '" title="{$' + lang + '}" /></a>';
			h += '<a href="javascript:' + mcmd + '" onclick="' + mcmd + 'return false;" onmousedown="return false;"><img src="'+AppTheme.images+'/decorators/button_menu.gif" title="{$' + lang + '}" class="mceMenuButton" />';
			h += '</a></span>';
		}

		return h;
	}

function getMenuSHButtonHTML2(_editorid, id, lang, img, mcmd, cmd, ui, val) {
		var h = '', m, x;

		mcmd = 'tinyMCE.execInstanceCommand(\'mce_editor_z_'+_editorid+'\',\'' + mcmd + '\');';
		cmd = 'tinyMCE.execInstanceCommand(\'mce_editor_z_'+_editorid+'\',\'' + cmd + '\'';

		if (typeof(ui) != "undefined" && ui != null)
			cmd += ',' + ui;

		if (typeof(val) != "undefined" && val != null)
			cmd += ",'" + val + "'";

		cmd += ');';

		// Use tilemaps when enabled and found and never in MSIE since it loads the tile each time from cache if cahce is disabled
		if (tinyMCE.getParam('button_tile_map') && (!tinyMCE.isIE || tinyMCE.isOpera) && (m = tinyMCE.buttonMap[id]) != null && (tinyMCE.getParam("language") == "en" || img.indexOf('$lang') == -1)) {
			x = 0 - (m * 20) == 0 ? '0' : 0 - (m * 20);

			if (tinyMCE.isRealIE)
				h += '<span id="mce_editor_z_'+_editorid+'_' + id + '" class="mceMenuButton" onmouseover="tinyMCE._menuButtonEvent(\'over\',this);tinyMCE.lastHover = this;" onmouseout="tinyMCE._menuButtonEvent(\'out\',this);">';
			else
				h += '<span id="mce_editor_z_'+_editorid+'_' + id + '" class="mceMenuButton">';

			h += '<a href="javascript:' + cmd + '" onclick="' + cmd + 'return false;" onmousedown="return false;" class="mceTiledButton mceMenuButtonNormal" target="_self">';
			h += '<img src="'+AppTheme.images+'/decorators/spacer.gif" style="width: 20px; height: 20px; background-position: ' + x + 'px 0" title="{$' + lang + '}" /></a>';
			h += '<a href="javascript:' + mcmd + '" onclick="' + mcmd + 'return false;" onmousedown="return false;"><img src="'+AppTheme.images+'/decorators/button_menu.gif" title="{$' + lang + '}" class="mceMenuButton" />';
			h += '</a></span>';
		} else {
			if (tinyMCE.isRealIE)
				h += '<span id="mce_editor_z_'+_editorid+'_' + id + '" dir="ltr" class="mceMenuButton" onmouseover="tinyMCE._menuButtonEvent(\'over\',this);tinyMCE.lastHover = this;" onmouseout="tinyMCE._menuButtonEvent(\'out\',this);">';
			else
				h += '<span id="mce_editor_z_'+_editorid+'_' + id + '" dir="ltr" class="mceMenuButton">';

			h += '<a href="javascript:' + cmd + '" onclick="' + cmd + 'return false;" onmousedown="return false;" class="mceMenuButtonNormal" target="_self">';
			h += '<img src="' + img + '" title="{$' + lang + '}" /></a>';
			h += '<a href="javascript:' + mcmd + '" onclick="' + mcmd + 'return false;" onmousedown="return false;"><img src="'+AppTheme.images+'/decorators/button_menu.gif" title="{$' + lang + '}" class="mceMenuButton" />';
			h += '</a></span>';
		}

		return h;
	}
	
	
	

tinyMCE.importThemeLanguagePack('headline');

var TinyMCE_HeadlineTheme = {
	_defColors : "000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,008000,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF",
	_buttonMap : 'bold,italic,underline,justifyleft,justifycenter,justifyright,forecolor',

	getEditorTemplate : function(settings, editorId) {
		var html = '';

		html +='<ul class="prCO-editpanel-toolbox">';

		html += '<li class="prWithoutInnerLeft"><select id="{$editor_id}_fontNameSelect" name="{$editor_id}_fontNameSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontName\',false,this.options[this.selectedIndex].value);" style="width:60px;"><option value="">{$lang_theme_fontdefault}</option>';
		
		/*var iFonts = 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;WingDings=wingdings';
		var nFonts = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
		*/
		var iFonts = 'Times New Roman = times new roman, arial, sans-serif; Arial = helvetica, arial, sans-serif; Tahoma = tahoma, arial, sans-serif; Verdana = verdana, arial, sans-serif; Lucida Console = lucida console, arial';
				
		var nFonts = 'Times New Roman = times new roman, arial, sans-serif; Arial = helvetica, arial, sans-serif; Tahoma = tahoma, arial, sans-serif; Verdana = verdana, arial, sans-serif; Lucida Console = lucida console, arial';
				
				var fonts = tinyMCE.getParam("theme_headline_fonts", nFonts).split(';');
				for (i=0; i<fonts.length; i++) {
					if (fonts[i] != '') {
						var parts = fonts[i].split('=');
						html += '<option value="' + parts[1] + '">' + parts[0] + '</option>';
					}
				}
				html += '</select></li>';
				
		html += '<li><select id="{$editor_id}_fontSizeSelect" name="{$editor_id}_fontSizeSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontSize\',false,this.options[this.selectedIndex].value);" style="width:40px;">'+
						'<option value="3">{$lang_theme_font_size}</option>'+
						'<option value="1">8</option>'+
						'<option value="2">10</option>'+
						'<option value="3">12</option>'+
						'<option value="4">14</option>'+
						'<option value="5">18</option>'+
						'<option value="6">24</option>'+
						'<option value="7">36</option>'+
						'</select></li>';
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'Bold\');';
		//html += tinyMCE.getButtonHTML('bold', '{$lang_bold_img}', '/images/co-ep-bold.gif', tcmd, tcmd );// 
		html +='<li><a id="{$editor_id}_bold" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-bold.gif) no-repeat 50% center"></a></li>';
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'Italic\');';
		html += '<li><a id="{$editor_id}_italic" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonSelected" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-italic.gif) no-repeat 50% center"></a></li>';
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'Underline\');';
		html += '<li><a id="{$editor_id}_underline" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-underline.gif) no-repeat 50% center"></a></li>';
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'JustifyLeft\');';
		html += '<li><a id="{$editor_id}_justifyleft" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-left.gif) no-repeat 50% center"></a></li>';
		
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'JustifyCenter\');';
		html += '<li><a id="{$editor_id}_justifycenter" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-center.gif) no-repeat 50% center"></a></li>';
		

		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'JustifyRight\');';
		html += '<li><a id="{$editor_id}_justifyright" href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;" onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/co/co-ep-right.gif) no-repeat 50% center"></a></li>';
		
		html += '<div style="padding-top:3px; float:left;">'+getMenuSHButtonHTML('forecolor', 'lang_theme_forecolor_desc', ''+AppTheme.images+'/decorators/forecolor.gif', "forecolorMenu", 'forecolor', false, null)+'</div>';
		
		//html += '<div style="padding-top:3px; float:left;">'+getMenuSHButtonHTML('forecolor', 'lang_theme_forecolor_desc', ''+AppTheme.images+'/decorators/forecolor.gif', "forecolorMenu2", 'forecolor2', false, null)+'</div>';
		
		var tcmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'removeFormatMSQ\');';
		
		html += '<li style="width:2px; background:none;"><img src="'+AppTheme.images+'/decorators/separator.gif"/></li><li style="padding-left:0px; "><a href="javascript:' + tcmd + '" onclick="' + tcmd + 'return false;"  onmousedown="return false;" class="mceButtonNormal" target="_self" style="background:url('+AppTheme.images+'/decorators/removeformat.gif) no-repeat 50% center"></a></li>';
		
		html += '</ul>';
		html += '<span id="{$editor_id}">IFRAME</span>';
		
		html = '<span id="' + editorId + '_toolbar" class="mceToolbarContainer">' + html + '</span>';
		
		return {
			delta_width : 0,
			delta_height : 20,
			html : html
		};
	}
};

tinyMCE.addTheme("headline", TinyMCE_HeadlineTheme);
tinyMCE.addButtonMap(TinyMCE_HeadlineTheme._buttonMap);
