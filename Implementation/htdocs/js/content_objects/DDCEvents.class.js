//-------------------------------------------------------
function events_show_calendar_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsShowCalendar = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function events_show_summaries_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsShowSummaries = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function events_show_venues_check(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsShowVenues = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function set_events_thread_value(index, value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsThreads[index] = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function events_add_thread(elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsThreads[tmpEl.eventsThreads.length] = 0;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function set_events_futered_display_number(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsFuteredDisplayNumber = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function set_events_display_number(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsDisplayNumber = value;
	WarecorpDDblockApp.redrawElementLight(elementId);
}
//-------------------------------------------------------
function event_display_style_change(elementId, value)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventDisplayStyle = value;
	//WarecorpDDblockApp.redrawElementLight(elementId);
	WarecorpDDblockApp.redrawElement(elementId);
}
//-------------------------------------------------------
function events_remove_section(elementId, index)
{
		var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
		for(k=index;k<(tmpEl.eventsThreads.length-1);k++)
		{
			tmpEl.eventsThreads[k] = tmpEl.eventsThreads[k+1];
		}
		tmp=tmpEl.eventsThreads.pop();
		WarecorpDDblockApp.redrawElementLight(elementId);
}




DDCEvents = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

YAHOO.extend(DDCEvents, DDC);

DDCEvents.prototype.getParams = function () {
    var item = this.getGlobalParams();
	
	item["Data"]["event_display_style"] = this.eventDisplayStyle;
	item["Data"]["events_futered_display_number"] = this.eventsFuteredDisplayNumber;
	item["Data"]["events_display_number"] = this.eventsDisplayNumber;
	item["Data"]["events_threads"] = this.eventsThreads;
	item["Data"]["events_show_summaries"] = this.eventsShowSummaries;
	item["Data"]["events_show_calendar"] = this.eventsShowCalendar;
	item["Data"]["events_show_venues"] = this.eventsShowVenues;
	
    return item;
};

//--------------------------------------------------------------------------------------------
DDCEvents.prototype.backupParams = function () {
	this.backupGlobalParams();
	
	this.bckEventDisplayStyle = this.eventDisplayStyle;
	this.bckEventsFuteredDisplayNumber = this.eventsFuteredDisplayNumber;
	this.bckEventsDisplayNumber = this.eventsDisplayNumber;
	this.bckEventsThreads = new Array();
	for(var i=0; i<this.eventsThreads.length; i++)
	{
		this.bckEventsThreads[i] = this.eventsThreads[i]
	}
	this.bckEventsShowSummaries = this.eventsShowSummaries;
	this.bckEventsShowCalendar = this.eventsShowCalendar;
	this.bckEventsShowVenues = this.eventsShowVenues;
};
//--------------------------------------------------------------------------------------------
DDCEvents.prototype.restoreParams = function () {
	this.restoreGlobalParams();
	
	this.eventDisplayStyle = this.bckEventDisplayStyle;
	this.eventsFuteredDisplayNumber = this.bckEventsFuteredDisplayNumber;
	this.eventsDisplayNumber = this.bckEventsDisplayNumber;
	this.eventsThreads = new Array();
	for(var i=0; i<this.bckEventsThreads.length; i++)
	{
		this.eventsThreads[i] = this.bckEventsThreads[i];
	}
	this.eventsShowSummaries = this.bckEventsShowSummaries;
	this.eventsShowCalendar = this.bckEventsShowCalendar;
	this.eventsShowVenues = this.bckEventsShowVenues;
};