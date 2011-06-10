//switch discussion tabs most active/most recent
function switchDiscussionsTab(elementId, id2,  mode)
{
	document.getElementById('discussion_tab_'+mode+'_'+elementId+'_'+id2).style.display = "block";
	document.getElementById('discussion_tab_'+Math.abs(mode-1)+'_'+elementId+'_'+id2).style.display = "none";
}
//show thread summaries changes
function family_discussions_show_thread_summaries_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_show_thread_summaries = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//show thread summaries changes2
function family_discussions_show_thread_summaries_check2(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_show_thread_summaries2 = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
// display most active changed
function family_discussions_most_active_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_display_most_active = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
// display most recent changed
function family_discussions_most_recent_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_display_most_recent = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
// add thread click
function family_discussions_add_thread(elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	
	var last_item = tmpEl.discussions_threads.length;
	tmpEl.discussions_threads[last_item] = [];
	tmpEl.discussions_threads[last_item][0] = 0;
	tmpEl.discussions_threads[last_item][1] = 0;
	WarecorpDDblockApp.redrawElement(elementId);
}
//change thread
function set_family_discussions_thread_value(index, value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_threads[index][0] = value;
	tmpEl.discussions_threads[index][1] = 0;
	WarecorpDDblockApp.redrawElement(elementId);
}
//change thread topic
function set_family_discussions_thread_topic_value(index, value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_threads[index][1] = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//change threads number
function set_family_discussions_thread_number(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.discussions_show_threads_number = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//remove section
function family_discussion_remove_section(elementId, index)
{
		var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
		for(k=index;k<(tmpEl.discussions_threads.length-1);k++)
		{
			tmpEl.discussions_threads[k][0] = tmpEl.discussions_threads[k+1][0];
			tmpEl.discussions_threads[k][1] = tmpEl.discussions_threads[k+1][1];
		}
		tmp=tmpEl.discussions_threads.pop();
		
		WarecorpDDblockApp.redrawElement (elementId);
}

//----------------------------------------------------------------------------------------------------
	

    DDCFamilyDiscussions = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCFamilyDiscussions, DDC);

    DDCFamilyDiscussions.prototype.getParams = function () {
      
		var item = this.getGlobalParams();

		item.Data.discussionsThreads = this.discussions_threads;
		
		item.Data.discussionsShowThreadSummaries = this.discussions_show_thread_summaries;
		item.Data.discussionsShowThreadSummaries2 = this.discussions_show_thread_summaries2;
		item.Data.discussionsDisplayMostActive = this.discussions_display_most_active;
		item.Data.discussionsDisplayMostRecent = this.discussions_display_most_recent;
		item.Data.discussionsShowThreadsNumber = this.discussions_show_threads_number;	
		
		
        return item;
    };
	
	//--------------------------------------------------------------------------------------------
	DDCFamilyDiscussions.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bck_discussions_show_thread_summaries = this.discussions_show_thread_summaries;
		this.bck_discussions_show_thread_summaries2 = this.discussions_show_thread_summaries2;
		this.bck_discussions_display_most_active = this.discussions_display_most_active;
		this.bck_discussions_display_most_recent = this.discussions_display_most_recent;
		this.bck_discussions_show_threads_number = this.discussions_show_threads_number;
		
		this.bck_discussions_threads = [];
		for(var i=0; i<this.discussions_threads.length; i++)
		{
			this.bck_discussions_threads[i] = [];
			this.bck_discussions_threads[i][0] = this.discussions_threads[i][0]; //discussion
			this.bck_discussions_threads[i][1] = this.discussions_threads[i][1]; //topic
		}
		
	};
	//--------------------------------------------------------------------------------------------
	DDCFamilyDiscussions.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.discussions_show_thread_summaries = this.bck_discussions_show_thread_summaries;
		this.discussions_show_thread_summaries2 = this.bck_discussions_show_thread_summaries2;
		this.discussions_display_most_active = this.bck_discussions_display_most_active;
		this.discussions_display_most_recent = this.bck_discussions_display_most_recent;
		this.discussions_show_threads_number = this.bck_discussions_show_threads_number;
		
		this.discussions_threads = [];
		for(var i=0; i<this.bck_discussions_threads.length; i++)
		{
			this.discussions_threads[i] = [];
			this.discussions_threads[i][0] = this.bck_discussions_threads[i][0]; //discussion
			this.discussions_threads[i][1] = this.bck_discussions_threads[i][1]; //topic
		}
	};
   
   //--------------------------------------------------------------------------------------------
