//-------------------------------------------------------
function changeIframeAltSrc(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.altSrc = value;
}
//-------------------------------------------------------
DDCIframe = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCIframe, DDC);

DDCIframe.prototype.getParams = function () {

   var item = this.getGlobalParams();
   item['enabled'] = 1;
   item["Data"]["alt_src"] = this.altSrc;
   return item;
};

DDCIframe.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckAltSrc = this.altSrc;
	return true;
};

DDCIframe.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.altSrc = this.bckAltSrc;
	return true;
};

//--------------------------------------------------------------------------------------------
	DDCIframe.prototype.setEditMode = function(){
		return;
	}
	DDCIframe.prototype.resetEditMode = function(){
		return;
	}
	DDCIframe.prototype.cancelEditMode = function(){
		return;
	}
	DDCIframe.prototype.applyEditMode = function(){
		return;
	}
//--------------------------------------------------------------------------------------------