DDCHeadline = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCHeadline, DDC);

DDCHeadline.prototype.getParams = function () {

   var item = this.getGlobalParams();
   
   return item;
};

DDCHeadline.prototype.backupParams = function () {
	this.backupGlobalParams();
	return true;
};

DDCHeadline.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	return true;
};