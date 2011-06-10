DDCRoundInfo = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};

YAHOO.extend(DDCRoundInfo, DDC);

DDCRoundInfo.prototype.getParams = function () {
	return this.getGlobalParams();
};

//--------------------------------------------------------------------------------------------
DDCRoundInfo.prototype.backupParams = function () {
	this.backupGlobalParams();
};
//--------------------------------------------------------------------------------------------
DDCRoundInfo.prototype.restoreParams = function () {
	this.restoreGlobalParams();
};
//--------------------------------------------------------------------------------------------
