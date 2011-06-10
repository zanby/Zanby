DDCRoundEvents = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};

YAHOO.extend(DDCRoundEvents, DDC);

DDCRoundEvents.prototype.getParams = function () {
	return this.getGlobalParams();
};

//--------------------------------------------------------------------------------------------
DDCRoundEvents.prototype.backupParams = function () {
	this.backupGlobalParams();
};
//--------------------------------------------------------------------------------------------
DDCRoundEvents.prototype.restoreParams = function () {
	this.restoreGlobalParams();
};
//--------------------------------------------------------------------------------------------
