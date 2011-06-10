//-------------------------------------------------------
function display_fmi_type_select_change(elementId, index)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.displayType = index;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function default_fmi_index_sort_change(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.defaultIndexSort = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function set_fmi_display_number_in_each_region(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.displayNumberInEachRegion = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//--------------------------------------------------------
	
	

    DDCFamilyMemberIndex = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCFamilyMemberIndex, DDC);

    DDCFamilyMemberIndex.prototype.getParams = function () {
      
		var item = this.getGlobalParams();
		
		item.Data.display_type = this.displayType;
		item.Data.default_index_sort = this.defaultIndexSort;
		item.Data.display_number_in_each_region = this.displayNumberInEachRegion;
		
		item.Data.hide    = [];
				
		for(i=0;i<this.hide.length;i++){
			if(this.hide[i] && this.hide[i] != '0') {
				item.Data.hide[this.hide[i]] = 1;
			}
		}
		
        return item;
    };
	
	//--------------------------------------------------------------------------------------------
	DDCFamilyMemberIndex.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckDisplayType = this.displayType;
		this.bckDefaultIndexSort = this.defaultIndexSort;
		this.bckDisplayNumberInEachRegion = this.displayNumberInEachRegion;
		
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyMemberIndex.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.displayType = this.bckDisplayType;
		this.defaultIndexSort = this.bckDefaultIndexSort;
		this.displayNumberInEachRegion = this.bckDisplayNumberInEachRegion;
	};
   
