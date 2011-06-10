	function storeAvatar(elementId, avatar)
	{
		tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		tmpElement.avatarId = avatar;
		tmpElement.loadAvatarInEditMode();
	}

    DDCPicture = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCPicture, DDC);

    DDCPicture.prototype.getParams = function () {

		var item = this.getGlobalParams();
		
		item.Data.avatarId        = this.avatarId;

	    return item;
    };

	//--------------------------------------------------------------------------------------------
	DDCPicture.prototype.cancelEditMode  = function(){
		storeAvatar(this.ID, this.bckAvatarId);
		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		return;
	};
	//--------------------------------------------------------------------------------------------
	DDCPicture.prototype.loadAvatarInEditMode = function () {
		//tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		xajax_load_avatar_in_edit_mode(this.ID, this.avatarId);
	};
	//--------------------------------------------------------------------------------------------
	DDCPicture.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckAvatarId = this.avatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCPicture.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.avatarId = this.bckAvatarId;
	};
