/**
 * $Id: editor_template_src.js 162 2007-01-03 16:16:52Z spocke $
 *
 * @author Moxiecode
 * @author Komarovski	
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */
tinyMCE.importThemeLanguagePack('simple');

var TinyMCE_SimpleTheme = {
	_defColors : "000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,008000,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF",
	// List of button ids in tile map
	_buttonMap : 'forecolor,bold,bullist,cleanup,italic,justifycenter,justifyfull,justifyleft,justifyright,numlist,redo,strikethrough,underline,undo',

	getEditorTemplate : function(settings, editorId) {
		var html = '';

		html += '<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}">';
		
		html += '<tr><td class="mceToolbar" align="center" height="1"><div>';
		
		
		html += '<div class="nodragndrop" style="float:left; margin-left:3px;"><select id="{$editor_id}_fontNameSelect" name="{$editor_id}_fontNameSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontName\',false,this.options[this.selectedIndex].value);" class="mceSelectList" style="width:70px;"><option value="">{$lang_theme_fontdefault}</option>';
		
		var iFonts = 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;WingDings=wingdings';
		var nFonts = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
				var fonts = tinyMCE.getParam("theme_simple_fonts", nFonts).split(';');
				for (i=0; i<fonts.length; i++) {
					if (fonts[i] != '') {
						var parts = fonts[i].split('=');
						html += '<option value="' + parts[1] + '">' + parts[0] + '</option>';
					}
				}
				html += '</select></div>';
				
		html += '<div class="nodragndrop" style="float:left;"><select id="{$editor_id}_fontSizeSelect" name="{$editor_id}_fontSizeSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontSize\',false,this.options[this.selectedIndex].value);" class="mceSelectList" style="width:50px;">'+
						'<option value="0">{$lang_theme_font_size}</option>'+
						'<option value="1">8</option>'+
						'<option value="2">10</option>'+
						'<option value="3">12</option>'+
						'<option value="4">14</option>'+
						'<option value="5">18</option>'+
						'<option value="6">24</option>'+
						'<option value="7">36</option>'+
						'</select></div>';
		html += '<div style="float:left;"><img src="'+AppTheme.images+'/decorators/separator.gif" width="2" height="20" class="mceSeparatorLine" /></div>';	
		
		html += tinyMCE.getMenuButtonHTML('forecolor', 'lang_theme_forecolor_desc', '{$themeurl}/images/forecolor.gif', "forecolorMenu", "forecolor",true ,null);
		
		html += '<div style="float:left;"><img src="'+AppTheme.images+'/decorators/separator.gif" width="2" height="20" class="mceSeparatorLine" /></div>';	
		html += tinyMCE.getButtonHTML('bold', 'lang_bold_desc', '{$themeurl}/images/{$lang_bold_img}', 'Bold');
		html += tinyMCE.getButtonHTML('italic', 'lang_italic_desc', '{$themeurl}/images/{$lang_italic_img}', 'Italic');
		html += tinyMCE.getButtonHTML('underline', 'lang_underline_desc', '{$themeurl}/images/{$lang_underline_img}', 'Underline');
		html += tinyMCE.getButtonHTML('strikethrough', 'lang_striketrough_desc', '{$themeurl}/images/strikethrough.gif', 'Strikethrough');
		
		html += '<div style="float:left;"><img src="'+AppTheme.images+'/decorators/separator.gif" width="2" height="20" class="mceSeparatorLine" /></div>';
		html += tinyMCE.getButtonHTML('justifyleft', 'lang_justifyleft_desc', '{$themeurl}/images/justifyleft.gif', 'JustifyLeft');
		html += tinyMCE.getButtonHTML('justifycenter', 'lang_justifycenter_desc', '{$themeurl}/images/justifycenter.gif', 'JustifyCenter');
		html += tinyMCE.getButtonHTML('justifyright', 'lang_justifyright_desc', '{$themeurl}/images/justifyright.gif', 'JustifyRight');
		html += tinyMCE.getButtonHTML('justifyfull', 'lang_justifyfull_desc', '{$themeurl}/images/justifyfull.gif', 'JustifyFull');
		
		html += '<div style="float:left;"><img src="'+AppTheme.images+'/decorators/separator.gif" width="2" height="20" class="mceSeparatorLine" /></div>';
		html += tinyMCE.getButtonHTML('undo', 'lang_undo_desc', '{$themeurl}/images/undo.gif', 'Undo');
		html += tinyMCE.getButtonHTML('redo', 'lang_redo_desc', '{$themeurl}/images/redo.gif', 'Redo');
		
		html += '<div style="float:left;"><img src="'+AppTheme.images+'/decorators/separator.gif" width="2" height="20" class="mceSeparatorLine" /></div>';
		html += tinyMCE.getButtonHTML('cleanup', 'lang_cleanup_desc', '{$themeurl}/images/cleanup.gif', 'mceCleanup');
		
		html += '</div></td></tr>';
		html += '<tr><td align="center">';
		html += '<span id="{$editor_id}">IFRAME</span>';
		html += '</td></tr></table>';

		return {
			delta_width : 0,
			delta_height : 20,
			html : html
		};
	},

	handleNodeChange : function(editor_id, node) {
		// Reset old states
		tinyMCE.switchClass(editor_id + '_bold', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_italic', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_underline', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_strikethrough', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonNormal');

		// Handle elements
		do {
			switch (node.nodeName.toLowerCase()) {
				case "b":
				case "strong":
					tinyMCE.switchClass(editor_id + '_bold', 'mceButtonSelected');
				break;

				case "i":
				case "em":
					tinyMCE.switchClass(editor_id + '_italic', 'mceButtonSelected');
				break;

				case "u":
					tinyMCE.switchClass(editor_id + '_underline', 'mceButtonSelected');
				break;

				case "strike":
					tinyMCE.switchClass(editor_id + '_strikethrough', 'mceButtonSelected');
				break;
				
				case "ul":
					tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonSelected');
				break;

				case "ol":
					tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonSelected');
				break;
			}
		} while ((node = node.parentNode) != null);
	},
	
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			
			case "forecolor":
				var fcp = new TinyMCE_Layer(editor_id + '_fcPreview', false), p, img, elm;

				TinyMCE_SimpleTheme._hideMenus(editor_id);

				if (!fcp.exists()) {
					fcp.create('div', 'mceColorPreview', document.getElementById(editor_id + '_toolbar'));
					elm = fcp.getElement();
					elm._editor_id = editor_id;
					elm._command = "forecolor";
					elm._switchId = editor_id + "_forecolor";
					tinyMCE.addEvent(elm, 'click', TinyMCE_SimpleTheme._handleMenuEvent);
					tinyMCE.addEvent(elm, 'mouseover', TinyMCE_SimpleTheme._handleMenuEvent);
					tinyMCE.addEvent(elm, 'mouseout', TinyMCE_SimpleTheme._handleMenuEvent);
				}

				img = tinyMCE.selectNodes(document.getElementById(editor_id + "_forecolor"), function(n) {return n.nodeName == "IMG";})[0];
				p = tinyMCE.getAbsPosition(img, document.getElementById(editor_id + '_toolbar'));

				fcp.moveTo(p.absLeft, p.absTop);
				fcp.getElement().style.backgroundColor = value != null ? value : tinyMCE.getInstanceById(editor_id).foreColor;
				fcp.show();

				return false;

			case "forecolorpicker":
				this._pickColor(editor_id, 'forecolor');
				return true;

			case "forecolorMenu":
				TinyMCE_SimpleTheme._hideMenus(editor_id);

				// Create color layer
				var ml = new TinyMCE_Layer(editor_id + '_fcMenu');

				if (!ml.exists())
					ml.create('div', 'mceMenu', document.body, TinyMCE_SimpleTheme._getColorHTML(editor_id, 'theme_simple_text_colors', 'forecolor'));

				tinyMCE.switchClass(editor_id + '_forecolor', 'mceMenuButtonFocus');
				ml.moveRelativeTo(document.getElementById(editor_id + "_forecolor"), 'bl');

				ml.moveBy(tinyMCE.isMSIE && !tinyMCE.isOpera ? -1 : 1, -1);

				if (tinyMCE.isOpera)
					ml.moveBy(0, -2);

				ml.show();
			return true;

			case "HiliteColor":
				var bcp = new TinyMCE_Layer(editor_id + '_bcPreview', false), p, img;

				TinyMCE_SimpleTheme._hideMenus(editor_id);

				if (!bcp.exists()) {
					bcp.create('div', 'mceColorPreview', document.getElementById(editor_id + '_toolbar'));
					elm = bcp.getElement();
					elm._editor_id = editor_id;
					elm._command = "HiliteColor";
					elm._switchId = editor_id + "_backcolor";
					tinyMCE.addEvent(elm, 'click', TinyMCE_SimpleTheme._handleMenuEvent);
					tinyMCE.addEvent(elm, 'mouseover', TinyMCE_SimpleTheme._handleMenuEvent);
					tinyMCE.addEvent(elm, 'mouseout', TinyMCE_SimpleTheme._handleMenuEvent);
				}

				img = tinyMCE.selectNodes(document.getElementById(editor_id + "_backcolor"), function(n) {return n.nodeName == "IMG";})[0];
				p = tinyMCE.getAbsPosition(img, document.getElementById(editor_id + '_toolbar'));

				bcp.moveTo(p.absLeft, p.absTop);
				bcp.getElement().style.backgroundColor = value != null ? value : tinyMCE.getInstanceById(editor_id).backColor;
				bcp.show();

				return false;

			case "HiliteColorMenu":
				TinyMCE_SimpleTheme._hideMenus(editor_id);

				// Create color layer
				var ml = new TinyMCE_Layer(editor_id + '_bcMenu');

				if (!ml.exists())
					ml.create('div', 'mceMenu', document.body, TinyMCE_SimpleTheme._getColorHTML(editor_id, 'theme_simple_background_colors', 'HiliteColor'));

				tinyMCE.switchClass(editor_id + '_backcolor', 'mceMenuButtonFocus');
				ml.moveRelativeTo(document.getElementById(editor_id + "_backcolor"), 'bl');

				ml.moveBy(tinyMCE.isMSIE && !tinyMCE.isOpera ? -1 : 1, -1);

				if (tinyMCE.isOpera)
					ml.moveBy(0, -2);

				ml.show();
			return true;
	
			case "mceColorPicker":
				if (user_interface) {
					var template = [];
	
					if (!value['callback'] && !value['color'])
						value['color'] = value['document'].getElementById(value['element_id']).value;

					template['file'] = 'color_picker.htm';
					template['width'] = 380;
					template['height'] = 250;
					template['close_previous'] = "no";

					template['width'] += tinyMCE.getLang('lang_theme_simple_colorpicker_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_theme_simple_colorpicker_delta_height', 0);

					if (typeof(value['store_selection']) == "undefined")
						value['store_selection'] = true;

					tinyMCE.lastColorPickerValue = value;
					tinyMCE.openWindow(template, {editor_id : editor_id, mce_store_selection : value['store_selection'], inline : "yes", command : "mceColorPicker", input_color : value['color']});
				} else {
					var savedVal = tinyMCE.lastColorPickerValue, elm;

					if (savedVal['callback']) {
						savedVal['callback'](value);
						return true;
					}

					elm = savedVal['document'].getElementById(savedVal['element_id']);
					elm.value = value;

					if (elm.onchange != null && elm.onchange != '')
						eval('elm.onchange();');
				}
			return true;
		}
		return false;
	},
	
	
	_pickColor : function(id, cm) {
		var inputColor, inst = tinyMCE.selectedInstance;

		if (cm == 'forecolor' && inst)
			inputColor = inst.foreColor;

		if ((cm == 'backcolor' || cm == 'HiliteColor') && inst)
			inputColor = inst.backColor;

		tinyMCE.execCommand('mceColorPicker', true, {color : inputColor, callback : function(c) {
			tinyMCE.execInstanceCommand(id, cm, false, c);
		}});
	},
	
	_getColorHTML : function(id, n, cm) {
		var i, h, cl;

		h = '<span class="mceMenuLine"></span>';
		cl = tinyMCE.getParam(n, TinyMCE_SimpleTheme._defColors).split(',');

		h += '<table class="mceColors"><tr>';
		for (i=0; i<cl.length; i++) {
			c = 'tinyMCE.execInstanceCommand(\'' + id + '\', \'' + cm + '\', false, \'#' + cl[i] + '\');';
			h += '<td><a href="javascript:' + c + '" style="background-color: #' + cl[i] + '" onclick="' + c + ';return false;"></a></td>';

			if ((i+1) % 8 == 0)
				h += '</tr><tr>';
		}

		h += '</tr></table>';

		if (tinyMCE.getParam("theme_simple_more_colors", true))
			h += '<a href="javascript:void(0);" onclick="TinyMCE_SimpleTheme._pickColor(\'' + id + '\',\'' + cm + '\');" class="mceMoreColors">' + tinyMCE.getLang('lang_more_colors') + '</a>';

		return h;
	},

	_hideMenus : function(id) {
		var fcml = new TinyMCE_Layer(id + '_fcMenu'), bcml = new TinyMCE_Layer(id + '_bcMenu');

		if (fcml.exists() && fcml.isVisible()) {
			tinyMCE.switchClass(id + '_forecolor', 'mceMenuButton');
			fcml.hide();
		}

		if (bcml.exists() && bcml.isVisible()) {
			tinyMCE.switchClass(id + '_backcolor', 'mceMenuButton');
			bcml.hide();
		}
	},
	
	
		initInstance : function(inst) {
		if (tinyMCE.getParam("theme_simple_resizing", false)) {
			if (tinyMCE.getParam("theme_simple_resizing_use_cookie", true)) {
				var w = TinyMCE_SimpleTheme._getCookie("TinyMCE_" + inst.editorId + "_width");
				var h = TinyMCE_SimpleTheme._getCookie("TinyMCE_" + inst.editorId + "_height");

				TinyMCE_SimpleTheme._resizeTo(inst, w, h, tinyMCE.getParam("theme_simple_resize_horizontal", true));
			}
		}

		inst.addShortcut('ctrl', 'k', 'lang_link_desc', 'mceLink');
	},

	removeInstance : function(inst) {
		new TinyMCE_Layer(inst.editorId + '_fcMenu').remove();
		new TinyMCE_Layer(inst.editorId + '_bcMenu').remove();
	},

	hideInstance : function(inst) {
		TinyMCE_SimpleTheme._hideMenus(inst.editorId);
	},

	_handleMenuEvent : function(e) {
		var te = tinyMCE.isMSIE ? window.event.srcElement : e.target;
		tinyMCE._menuButtonEvent(e.type == "mouseover" ? "over" : "out", document.getElementById(te._switchId));

		if (e.type == "click")
			tinyMCE.execInstanceCommand(te._editor_id, te._command);
	}

	
};

tinyMCE.addTheme("simple", TinyMCE_SimpleTheme);
tinyMCE.addButtonMap(TinyMCE_SimpleTheme._buttonMap);
