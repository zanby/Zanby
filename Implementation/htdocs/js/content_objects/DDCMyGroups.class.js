Array.prototype.in_array = function(value)
{
    var i;
    if( this.indexOf !== undefined ) {
        return (this.indexOf(value) !== -1);
    } else {
        for ( i = 0; i < this.length; ++i ) {
            if ( this.valueOf(i) == value ) {
                return true;
            }
        }
        return false;
    }
};
//----------------------------------------------------------------------------------------------------
function switchMyFamilyGroupsPopup(elementId)
{
    if (document.getElementById('my-family-groups-popup_'+elementId).style.display == "block") {
        hideMyFamilyGroupsPopup(elementId);
    } else {
        showMyFamilyGroupsPopup(elementId);
    }
}
//----------------------------------------------------------------------------------------------------
function switchMyGroupsPopup(elementId)
{
    if (document.getElementById('my-groups-popup_'+elementId).style.display == "block") {
        hideMyGroupsPopup(elementId);
    } else {
        showMyGroupsPopup(elementId);
    }
}
//----------------------------------------------------------------------------------------------------
function showMyGroupsPopup(elementId)
{
    var i, tmpEl;
    hideMyFamilyGroupsPopup(elementId);
    
    document.getElementById('my-groups-popup_'+elementId).style.display = "block";
    tmpEl = WarecorpDDblockApp.getObjByID(elementId);

    for(i = 0; i < tmpEl.group_uids.length; ++i) {
        if(
                tmpEl.unhide.in_array(tmpEl.group_uids[i])                                                      || 
                (tmpEl.auto_disp_simple  &&  !tmpEl.not_new_groups.in_array(parseInt(tmpEl.group_uids[i],10)))
        ) {
            document.getElementById('group_hide_check_'+i+'_'+elementId).checked = true;
        } else {
            document.getElementById('group_hide_check_'+i+'_'+elementId).checked = false;
        }
    }
    
    document.getElementById('href-my-groups-popup_'+elementId).className = "";
    
    return false;
}
function hideMyGroupsPopup(elementId)
{
    document.getElementById('my-groups-popup_'+elementId).style.display = "none";
    document.getElementById('href-my-groups-popup_'+elementId).className = "switched";
}
// Family
function showMyFamilyGroupsPopup(elementId)
{
    var i, tmpEl;
    hideMyGroupsPopup(elementId);
    
    document.getElementById('my-family-groups-popup_'+elementId).style.display = "block";
    tmpEl = WarecorpDDblockApp.getObjByID(elementId);

    for(i = 0; i < tmpEl.family_uids.length; ++i) {
        if(
                tmpEl.family_unhide.in_array(tmpEl.family_uids[i])                                                      || 
                (tmpEl.auto_disp_family  &&  !tmpEl.not_new_groups.in_array(parseInt(tmpEl.family_uids[i],10)))
        ) {
            document.getElementById('family_group_hide_check_'+i+'_'+elementId).checked = true;
        } else {
            document.getElementById('family_group_hide_check_'+i+'_'+elementId).checked = false;
        }
    }
    
    document.getElementById('href-my-families-popup_'+elementId).className = "";
}
function hideMyFamilyGroupsPopup(elementId)
{
    document.getElementById('my-family-groups-popup_'+elementId).style.display = "none";
    document.getElementById('href-my-families-popup_'+elementId).className = "switched";
}
//----------------------------------------------------------------------------------------------------

DDCMyGroups = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

YAHOO.extend(DDCMyGroups, DDC);

DDCMyGroups.prototype.getParams = function () {
  
    var item = this.getGlobalParams();

    item.Data.unhide            = [];
    item.Data.family_unhide     = [];
    item.Data.not_new_groups    = [];
    item.Data.auto_disp_simple  = 0;
    item.Data.auto_disp_family  = 0;

    for(i = 0; i < this.group_uids.length; i++) {
        if( this.unhide.in_array(this.group_uids[i]) || 
          ( this.auto_disp_simple  &&  !this.not_new_groups.in_array(parseInt(this.group_uids[i],10)) )
        ) {
            item.Data.unhide[this.group_uids[i]] = 1;
        }
    }
    for(i = 0; i < this.family_uids.length; i++) {
        if( this.family_unhide.in_array(this.family_uids[i]) ||
          ( this.auto_disp_family  &&  !this.not_new_groups.in_array(parseInt(this.family_uids[i],10)) )
        ) {
            item.Data.family_unhide[this.family_uids[i]] = 1;
        }
    }

    item.Data.not_new_groups   = this.not_new_groups.slice(0);
    item.Data.auto_disp_family = this.auto_disp_family;
    item.Data.auto_disp_simple = this.auto_disp_simple;

    return item;
};
//--------------------------------------------------------------------------------------------
DDCMyGroups.prototype.backupParams = function () {
    var i;
    this.backupGlobalParams();
    
    this.bckUnHide = [];
    for(i = 0; i < this.unhide.length; i++){
        if(this.unhide[i]) {
            this.bckUnHide[i] = this.unhide[i];
        } else {
            this.bckUnHide[i] = 0;
        }
    }
    this.bckFamilyUnHide = [];
    for(i = 0; i < this.family_unhide.length; i++) {
        if(this.family_unhide[i]) {
            this.bckFamilyUnHide[i] = this.family_unhide[i];
        } else {
            this.bckFamilyUnHide[i] = 0;
        }
    }
    this.bckAutoDispSimple = this.auto_disp_simple;
    this.bckAutoDispFamily = this.auto_disp_family;
    this.bckNotNewGroups = this.not_new_groups.slice(0);
};
//--------------------------------------------------------------------------------------------
DDCMyGroups.prototype.restoreParams = function () {
    var i;
    this.restoreGlobalParams();
    
    if (this.bckUnHide) {
        for(i=0; i<this.unhide.length; i++) {
            if(this.bckUnHide[i]) {
                this.unhide[i] = this.bckUnHide[i];
            } else {
                this.unhide[i] = 0;
            }
        }
    }
    if (this.bckFamilyUnHide) {
        for(i=0; i<this.family_unhide.length; i++) {
            if(this.bckFamilyUnHide[i]) {
                this.family_unhide[i] = this.bckFamilyUnHide[i];
            } else {
                this.family_unhide[i] = 0;
            }
        }
    }
    this.auto_disp_simple = this.bckAutoDispSimple;
    this.auto_disp_family = this.bckAutoDispFamily;
    this.not_new_groups = this.bckNotNewGroups.slice(0);
};
//--------------------------------------------------------------------------------------------
function group_element_hide(element_number, is_hide , elementId, is_family)
{
    is_hide = is_hide || 0;
    WarecorpDDblockApp.getObjByID(elementId).unhide[element_number] = is_hide;
    
    if (!is_hide) {
        document.getElementById('group_hide_check_'+element_number+'_'+elementId).checked = false;
        //document.getElementById('gpdiv_'+element_number+'_'+elementId).style.display = 'none';
    } else {
        document.getElementById('group_hide_check_'+element_number+'_'+elementId).checked = true;
        //document.getElementById('gpdiv_'+element_number+'_'+elementId).style.display = '';
    }
    
    //return false;
}

function family_group_element_hide(element_number, is_hide , elementId)
{
    is_hide = is_hide || 0;
    WarecorpDDblockApp.getObjByID(elementId).family_unhide[element_number] = is_hide;
    
    if (!is_hide) {
        document.getElementById('family_group_hide_check_' + element_number + '_' + elementId).checked = false;
        //document.getElementById('fgpdiv_' + element_number + '_' + elementId).style.display = 'none';
    } else {
        document.getElementById('family_group_hide_check_' + element_number + '_' + elementId).checked = true;
        //document.getElementById('fgpdiv_' + element_number + '_'+elementId).style.display = '';	
    }
}
function group_automaticaly_display(elementId, type, isDisplaying)
{
    var obj = WarecorpDDblockApp.getObjByID(elementId);
    switch ( type ) {
        case 'simple':
            obj.auto_disp_simple = ( isDisplaying ) ? 1 : 0;
            break;
        case 'family':
            obj.auto_disp_family = ( isDisplaying ) ? 1 : 0;
            break;
        default:
            //  Unknown type
            //  Do nothing
            break;
    }
}
function display_all(elementId, type, is_hide)
{
    var i, uids;
    var obj = WarecorpDDblockApp.getObjByID(elementId);

    if ( type === 'simple' ) {
        for ( i = 0; i < obj.group_uids.length; ++i ) {
            group_element_hide(i, is_hide, elementId);
        }
    } else if ( type === 'family' ) {
        for ( i = 0; i < obj.family_uids.length; ++i ) {
            family_group_element_hide(i, is_hide, elementId, false);
        }
    }
}

