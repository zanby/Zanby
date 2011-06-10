	function storeGBGI(elementId, avatar)
	{
		tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		tmpElement.avatarId = avatar;
		tmpElement.loadGBGIInEditMode();
	}
	
	function selectGBGI(elementId)
	{
		tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		if (tmpElement.avatarId) {
			xajax_select_gbgi(elementId, "", tmpElement.avatarId);
		} else {
			xajax_select_gbgi(elementId, "");
		}
		
	}

    DDCGroupFamilyIcons = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCGroupFamilyIcons, DDC);

    DDCGroupFamilyIcons.prototype.getParams = function () {

		var item = this.getGlobalParams();
		
		item["Data"]['avatarId']        = this.avatarId;
    		
	    return item;
    };

	//--------------------------------------------------------------------------------------------
	DDCGroupFamilyIcons.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckAvatarId = this.avatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupFamilyIcons.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.avatarId = this.bckAvatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupFamilyIcons.prototype.loadGBGIInEditMode = function () {
		//tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		xajax_get_block_content_light(this.targetID,this.ID,'light_'+this.ID,this.contentType,this.editMode,this.getParams());
					
		//xajax_load_bgi_in_edit_mode(this.ID, this.avatarId);
	};
