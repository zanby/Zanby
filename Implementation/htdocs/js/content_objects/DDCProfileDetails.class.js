    //----------------------------------------------------------------------------------------------------
    function showProfilePopup(elementId)
    {
        document.getElementById('profile-popup_'+elementId).style.display = "block";
        var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
        for(var i=0; i<6; i++){
            if(tmpEl.hide[i] == 1){
                document.getElementById('hide_check_'+i+'_'+elementId).checked=0;
            }else{
                document.getElementById('hide_check_'+i+'_'+elementId).checked='checked';
            }
        }
        document.getElementById('href-profile-popup_'+elementId).className = "";
    }

    function hideProfilePopup(elementId)
    {
        document.getElementById('profile-popup_'+elementId).style.display = "none";
        document.getElementById('href-profile-popup_'+elementId).className = "switched";
    }

    function switchProfilePopup(elementId)
    {
        if (document.getElementById('profile-popup_'+elementId).style.display == "block")
        {
            hideProfilePopup(elementId);
        }
        else
        {
            showProfilePopup(elementId);
        }
    }

    function makeProfileChanges(elementId)
    {
        var tmpEl = WarecorpDDblockApp.getObjByID(elementId);
        tmpEl.globalChangesExists = true;
    }
    //----------------------------------------------------------------------------------------------------


    DDCProfileDetails = function(id, sGroup, config) {
        if (id) {
            this.init(id, sGroup, config);
        }
    };

    YAHOO.extend(DDCProfileDetails, DDC);

    DDCProfileDetails.prototype.getParams = function () {

        var item = this.getGlobalParams();

        item.Data.hide    = [];

        for(var i=0; i<6; i++){
            if(this.hide[i]){
                item.Data.hide[i] = this.hide[i];
            }else{
                item.Data.hide[i] = 0;
            }
        }


        return item;
    };
    //--------------------------------------------------------------------------------------------
    DDCProfileDetails.prototype.setEditMode = function(){
        this.globalChangesExists = false;
        return;
    };
    DDCProfileDetails.prototype.resetEditMode = function(){
        this.globalChangesExists = false;
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    };
    DDCProfileDetails.prototype.cancelEditMode = function(){
        this.globalChangesExists = false;
        tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
        return;
    };
    DDCProfileDetails.prototype.applyEditMode = function(){
        if ( null !== document.getElementById('tinyMCE_'+this.ID+'_H') ) {
            tinyMCEDeinit('tinyMCE_'+this.ID+'_H');
            this.headline = document.getElementById('tinyMCE_'+this.ID+'_H').value;
        }

        this.globalChangesExists = false;
        xajax_update_user_profile(document.getElementById(this.ID+'_gender').value,document.getElementById(this.ID+'_realname').value,"", this.ID);

        return;
    };

    DDCProfileDetails.prototype.haveGlobalChanges = function(){
        if (this.globalChangesExists)
        {
            return true;
        }

        return false;
    };

    //--------------------------------------------------------------------------------------------
    DDCProfileDetails.prototype.backupParams = function () {
        this.backupGlobalParams();

        this.bckHide = [];
        for(var i=0; i<6; i++){
            if(this.hide[i]){
                this.bckHide[i] = this.hide[i];
            }else{
                this.bckHide[i] = 0;
            }
        }
    };
    //--------------------------------------------------------------------------------------------
    DDCProfileDetails.prototype.restoreParams = function () {
        this.restoreGlobalParams();

        if (this.bckHide)
        {
            for(var i=0; i<6; i++){
                if(this.bckHide[i]){
                    this.hide[i] = this.bckHide[i];
                }else{
                    this.hide[i] = 0;
                }
            }
        }
    };

    //--------------------------------------------------------------------------------------------
    function profile_element_hide(element_number, is_hide , elementId)
    {
        if (is_hide == true) {is_hide=1;}
        if (is_hide == false) {is_hide=0;}

        WarecorpDDblockApp.getObjByID(elementId).hide[element_number] = is_hide;

        if ( parseInt(is_hide, 10) === 1 )
        {
            document.getElementById('hide_check_'+element_number+'_'+elementId).checked=0;
            document.getElementById('pddiv_'+element_number+'_'+elementId).style.display='none';
            if (document.getElementById('a_pddiv_'+element_number+'_'+elementId))
            {
                document.getElementById('a_pddiv_'+element_number+'_'+elementId).style.display='none';
            }
        }
        else
        {
            document.getElementById('hide_check_'+element_number+'_'+elementId).checked="checked";
            document.getElementById('pddiv_'+element_number+'_'+elementId).style.display = "";

            if (true && document.getElementById('a_pddiv_'+element_number+'_'+elementId))
            {
                document.getElementById('a_pddiv_'+element_number+'_'+elementId).style.display="";
            }
        }
    }
