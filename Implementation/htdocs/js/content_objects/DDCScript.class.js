function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do { curDate = new Date(); }
while(curDate-date < millis);
}

//-------------------------------------------------------
function changeScriptAltSrc(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.altSrc = value;
}
//-------------------------------------------------------
function changeScriptCustomHeight(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.customHeight = value;
}
//-------------------------------------------------------
DDCScript = function(id, sGroup, config) {
	if (id) {
		this.init(id, sGroup, config);
	}
};
YAHOO.extend(DDCScript, DDC);

DDCScript.prototype.getParams = function (jscontent) {

   var item = this.getGlobalParams();
   item['enabled'] = 1;
   item["Data"]["alt_src"] = this.altSrc;
   item["Data"]["unique_code"] = this.uniqueCode;
   item["Data"]["custom_height"] = this.customHeight;
   if (jscontent == 'jscontent') { item["Data"]["jscontent"]  = 1 };
   return item;
};

DDCScript.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckAltSrc = this.altSrc;
	this.bckCustomHeight = this.customHeight;
	return true;
};

DDCScript.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.altSrc = this.bckAltSrc;
	this.customHeight = this.bckCustomHeight;
	return true;
};

//--------------------------------------------------------------------------------------------
	DDCScript.prototype.saveScriptCode = function(){
		this.altSrc = document.getElementById('script_src_'+this.ID).value;
		xajax_ddScript_save_script_code(this.uniqueCode, this.altSrc, this.customHeight, this.ID);
		return;
	}
	DDCScript.prototype.setEditMode = function(){
		return;
	}
	DDCScript.prototype.resetEditMode = function(){
		return;
	}
	DDCScript.prototype.cancelEditMode = function(){
		return;
	}
	DDCScript.prototype.applyEditMode = function(){
		document.getElementById('cb-content-'+this.ID).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
		//xajax_ddScript_save_script_code(this.uniqueCode, this.altSrc, this.customHeight);
		//pausecomp(5000);
		this.altSrc = '';
		return;
	}
//--------------------------------------------------------------------------------------------