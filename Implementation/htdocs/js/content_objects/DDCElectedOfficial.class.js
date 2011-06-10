DDCElectedOfficial = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCElectedOfficial, DDC);

DDCElectedOfficial.prototype.applyEditMode = function() {
    this.globalChangesExists = false;
    //xajax_update_headline_description('description', this.headlineText, this.ID);
    return;
};
DDCElectedOfficial.prototype.getParams = function () {
    var item = this.getGlobalParams();
    return item;
};
