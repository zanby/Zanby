function storeDDImageAvatar(elementId, avatar)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.avatarId = avatar;
	WarecorpDDblockApp.redrawElementLight(tmpElement.ID);
}

DDCImage = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};

YAHOO.extend(DDCImage, DDC);

DDCImage.prototype.getParams = function () {

	var item = this.getGlobalParams();
	
	item["Data"]['avatarId']        = this.avatarId;
  
	return item;
};

//--------------------------------------------------------------------------------------------
DDCImage.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckAvatarId = this.avatarId;
};
//--------------------------------------------------------------------------------------------
DDCImage.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.avatarId = this.bckAvatarId;
};
//--------------------------------------------------------------------------------------------
