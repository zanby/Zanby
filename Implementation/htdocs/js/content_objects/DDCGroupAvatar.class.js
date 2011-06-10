	function storeAvatar(elementId, avatar)
	{
		tmpElement = WarecorpDDblockApp.getObjByID(elementId);
		tmpElement.avatarId = avatar;
		tmpElement.loadAvatarInEditMode();
	}

    DDCGroupAvatar = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCGroupAvatar, DDC);

    DDCGroupAvatar.prototype.getParams = function () {

		var item = this.getGlobalParams();
		
		item.Data.avatarId        = this.avatarId;
	    
		return item;
    };

	//--------------------------------------------------------------------------------------------
	DDCGroupAvatar.prototype.cancelEditMode  = function(){
		storeAvatar(this.ID, this.bckAvatarId);
		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		return;
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupAvatar.prototype.loadAvatarInEditMode = function () {
		//tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
		xajax_load_avatar_in_edit_mode(this.ID, this.avatarId);
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupAvatar.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckAvatarId = this.avatarId;
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupAvatar.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.avatarId = this.bckAvatarId;
	};
