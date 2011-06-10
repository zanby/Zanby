/*
 *	@author Alexander Komarovski 
 */

//eventToDisplayId
function set_wMap_eventToDisplayId(value, elementId)
{
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventToDisplayId = value;
	tmpEl.updateMap();
}

//defaultDisplayType
function wMap_defaultDisplayType_change(elementId, value) {
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.defaultDisplayType = value;
	tmpEl.updateMap();
}

//displayRange
function wMap_displayRange_change(elementId, value) {
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.displayRange = value;
	tmpEl.updateMap();
}

//eventsDisplayType
function wMap_eventsDisplayType_change(elementId, value) {
	var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
	tmpEl.eventsDisplayType = value;
	
	if (value == 1) {
		if (document.getElementById('wMap_eventToDisplayId_1_'+elementId)) { document.getElementById('wMap_eventToDisplayId_1_'+elementId).style.display = ''; }
		if (document.getElementById('wMap_eventToDisplayId_2_'+elementId)) { document.getElementById('wMap_eventToDisplayId_2_'+elementId).style.display = ''; }

                if (document.getElementById('wMap_eventToDisplayId_select_'+elementId)) {
                    var el = document.getElementById('wMap_eventToDisplayId_select_'+elementId);
                    if (el.options[el.selectedIndex].value) {
                        set_wMap_eventToDisplayId(el.options[el.selectedIndex].value, elementId);
                        return;
                    }
                }

	} else {
		if (document.getElementById('wMap_eventToDisplayId_1_'+elementId)) { document.getElementById('wMap_eventToDisplayId_1_'+elementId).style.display = 'none'; }
		if (document.getElementById('wMap_eventToDisplayId_2_'+elementId)) { document.getElementById('wMap_eventToDisplayId_2_'+elementId).style.display = 'none'; }
	}
	tmpEl.updateMap();
}

//----------------------------------------------------------------------------------------------------
	

    DDCGroupWidgetMap = function(id, sGroup, config) {
        this.onlyWide = 1;
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCGroupWidgetMap, DDC);

    DDCGroupWidgetMap.prototype.getParams = function () {
      
		var item = this.getGlobalParams();

		item.Data.defaultDisplayType = this.defaultDisplayType;
		item.Data.displayRange = this.displayRange;
		item.Data.eventsDisplayType = this.eventsDisplayType;
		item.Data.eventToDisplayId = this.eventToDisplayId;	
		
		
        return item;
    };
    //--------------------------------------------------------------------------------------------
	DDCGroupWidgetMap.prototype.updateMap = function () {
		document.getElementById('wMap_iframe_'+this.ID).src = this.getParamsAsPreparedString(); 
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupWidgetMap.prototype.getParamsAsPreparedString = function () {
		resultString = '';
		if (this.defaultDisplayType == 1) {resultString = resultString + '&defaultDisplayType=1';}
		if (this.displayRange == 1) {resultString = resultString + '&displayRange=1';}
		if (this.eventsDisplayType == 1) {
			resultString = resultString + '&eventsDisplayType=1';
			if (this.eventToDisplayId) {
				resultString = resultString + '&eventToDisplayId='+this.eventToDisplayId;
			}
		}
		
		resultString = resultString + '&width='+document.getElementById('wMap_iframe_'+this.ID).style.width;
		resultString = resultString + '&height='+(parseInt(document.getElementById('wMap_iframe_'+this.ID).style.height, 10)-20);
		resultString = resultString + '&groupContext='+this.groupContext;
		
		return AppTheme.base_url+'/widget.js?wtype=map&wdtype=iniframe&needDistrictLayer=1&width='+document.getElementById('wMap_iframe_'+this.ID).style.width+'&height=300&kmlControlInternalId=getKMLLink'+resultString;
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupWidgetMap.prototype.backupParams = function () {
		this.backupGlobalParams();
		
		this.bckDefaultDisplayType = this.defaultDisplayType;
		this.bckDisplayRange = this.displayRange;
		this.bckEventsDisplayType = this.eventsDisplayType;
		this.bckEventToDisplayId = this.eventToDisplayId;
	
	};
	//--------------------------------------------------------------------------------------------
	DDCGroupWidgetMap.prototype.restoreParams = function () {
		this.restoreGlobalParams();
		
		this.defaultDisplayType = this.bckDefaultDisplayType;
		this.displayRange = this.bckDisplayRange;
		this.eventsDisplayType = this.bckEventsDisplayType;
		this.eventToDisplayId = this.bckEventToDisplayId;
	};
	//--------------------------------------------------------------------------------------------
	
	DDCGroupWidgetMap.prototype.setEditMode = function(){
		return;
	};
	DDCGroupWidgetMap.prototype.resetEditMode = function(){
		return;
	};
	DDCGroupWidgetMap.prototype.cancelEditMode = function(){
		return;
	};
	DDCGroupWidgetMap.prototype.applyEditMode = function(){
		return;
	};
    //--------------------------------------------------------------------------------------------
