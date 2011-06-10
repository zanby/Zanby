function storeDDFamilyVideo(elementId, avatar)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.videoId = avatar;
	WarecorpDDblockApp.redrawElement(tmpElement.ID);
}

    DDCFamilyVideoContentBlock = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };
    YAHOO.extend(DDCFamilyVideoContentBlock, DDC);

    DDCFamilyVideoContentBlock.prototype.getParams = function () {

		var item = this.getGlobalParams();
  
    	item["Data"]["Content"] = this.innerText;
		item["Data"]["videoId"] = this.videoId;

        return item;
    };
	
	
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.setEditMode = function(){
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.resetEditMode = function(){
//		document.getElementById('tinyMCE_'+this.ID+'_div').style.display = "none";
//    	document.getElementById('tinyMCE_'+this.ID+'_div_buttons').style.display = "none";
//		document.getElementById('tinyMCE_'+this.ID+'_div_wait').style.display = "block";
//		tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
//		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');		
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.cancelEditMode = function(){
//		tinyMCEDeinit('tinyMCE_'+this.ID);
//		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.applyEditMode = function(){
//		tinyMCEDeinit('tinyMCE_'+this.ID);
//		this.innerText = document.getElementById('tinyMCE_'+this.ID).value;
//		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
//		this.headline = document.getElementById('tinyMCE_'+this.ID+'_H').value;
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckText = this.innerText; 
		this.bckVideoId = this.videoId; 
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyVideoContentBlock.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		if (this.bckText)
		{
			this.innerText = this.bckText;
			this.videoId = this.bckVideoId;
		}
	};
