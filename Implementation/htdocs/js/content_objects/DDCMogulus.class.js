//-------------------------------------------------------
function changeMogulusChannel(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.channel = value;
}
//-------------------------------------------------------
function mogulus_start_on_init_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.startOnInit = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
DDCMogulus = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCMogulus, DDC);

DDCMogulus.prototype.getParams = function () {

   var item = this.getGlobalParams();
   item['enabled'] = 1;
   item["Data"]["channel"] = this.channel;
   item["Data"]["startOnInit"] = this.startOnInit
   return item;
};

DDCMogulus.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckChannel = this.channel;
	this.bckStartOnInit = this.startOnInit;
	return true;
};

DDCMogulus.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.channel = this.bckChannel;
	this.startOnInit = this.bckStartOnInit;
	return true;
};

//--------------------------------------------------------------------------------------------
	DDCMogulus.prototype.setEditMode = function(){
		return;
	}
	DDCMogulus.prototype.resetEditMode = function(){
		return;
	}
	DDCMogulus.prototype.cancelEditMode = function(){
		return;
	}
	DDCMogulus.prototype.applyEditMode = function(){
		return;
	}
//--------------------------------------------------------------------------------------------