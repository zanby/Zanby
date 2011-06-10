
    DDCTextBlock = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };
    YAHOO.extend(DDCTextBlock, DDC);

    DDCTextBlock.prototype.getParams = function () {

		var item = this.getGlobalParams();
  
    	item["Data"]["Content"] = this.innerText;

        return item;
    };
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.setEditMode = function(){
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.resetEditMode = function(){
		document.getElementById('tinyMCE_'+this.ID+'_div').style.display = "none";
    	document.getElementById('tinyMCE_'+this.ID+'_div_buttons').style.display = "none";
		document.getElementById('tinyMCE_'+this.ID+'_div_wait').style.display = "block";
		tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.cancelEditMode = function(){
		tinyMCEDeinit('tinyMCE_'+this.ID);
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.applyEditMode = function(){
		tinyMCEDeinit('tinyMCE_'+this.ID);
		this.innerText = document.getElementById('tinyMCE_'+this.ID).value;
		return;
	}
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckText = this.innerText; 
	};
	//--------------------------------------------------------------------------------------------
	DDCTextBlock.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		if (this.bckText)
		{
			this.innerText = this.bckText;
		}
	};
