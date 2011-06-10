//-------------------------------------------------------
function family_people_entity_to_display_change(elementId, index)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.entityToDisplay = index;
	WarecorpDDblockApp.redrawElement(elementId);
}
//-------------------------------------------------------
    DDCFamilyPeople = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCFamilyPeople, DDCGroupMembers);



    DDCGroupMembers.prototype.getParams = function () {
      
		var item = this.getGlobalParams();

		item.Data.display_type = this.displayType;
		item.Data.default_index_sort = this.defaultIndexSort;
		item.Data.display_number_in_each_region = this.displayNumberInEachRegion;
		item.Data.entity_to_display = this.entityToDisplay;
		
		item.Data.hide    = [];
				
		for(i=0;i<this.hide.length;i++){
			if(this.hide[i] && this.hide[i] != '0') {
				item.Data.hide[this.hide[i]] = 1;
			}
		}
		
        return item;
    };
	
	//--------------------------------------------------------------------------------------------
	DDCGroupMembers.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckDisplayType = this.displayType;
		this.bckDefaultIndexSort = this.defaultIndexSort;
		this.bckDisplayNumberInEachRegion = this.displayNumberInEachRegion;
		this.bckEntityToDisplay = this.entityToDisplay;
		
		this.bckHide = [];
		for(var i=0; i<this.hide.length; i++){
			if(this.hide[i]){
				this.bckHide[i] = this.hide[i];
			}else{
				this.bckHide[i] = 0;
			}
		}
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupMembers.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.displayType = this.bckDisplayType;
		this.defaultIndexSort = this.bckDefaultIndexSort;
		this.displayNumberInEachRegion = this.bckDisplayNumberInEachRegion;
		this.entityToDisplay = this.bckEntityToDisplay;
		
		if (this.bckHide)
		{
			for(var i=0; i<this.hide.length; i++){
				if(this.bckHide[i]){
					this.hide[i] = this.bckHide[i];
				}else{
					this.hide[i] = 0;
				}
			}
		}
	};
   
