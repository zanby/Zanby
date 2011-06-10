function storeDDMyVideo(elementId, avatar)
{
    tmpElement = WarecorpDDblockApp.getObjByID(elementId);
    tmpElement.videoId = avatar;
    WarecorpDDblockApp.redrawElement(tmpElement.ID);
}

    DDCMyVideoContentBlock = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };
    YAHOO.extend(DDCMyVideoContentBlock, DDC);

    DDCMyVideoContentBlock.prototype.getParams = function () {

        var item = this.getGlobalParams();

        item["Data"]["Content"] = this.innerText;
        item["Data"]["videoId"] = this.videoId;

        return item;
    };


    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.setEditMode = function(){
        return;
    }
    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.resetEditMode = function(){
//		document.getElementById('tinyMCE_'+this.ID+'_div').style.display = "none";
//    	document.getElementById('tinyMCE_'+this.ID+'_div_buttons').style.display = "none";
//		document.getElementById('tinyMCE_'+this.ID+'_div_wait').style.display = "block";
//		tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    }
    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.cancelEditMode = function(){
//		tinyMCEDeinit('tinyMCE_'+this.ID);
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    }
    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.applyEditMode = function(){
//		tinyMCEDeinit('tinyMCE_'+this.ID);
//		this.innerText = document.getElementById('tinyMCE_'+this.ID).value;
//		tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
//		this.headline = document.getElementById('tinyMCE_'+this.ID+'_H').value;
        return;
    }
    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.backupParams = function () {
        this.backupGlobalParams();

        this.bckText = this.innerText;
        this.bckVideoId = this.videoId;
    };
    //--------------------------------------------------------------------------------------------
    DDCMyVideoContentBlock.prototype.restoreParams = function () {
        this.restoreGlobalParams();

        if (this.bckText)
        {
            this.innerText = this.bckText;
            this.videoId = this.bckVideoId;
        }
    };
