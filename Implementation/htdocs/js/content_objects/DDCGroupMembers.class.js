//----------------------------------------------------------------------------------------------------
	function showGroupMembersPopup(elementId)
	{
		if (document.getElementById('group-members-popup_'+elementId)) {
            document.getElementById('group-members-popup_'+elementId).style.display = "block";
        }
		var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
		
		for(var i=0; i<tmpEl.hide.length; i++){
			if( !tmpEl.hide[i] || tmpEl.hide[i] == 0 ){
				if (document.getElementById('group_members_hide_check_'+i+'_'+elementId)) {
                    document.getElementById('group_members_hide_check_'+i+'_'+elementId).checked='checked';
                }
			}else if (document.getElementById('group_members_hide_check_'+i+'_'+elementId)) {
                document.getElementById('group_members_hide_check_'+i+'_'+elementId).checked=0;
			}
		}
		
		document.getElementById('href-group-members-popup_'+elementId).className = "";
		return false;
	}
	//------
	function switchGroupMembersPopup(elementId)
	{
		if (document.getElementById('group-members-popup_'+elementId).style.display == "block")
		{
			hideGroupMembersPopup(elementId);
		}
		else
		{
			showGroupMembersPopup(elementId);
		}
	}
	//------
	function hideGroupMembersPopup(elementId)
	{
		document.getElementById('group-members-popup_'+elementId).style.display = "none";
		document.getElementById('href-group-members-popup_'+elementId).className = "switched";
	}
	//------
	function group_members_element_hide(element_number, is_hide , elementId)
    {
		
		if (!is_hide) {is_hide=0;}
		
		WarecorpDDblockApp.getObjByID(elementId).hide[element_number] = is_hide;
		
		if (is_hide)
		{
			document.getElementById('group_members_hide_check_'+element_number+'_'+elementId).checked=0;
			document.getElementById('gmdiv_'+element_number+'_'+elementId).style.display='none';
		}
		else
		{
			document.getElementById('group_members_hide_check_'+element_number+'_'+elementId).checked="checked";
			document.getElementById('gmdiv_'+element_number+'_'+elementId).style.display='block';	
		}
		
		return false;
	}
//-------------------------------------------------------
function display_gm_type_select_change(elementId, index)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.displayType = index;
	WarecorpDDblockApp.redrawElement(elementId);
}
//-------------------------------------------------------
function default_gm_index_sort_change(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.defaultIndexSort = value;
	WarecorpDDblockApp.redrawElement(elementId);
}
//-------------------------------------------------------
function set_gm_display_number_in_each_region(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.displayNumberInEachRegion = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//--------------------------------------------------------
	
	

    DDCGroupMembers = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCGroupMembers, DDC);

    DDCGroupMembers.prototype.getParams = function () {
      
		var item = this.getGlobalParams();

		item["Data"]["display_type"] = this.displayType;
		item["Data"]["default_index_sort"] = this.defaultIndexSort;
		item["Data"]["display_number_in_each_region"] = this.displayNumberInEachRegion;
		
		item["Data"]["hide"]    = new Array();
				
		for(i=0;i<this.hide.length;i++){
			if(this.hide[i] && this.hide[i] != '0'){
				item["Data"]["hide"][this.hide[i]] = 1;
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
		
		this.bckHide = new Array();
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
   
