//-------------------------------------------------------
// Font Family
//-------------------------------------------------------
function ddFastHeadline_change_font_family(elementId, value)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.fontFamily = value;
	
	if (value == 0) {
		document.getElementById('ddFastHeadline_'+elementId).style.fontFamily = "";
	} else {
		document.getElementById('ddFastHeadline_'+elementId).style.fontFamily = tmpElement.fontFamily;
	}
}
//-------------------------------------------------------
// Font Size
//-------------------------------------------------------
function ddFastHeadline_change_font_size(elementId, value)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.fontSize = value;
	
	if (value == 0) {
		document.getElementById('ddFastHeadline_'+elementId).style.fontSize = "";
	} else {
		document.getElementById('ddFastHeadline_'+elementId).style.fontSize = tmpElement.fontSize+'px';
	}
}
//-------------------------------------------------------
// Font Weight Bold
//-------------------------------------------------------
function ddFastHeadline_change_weight_bold(elementId)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		
	if (tmpElement.fontWeightBold == 0) {
		tmpElement.fontWeightBold = 1;
		document.getElementById('ddFastHeadline_'+elementId).style.fontWeight = "bold";
	} else {
		tmpElement.fontWeightBold = 0;
		document.getElementById('ddFastHeadline_'+elementId).style.fontWeight = "";
	}
}
//-------------------------------------------------------
// Font Style Italic
//-------------------------------------------------------
function ddFastHeadline_change_style_italic(elementId)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		
	if (tmpElement.fontStyleItalic == 0) {
		tmpElement.fontStyleItalic = 1;
		document.getElementById('ddFastHeadline_'+elementId).style.fontStyle = "italic";
	} else {
		tmpElement.fontStyleItalic = 0;
		document.getElementById('ddFastHeadline_'+elementId).style.fontStyle = "";
	}
}
//-------------------------------------------------------
// Text Decoration Underline
//-------------------------------------------------------
function ddFastHeadline_change_decoration_underline(elementId)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		
	if (tmpElement.textDecorationUnderline == 0) {
		tmpElement.textDecorationUnderline = 1;
		document.getElementById('ddFastHeadline_'+elementId).style.textDecoration = "underline";
	} else {
		tmpElement.textDecorationUnderline = 0;
		document.getElementById('ddFastHeadline_'+elementId).style.textDecoration = "";
	}
}
//-------------------------------------------------------
// Text Align
//-------------------------------------------------------
function ddFastHeadline_change_text_align(elementId, value)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.textAlign = value;
	
	document.getElementById('ddFastHeadline_'+elementId).style.textAlign = tmpElement.textAlign;
}
//-------------------------------------------------------
// Color
//-------------------------------------------------------
function ddFastHeadline_change_color(elementId, value)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.color = value;
	
	document.getElementById('ddFastHeadline_'+elementId).style.color = tmpElement.color;
	if(document.getElementById(elementId+'CP3_indicator')){
		document.getElementById(elementId+'CP3_indicator').style.backgroundColor=tmpElement.color;
	}
}
//-------------------------------------------------------
// Clear formatting
//-------------------------------------------------------
function ddFastHeadline_clear_formatting(elementId)
{
	var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.clearFormatting();
	
	document.getElementById('ddFastHeadline_'+elementId).style.fontFamily = "";
	document.getElementById('ddFastHeadline_'+elementId).style.fontSize = "";
	document.getElementById('ddFastHeadline_'+elementId).style.fontWeight = "";
	document.getElementById('ddFastHeadline_'+elementId).style.fontStyle = "";
	document.getElementById('ddFastHeadline_'+elementId).style.textDecoration = "";
	document.getElementById('ddFastHeadline_'+elementId).style.textAlign = "";
	document.getElementById('ddFastHeadline_'+elementId).style.color = "";
}
//-------------------------------------------------------




DDCFastHeadline = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCFastHeadline, DDC);

DDCFastHeadline.prototype.getParams = function () {

    var item = this.getGlobalParams();
   
    item.Data.font_family = this.fontFamily;
    item.Data.font_size = this.fontSize;
	item.Data.font_weight_bold = this.fontWeightBold;
	item.Data.font_style_italic = this.fontStyleItalic;
	item.Data.text_decoration_underline = this.textDecorationUnderline;
	item.Data.text_align = this.textAlign;
    item.Data.color = this.color;
    if (this.innerText) {
        item.Data.Content = this.innerText;
    }

    return item;
};

DDCFastHeadline.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckFontFamily = this.fontFamily;
	this.bckFontSize = this.fontSize;
	this.bckFontWeightBold = this.fontWeightBold;
	this.bckFontStyleItalic = this.fontStyleItalic;
	this.bckTextDecorationUnderline = this.textDecorationUnderline;
	this.bckTextAlign = this.textAlign;	
	this.bckColor = this.color;	
	
	return true;
};

DDCFastHeadline.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.fontFamily = this.bckFontFamily;
	this.fontSize = this.bckFontSize;
	this.fontWeightBold = this.bckFontWeightBold;
	this.fontStyleItalic = this.bckFontStyleItalic;
	this.textDecorationUnderline = this.bckTextDecorationUnderline;
	this.textAlign = this.bckTextAlign;	
	this.color = this.bckColor;
	
	return true;
};

DDCFastHeadline.prototype.clearFormatting = function () {
	
	this.fontFamily = '';
	this.fontSize = '';
	this.fontWeightBold = 0;
	this.fontStyleItalic = 0;
	this.textDecorationUnderline = 0;
	this.textAlign = '';	
	this.color = '';
	
	return true;
};

//--------------------------------------------------------------------------------------------
	DDCFastHeadline.prototype.setEditMode = function(){
		return;
	};
	DDCFastHeadline.prototype.resetEditMode = function(){
		return;
	};
	DDCFastHeadline.prototype.cancelEditMode = function(){
		return;
	};
	DDCFastHeadline.prototype.applyEditMode = function(){
		return;
	};
//--------------------------------------------------------------------------------------------
