//switch topvideo tabs most active/most recent
function switchTopVideosTab(elementId, id2,  mode)
{ 
	var mode1 = 0; var mode2 = 0;
	switch (mode) {
		case 0: mode1=1; mode2=2; break;
		case 1: mode1=0; mode2=2; break;
		case 2: mode1=0; mode2=1; break;
	}
	document.getElementById('topvideo_tab_'+mode+'_'+elementId+'_'+id2).style.display = "block";
	if (document.getElementById('topvideo_tab_'+mode1+'_'+elementId+'_'+id2)) {
        document.getElementById('topvideo_tab_'+mode1+'_'+elementId+'_'+id2).style.display = "none";
    }
	if (document.getElementById('topvideo_tab_'+mode2+'_'+elementId+'_'+id2)) {
        document.getElementById('topvideo_tab_'+mode2+'_'+elementId+'_'+id2).style.display = "none";
    }
}
// display most active changed
function family_topvideos_most_active_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.topvideos_display_most_active = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
// display most recent changed
function family_topvideos_most_recent_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.topvideos_display_most_recent = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}

// display most recent changed
function family_topvideos_most_upped_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.topvideos_display_most_upped = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}

//change threads number
function set_family_topvideos_thread_number(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.topvideos_show_threads_number = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}

//----------------------------------------------------------------------------------------------------
	

    DDCFamilyTopVideos = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCFamilyTopVideos, DDC);

    DDCFamilyTopVideos.prototype.getParams = function () {
      
		var item = this.getGlobalParams();

		item.Data.topvideosDisplayMostActive = this.topvideos_display_most_active;
		item.Data.topvideosDisplayMostRecent = this.topvideos_display_most_recent;
		item.Data.topvideosDisplayMostUpped = this.topvideos_display_most_upped;
		item.Data.topvideosShowThreadsNumber = this.topvideos_show_threads_number;	
		
		
        return item;
    };
	
	//--------------------------------------------------------------------------------------------
	DDCFamilyTopVideos.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bck_topvideos_display_most_active = this.topvideos_display_most_active;
		this.bck_topvideos_display_most_recent = this.topvideos_display_most_recent;
		this.bck_topvideos_display_most_upped = this.topvideos_display_most_upped;
		this.bck_topvideos_show_threads_number = this.topvideos_show_threads_number;
	
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyTopVideos.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.topvideos_display_most_active = this.bck_topvideos_display_most_active;
		this.topvideos_display_most_recent = this.bck_topvideos_display_most_recent;
		this.topvideos_display_most_upped = this.bck_topvideos_display_most_upped;
		this.topvideos_show_threads_number = this.bck_topvideos_show_threads_number;

	};
   
   //--------------------------------------------------------------------------------------------
