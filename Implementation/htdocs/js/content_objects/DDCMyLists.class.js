//-------------------------------------------------------
function list_display_type_select_change(elementId, index)
{
    var tmpElement = WarecorpDDblockApp.getObjByID(elementId);
    tmpElement.listDisplayType = index;

    if(index == 1) {
        tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
        //tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
        xajax_get_block_content(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
    } else {
        xajax_get_block_content(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
    }
}

//-------------------------------------------------------
function list_categories_check_change(elementId, key, checked)
{

    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);

    if (checked)
    {
        tmpEl.listCategoriesToDisplay[tmpEl.listCategoriesToDisplay.length] = key;
        document.getElementById('list_categories_check_'+String(key)+'_'+elementId).checked = "checked";
    }
    else
    {
        document.getElementById('list_categories_check_'+String(key)+'_'+elementId).checked = "";

        if (tmpEl.listCategoriesToDisplay.length)
        {
            for(i=0; i<tmpEl.listCategoriesToDisplay.length-1; i++)
            {
                if (tmpEl.listCategoriesToDisplay[i] == key)
                {
                    tmpEl.listCategoriesToDisplay[i] = tmpEl.listCategoriesToDisplay[tmpEl.listCategoriesToDisplay.length-1];
                }
            }
            tmp=tmpEl.listCategoriesToDisplay.pop();
        }
    }
    WarecorpDDblockApp.redrawElementLight(elementId);
}

//-------------------------------------------------------
function list_default_index_sort_change(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.listDefaultIndexSort = value;
    WarecorpDDblockApp.redrawElementLight(elementId);
}

//-------------------------------------------------------
function list_default_sort_change(elementId, value)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.listDefaultSort = value;
    WarecorpDDblockApp.redrawElementLight(elementId);
}

//-------------------------------------------------------
function list_show_summaries_check(value, elementId)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.listShowSummaries = value;
    WarecorpDDblockApp.redrawElementLight(elementId);
}

//-------------------------------------------------------
function set_list_display_number_in_each_category(value, elementId)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.listDisplayNumberInEachCategory = value;
    WarecorpDDblockApp.redrawElementLight(elementId);
}

//-------------------------------------------------------
function set_list_to_display(value, elementId)
{
    var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
    tmpEl.listToDisplay = value;
    WarecorpDDblockApp.redrawElementLight(elementId);
}
//--------------------------------------------------------



    DDCMyLists = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCMyLists, DDC);

    DDCMyLists.prototype.getParams = function () {

        var item = this.getGlobalParams();

        item.Data.list_categories_to_display = [];
        for(i=0; i<this.listCategoriesToDisplay.length; i++)
        {
            if (this.listCategoriesToDisplay[i])
            {
                item.Data.list_categories_to_display[item.Data.list_categories_to_display.length] = this.listCategoriesToDisplay[i];
            }
        }

        item.Data.list_display_type = this.listDisplayType;
        item.Data.list_to_display = this.listToDisplay;
        item.Data.list_default_index_sort = this.listDefaultIndexSort;
        item.Data.list_default_sort = this.listDefaultSort;
        item.Data.list_display_number_in_each_category = this.listDisplayNumberInEachCategory;
        item.Data.list_show_summaries = this.listShowSummaries;

        return item;
    };

    //--------------------------------------------------------------------------------------------
    DDCMyLists.prototype.backupParams = function () {
        this.backupGlobalParams();

        //item["Data"]["listCategoriesToDisplay"] = this.listCategoriesToDisplay;

        this.bckListDisplayType = this.listDisplayType;
        this.bckListToDisplay = this.listToDisplay;
        this.bckListDefaultIndexSort = this.listDefaultIndexSort;
        this.bckListDefaultSort = this.listDefaultSort;
        this.bckListDisplayNumberInEachCategory = this.listDisplayNumberInEachCategory;
        this.bckListShowSummaries = this.listShowSummaries;
    };
    //--------------------------------------------------------------------------------------------
    DDCMyLists.prototype.restoreParams = function () {
        this.restoreGlobalParams();

        this.listDisplayType = this.bckListDisplayType;
        this.listToDisplay = this.bckListToDisplay;
        this.listDefaultIndexSort = this.bckListDefaultIndexSort;
        this.listDefaultSort = this.bckListDefaultSort;
        this.listDisplayNumberInEachCategory = this.bckListDisplayNumberInEachCategory;
        this.listShowSummaries = this.bckListShowSummaries;
    };

    //--------------------------------------------------------------------------------------------

    DDCMyLists.prototype.resetEditMode = function() {
        if(this.listDisplayType == 0){
           tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        }
        return;
    };
    DDCMyLists.prototype.cancelEditMode = function() {
        if(this.listDisplayType == 0){
           tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        }
        return;
    };
    DDCMyLists.prototype.applyEditMode = function() {
		if(this.listDisplayType == 0 && null !== document.getElementById('tinyMCE_'+this.ID+'_H') ) {
            tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
            this.headline = document.getElementById('tinyMCE_'+this.ID+'_H').value;
        }
        return;
    };
//--------------------------------------------------------------------------------------------
