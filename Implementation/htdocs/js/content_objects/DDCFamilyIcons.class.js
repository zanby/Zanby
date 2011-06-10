	function storeBGI(elementId, avatar)
	{
		tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		tmpElement.avatarId = avatar;
		tmpElement.loadBGIInEditMode();
	}

    DDCFamilyIcons = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCFamilyIcons, DDC);

    DDCFamilyIcons.prototype.getParams = function () {

		var item = this.getGlobalParams();
		
		item.Data.avatarId        = this.avatarId;

	    return item;
    };

	//--------------------------------------------------------------------------------------------
	DDCFamilyIcons.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckAvatarId = this.avatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyIcons.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.avatarId = this.bckAvatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyIcons.prototype.loadBGIInEditMode = function () {
		//tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		xajax_get_block_content_light(this.targetID,this.ID,'light_'+this.ID,this.contentType,this.editMode,this.getParams());
					
		//xajax_load_bgi_in_edit_mode(this.ID, this.avatarId);
	};
