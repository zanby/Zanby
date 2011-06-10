/*
 *	@author Alexander Komarovski 
 */

(function() {

var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var DDM = YAHOO.util.DragDropMgr;

WarecorpDDblockApp = {
    index : 100,
    ddPresetDiv : document.createElement("div"),
    contentObjects : new Array(),
    targetL:  new YAHOO.util.DDTarget("ddTarget1"),
    targetR:  new YAHOO.util.DDTarget("ddTarget2"),
    targetToInsert: null,
    targetToInsertAfter: null,

    init: function() {
        
        // buttons for drag in the left
        new YAHOO.example.DDPreset("ddContentBlock");
        new YAHOO.example.DDPreset("ddPicture");
        new YAHOO.example.DDPreset("ddProfileDetails");
        new YAHOO.example.DDPreset("ddProfileDetailsAT");
        new YAHOO.example.DDPreset("ddMyPhotos");
        new YAHOO.example.DDPreset("ddRSSFeed");
        new YAHOO.example.DDPreset("ddMyGroups");
        new YAHOO.example.DDPreset("ddMyDocuments");
        new YAHOO.example.DDPreset("ddGroupDocuments");
        new YAHOO.example.DDPreset("ddFamilyDiscussions");
        new YAHOO.example.DDPreset("ddMyDiscussions");
        //new YAHOO.example.DDPreset("ddProfileHeadline");
        //new YAHOO.example.DDPreset("ddProfileIntroduction");
        new YAHOO.example.DDPreset("ddGroupHeadline");
        new YAHOO.example.DDPreset("ddGroupDescription");
        new YAHOO.example.DDPreset("ddGroupPhotos");
        new YAHOO.example.DDPreset("ddMyLists");
        new YAHOO.example.DDPreset("ddGroupLists");
        new YAHOO.example.DDPreset("ddFamilyLists");
        new YAHOO.example.DDPreset("ddGroupImage");
        new YAHOO.example.DDPreset("ddMyFriends");
        new YAHOO.example.DDPreset("ddGroupMembers");
        new YAHOO.example.DDPreset("ddFamilyMemberIndex");
        new YAHOO.example.DDPreset("ddFamilyIcons");
        new YAHOO.example.DDPreset("ddGroupFamilyIcons");
        new YAHOO.example.DDPreset("ddImage");
        new YAHOO.example.DDPreset("ddMyEvents");
        new YAHOO.example.DDPreset("ddGroupEvents");
        new YAHOO.example.DDPreset("ddFamilyEvents");
        new YAHOO.example.DDPreset("ddGroupAvatar");
        new YAHOO.example.DDPreset("ddFamilyAvatar");
        
        new YAHOO.example.DDPreset("ddMyVideos");
        new YAHOO.example.DDPreset("ddGroupVideos");
        new YAHOO.example.DDPreset("ddFamilyVideos");
        new YAHOO.example.DDPreset("ddFamilyVideoContentBlock");
        new YAHOO.example.DDPreset("ddMyVideoContentBlock");
        new YAHOO.example.DDPreset("ddFamilyPeople");
        new YAHOO.example.DDPreset("ddFamilyTopVideos");
        
        new YAHOO.example.DDPreset("ddMogulus");
        new YAHOO.example.DDPreset("ddIframe");
        new YAHOO.example.DDPreset("ddScript");
        new YAHOO.example.DDPreset("ddElectedOfficial");
        new YAHOO.example.DDPreset("ddRoundInfo");
        new YAHOO.example.DDPreset("ddRoundEvents");
        
        new YAHOO.example.DDPreset("ddFamilyWidgetMap");
        new YAHOO.example.DDPreset("ddGroupWidgetMap");
        
        var DDT = new YAHOO.example.DDList('ddTarget1','group1',{scroll: false});
        DDT.setHandleElId("leftTargetHandle");
        //DDT.addInvalidHandleId('ddTarget1');
        
        var DDT2 = new YAHOO.example.DDList('ddTarget2','group1',{scroll: false});
        DDT2.setHandleElId("rightTargetHandle");
        //DDT.addInvalidHandleId('ddTarget2');
        
        this.ddPresetDiv.id = "ddPreset";
        //this.ddPresetDiv.className = "content-block-div";
        
        
        Dom.setStyle(this.ddPresetDiv, "border", "1px solid gray");
        Dom.setStyle(this.ddPresetDiv, "height", "10px");
        Dom.setStyle(this.ddPresetDiv, "font-size", "0px");
        Dom.setStyle(this.ddPresetDiv, "margin-bottom", "10px");
        Dom.setStyle(this.ddPresetDiv, "background", "#CCCCCC");
        //new YAHOO.util.DDTarget("ddPreset");
        
        if (document.getElementById("DD_entity_id")) {
            xajax_content_objects_load_from_db(document.getElementById("DD_entity_id").value);
        }
    },
    
    //---------------------------------------------------------------------------------------------------------
    //  Creates new HTML Object (div with frame)
    //---------------------------------------------------------------------------------------------------------
    createHTMLObject : function(contentType, targetId, ignoredForDrag)
    {
        DDCBlockFactory.clone(targetId,contentType);
        
        var newObj = document.createElement("div");
        Dom.setStyle(newObj, 'margin-bottom', '10px');
        newObj.id = "ddContentObject"+this.index;
        newObj.targetID = targetId;
        this.index++;
                
        //depricated in core 3
        //newObj.style.width = getBlockWidth(targetId);
        newObj.style.width = "100%";

  
   //     if (parseInt(newObj.style.width) < 200) 
    //    {
    //        newObj.className = "prGrayBorder znWidget0-conarrow";
    //    }
        
        newObj.className = "prContentObjectComposeMode";

        /*zanby3*/
        newInnerHTML = Dom.get("COContainer").innerHTML;
        newInnerHTML = newInnerHTML.replace (/\{\$newObjectID\}/gi, newObj.id);
        newInnerHTML = newInnerHTML.replace (/\{\$newObjectTitle\}/gi, getBlockTitle(contentType));

        newObj.innerHTML = newInnerHTML;
        //Dom.setStyle(newObj, "border", "1px solid gray");
        Dom.setStyle(newObj, 'z-index', '10000');
        //Dom.setStyle(newObj, 'className', 'nodragndrop');
        
        //INSERT
        if (!WarecorpDDblockApp.targetToInsert){
            Dom.get(targetId).appendChild(newObj);
        } else {
            tti = Dom.get(WarecorpDDblockApp.targetToInsert);
            
            if (WarecorpDDblockApp.targetToInsertAfter){
                Dom.get(targetId).insertBefore(newObj, tti.nextSibling);
            } else {
                Dom.get(targetId).insertBefore(newObj, tti);
            }
        }


        var DD = new YAHOO.example.DDList(newObj.id,'',{scroll: false});
        
        //DD.setHandleElId(newObj.id+'_title');
        DD.setHandleElId('cb-header-'+newObj.id);
        DDM.refreshCache();
        
        this.changeTitleByTargetId(newObj.id, targetId);
        this.saveOrder();
            
        return newObj;
    },
    
    //------------------------------------------------------------------------------------------------
    updateProfilesAT : function(id) {return WarecorpDDblockApp.updateProfiles(id)},
    updateProfiles : function(id) {

        if ( this.contentObjects.length > 0 ) {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) {
                if ( this.contentObjects[i].contentType == 'ddProfileDetails' ||  this.contentObjects[i].contentType == 'ddProfileDetailsAT') {
                    document.getElementById('cb-content-'+this.contentObjects[i].ID).innerHTML='<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
                    xajax_get_block_content(this.contentObjects[i].targetID,this.contentObjects[i].ID,'cb-content-'+this.contentObjects[i].ID,this.contentObjects[i].contentType,this.contentObjects[i].editMode,this.contentObjects[i].getParams());
                }
            }
        }
    },
    //------------------------------------------------------------------------------------------------
    updatePictures : function() {
    	
        if ( this.contentObjects.length > 0 ) {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) {
                if ( this.contentObjects[i].contentType == 'ddPicture' ) {
                    tmpElement = this.getObjByID(this.contentObjects[i].ID);
                    if (tmpElement.editMode) {
                        xajax_get_block_content_light(tmpElement.targetID,this.contentObjects[i].ID,'light_'+this.contentObjects[i].ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
                    } else {
                    	xajax_get_block_content(tmpElement.targetID,this.contentObjects[i].ID,'cb-content-'+this.contentObjects[i].ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
                    }
                }
            }
        }
    
    },
    //------------------------------------------------------------------------------------------------
    updateGroupAvatars : function() {

        if ( this.contentObjects.length > 0 ) {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) {
                if ( this.contentObjects[i].contentType == 'ddGroupAvatar' || this.contentObjects[i].contentType == 'ddFamilyAvatar' ) {
                    tmpElement = this.getObjByID(this.contentObjects[i].ID);
                    if (tmpElement.editMode) {
                        xajax_get_block_content_light(tmpElement.targetID,this.contentObjects[i].ID,'light_'+this.contentObjects[i].ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
                    } else {
                    	xajax_get_block_content(tmpElement.targetID,this.contentObjects[i].ID,'cb-content-'+this.contentObjects[i].ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
                    }
                }
            }
        }
    },
    //--------------------------------------------
    saveOrder : function() {
        for (var targetNum = 1; targetNum<=2; targetNum++) {
            var targetEl = document.getElementById('ddTarget'+targetNum);
            var targetNodes=targetEl.childNodes;
            var tmpCounter = 1;
            
            for(var i = 0; i < targetNodes.length; i++) {
                
                var currentElement = this.getObjByID(targetNodes[i].id);
                if (currentElement) {
                    currentElement.positionHorizontal = targetNum;
                    currentElement.positionVertical = tmpCounter;
                    tmpCounter++;
                }
            }
        }
    },
    //--------------------------------------------
    elementsCountByTarget : function(targetId) {
        var targetEl = document.getElementById(targetId);
        var targetNodes=targetEl.childNodes;
        var tmpCounter = 0;
        
        for(var i = 0; i < targetNodes.length; i++) {
            var currentElement = this.getObjByID(targetNodes[i].id);
            if (currentElement) {
                tmpCounter++;
            }
        }
        
        return tmpCounter;
    },
    //---------------------------------------------------------------------------------------------------------
    //  Reset Apply Cancel Edit
    //---------------------------------------------------------------------------------------------------------
    //Reset
    resetEditMode : function(elementId) {
        hideAllAKPopups();
        tmpElement = this.getObjByID(elementId);
        tmpElement.editMode = 1;
        tmpElement.resetEditMode();
        xajax_get_block_content(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode);

    },
    
    //Edit
    setEditMode : function(elementId) {
        hideAllAKPopups();
        tmpElement = this.getObjByID(elementId);
        tmpElement.editMode = 1;
        tmpElement.backupParams();
        tmpElement.setEditMode();
        
        xajax_get_block_content(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
    },

    //Apply
    applyEditMode : function(elementId) {
        hideAllAKPopups();
        tmpElement = this.getObjByID(elementId);
        tmpElement.editMode = 0;
        
        if (tmpElement.contentType != 'ddProfileDetails' && tmpElement.contentType != 'ddProfileDetailsAT') {
            tmpElement.applyEditMode();
            xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
        } else if (tmpElement.haveGlobalChanges()) {
            tmpElement.applyEditMode();
            document.getElementById('cb-content-'+elementId).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
            WarecorpDDblockApp.save();
        } else {
            tmpElement.applyEditMode();// TODO эта строка добавлена для того чтобы сохранялся хеадлайн раньше ее тут небыло, теперь в любом случае будет вызываться перерисовка всех блоков этого типа на странице
            xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
        }
    },
    
    //Cancel
    cancelEditMode : function(elementId) {
        hideAllAKPopups();
        tmpElement = this.getObjByID(elementId);
        tmpElement.editMode = 0;
        tmpElement.restoreParams();
        tmpElement.cancelEditMode();
        xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
    },
    
    //Redraw
    redrawElement : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        if (!tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1)){
            
            tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
            //tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
        }
        xajax_get_block_content(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
        
    },
    
    redrawElementLight : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        xajax_get_block_content_light(tmpElement.targetID,elementId,'light_'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
    },
    
    // --- STYLES -------------------------------------------------------------------------------------------------------------------------------------
    refreshStyles : function(elementId)
    {
        tmpElement = this.getObjByID(elementId);
        domObj = document.getElementById(elementId);
        
        //tmpElement.contentType
        
        if (tmpElement.backgroundColor == 'undefined') {tmpElement.backgroundColor = ''}
        if (tmpElement.borderColor == 'undefined') {tmpElement.borderColor = ''}
        if (tmpElement.borderStyle == 'undefined') {tmpElement.borderStyle = ''}
        
        if ( !(tmpElement.contentType == 'ddContentBlock' && tmpElement.editMode) ){
            Dom.setStyle(domObj, 'background-color', tmpElement.backgroundColor);
            Dom.setStyle(domObj, 'border-color', tmpElement.borderColor);
            Dom.setStyle(domObj, 'border-style', tmpElement.borderStyle);
        } else {
            Dom.setStyle(domObj, 'border-color', '#CFCFCF');
            Dom.setStyle(domObj, 'border-style', 'solid');
        }
        
        
        if(document.getElementById(elementId+'CP2_indicator')){
            if (tmpElement.borderColor) {
                document.getElementById(elementId+'CP2_indicator').style.backgroundColor=tmpElement.borderColor;
            }else {
                document.getElementById(elementId+'CP2_indicator').style.backgroundColor='#666666';
            }
        }
        
        if (tmpElement.editMode && !(tmpElement.headlineAbsent && tmpElement.contentType != 'ddContentBlock') &&   !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1) &&  !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1) &&  !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1) ){
            
            if (tmpElement.backgroundColor){
                if (tmpElement.contentType == 'ddFamilyVideoContentBlock'){
                    applyThemeToTinyMCE2(tmpElement.mceEditorID2, tmpElement.backgroundColor, '', '',tmpElement.mceEditorID);
                } else
                applyThemeToTinyMCE(tmpElement.mceEditorID, tmpElement.backgroundColor, '', '');
                
                if(document.getElementById(elementId+'CP1_indicator')){
                    document.getElementById(elementId+'CP1_indicator').style.backgroundColor=tmpElement.backgroundColor;
                }
            }else {
                if (tmpElement.contentType == 'ddFamilyVideoContentBlock'){
                    applyThemeToTinyMCE2(tmpElement.mceEditorID2, tmpElement.mceEditorBC, '', '',tmpElement.mceEditorID);
                } else
                applyThemeToTinyMCE(tmpElement.mceEditorID, tmpElement.mceEditorBC, '', '');
                
                
                if(document.getElementById(elementId+'CP1_indicator')){
                    document.getElementById(elementId+'CP1_indicator').style.backgroundColor='#FFFFFF';
                }
            }
            
            
            if (tmpElement.contentType == 'ddContentBlock'){
                document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID).style.borderWidth = '1px';
                document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID).style.backgroundColor = tmpElement.backgroundColor;
                if (tmpElement.borderColor) {document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID).style.borderColor = tmpElement.borderColor;}
                if (tmpElement.borderStyle) {document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID).style.borderStyle = tmpElement.borderStyle;}
            }
            
            
            if (tmpElement.contentType == 'ddFamilyVideoContentBlock'){
                document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID2).style.borderWidth = '1px';
                //document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID2).style.backgroundColor = 'transparent';
                document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID2).style.backgroundColor = tmpElement.backgroundColor;
                //if (tmpElement.borderColor) {document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID2).style.borderColor = tmpElement.borderColor;}
                //if (tmpElement.borderStyle) {document.getElementById('bordel_mce_editor_'+tmpElement.mceEditorID2).style.borderStyle = tmpElement.borderStyle;}
            }
            
        }
           
        return;
    },

    //Set background
    setElementBackgroundColor : function(elementId, backgroundColor) {
        tmpElement = this.getObjByID(elementId);
        tmpElement.setBackgroundColor(backgroundColor);
        this.refreshStyles(elementId);
    },
    //Set border color
    setElementBorderColor : function(elementId, borderColor) {
        tmpElement = this.getObjByID(elementId);
        tmpElement.setBorderColor(borderColor);
        this.refreshStyles(elementId);
    },
    //Set border style
    setElementBorderStyle : function(elementId, borderStyle) {
        tmpElement = this.getObjByID(elementId);
        tmpElement.setBorderStyle(borderStyle);
        this.refreshStyles(elementId);
    },
    //Get background
    getElementBackgroundColor : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        return tmpElement.getBackgroundColor();
    },
    //Get border color
    getElementBorderColor : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        return tmpElement.getBorderColor();
    },
    //Get border style
    getElementBorderStyle : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        return tmpElement.getBorderStyle();
    },
    //Reset style
    resetElementStyle : function(elementId) {
        tmpElement = this.getObjByID(elementId);
        tmpElement.resetStyle();
        this.refreshStyles(elementId);
        if (!(tmpElement.headlineAbsent && tmpElement.contentType != 'ddContentBlock') && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1) && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1) && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1)){
            applyThemeToTinyMCE(tmpElement.mceEditorID, tmpElement.mceEditorBCD, '', '');
            //if (tmpElement.mceEditorID2) {applyThemeToTinyMCE(tmpElement.mceEditorID2, tmpElement.mceEditorBCD, '', '');}
        }
    },
    // --- /STYLES ------------------------------------------------------------------------------------------------------------------------------------
    
    
    
    //HEADLINE
    //--------------------------------------------
    setHeadline : function(elementId, sHeadline) {
        var tmpElement = this.getObjByID(elementId);
        tmpElement.setHeadline(sHeadline);
        return true;
    },
    
    
    //--------------------------------------------
    changeTarget : function(elementId, targetId) {
        var tmpElement = this.getObjByID(elementId);
        tmpElement.targetID = targetId;
    },
    
    //--------------------------------------------
    changeTitleByTargetId : function(elementId, targetId) {
        
        tmpElement = this.getObjByID(elementId);
        //var symCount = 9;
        
        //if ((tmpElement.contentType == "ddProfileHeadline") || (tmpElement.contentType == "ddProfileIntroduction") || (tmpElement.contentType == "ddGroupHeadline") || (tmpElement.contentType == "ddGroupDescription"))
        //{
    //      symCount = 19;  
        //}
    
        //var newTitle = getBlockTitle(tmpElement.contentType);
        //
        //if (targetId == 'ddTarget1' &&  newTitle.length > symCount)
        //{
        //  newTitle = newTitle.substr(0,symCount)+'...';
        //}
        
        //document.getElementById(tmpElement.ID+'_title').innerHTML = newTitle;
        
        //width
         //depricated in core 3
        //document.getElementById(tmpElement.ID).style.width = getBlockWidth(targetId);
        document.getElementById(tmpElement.ID).style.width = "100%";

        
// !!!  temporarily disabled in zcore3
      
/*  if (parseInt(document.getElementById(tmpElement.ID).style.width) < 200) 
        {            
            if (document.getElementById(tmpElement.ID).className.indexOf('prContentObject-narrow') == -1) {
                document.getElementById(tmpElement.ID).className = document.getElementById(tmpElement.ID).className + ' prContentObject-narrow';
            }
        }
        else
        {
            document.getElementById(tmpElement.ID).className = document.getElementById(tmpElement.ID).className.replace (/prContentObject-narrow/gi, ''); 
        }
*/

    },
    
    
    
    //--------------------------------------------
    save : function() {
            
        items = new Array();
        if ( this.contentObjects.length > 0 ) {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) {
                
                // возможно вставить проверку на то находиться ли блок в режиме редактирования, т.к 
                // сейчас при сохранении одного блока сохраняются также те которые находятся в режиме редактирования (ОБЩ�?Й БАГ!!!!!!!!!)
                
                var item = this.contentObjects[i].getParams();
                items[items.length] = item;
            }
        }
        if (document.getElementById("DD_entity_id")) {
            xajax_content_objects_save(items, document.getElementById("DD_entity_id").value);
        }
        
    },
    //--------------------------------------------
    removeItem : function(id) {
        hideAllAKPopups();
        var new_draggedObjects = new Array();
        
        if ( this.contentObjects.length > 0 ) 
        {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) 
            {
                if ( this.contentObjects[i].ID != id ) {
                    new_draggedObjects[new_draggedObjects.length] = this.contentObjects[i];
                }
                else
                {
                    //Remove tinyMCE control
                    if (this.contentObjects[i].contentType == 'ddContentBlock')
                    {
                        tinyMCEDeinit('tinyMCE_'+this.contentObjects[i].ID)
                    }
                    
                    //Remove ddScript Files
                    if (this.contentObjects[i].contentType == 'ddScript')
                    {
                        xajax_ddScript_remove_script_code(this.contentObjects[i].uniqueCode);
                    }
                    
                    //remove tinyMCE headline control (all blocks)
                    if (!tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1) ){
                        tinyMCEDeinit('tinyMCE_'+this.contentObjects[i].ID+'_H');
                    }
                    
                    
                    switch(this.contentObjects[i].targetID) {
                        case 'ddTarget1':
                            document.getElementById("ddTarget1").removeChild(document.getElementById(id));
                            break;
                        case 'ddTarget2':
                            document.getElementById("ddTarget2").removeChild(document.getElementById(id));
                            break;
                    }
                }
            }
            
            this.contentObjects = new_draggedObjects;
        }

        //smoothing
        WarecorpDDblockApp.ddTargetSmoothing();
        //SAVE
        WarecorpDDblockApp.save();
    },
    
    //---------------------------------------------------------------------------------------------------------
    //smoothing of ddTarget columns
    ddTargetSmoothing : function() 
    {
        var ddTarget1_obj = document.getElementById('ddTarget1');
        var ddTarget2_obj = document.getElementById('ddTarget2');
        
        Dom.setStyle(ddTarget1_obj, "height", "");
        Dom.setStyle(ddTarget2_obj, "height", "");
        if ( !tinyMCE.isIE ) {
            Dom.setStyle(ddTarget1_obj, "min-height", '730px');
            Dom.setStyle(ddTarget2_obj, "min-height", '730px');
        }
                
        if ( ddTarget1_obj.clientHeight > ddTarget2_obj.clientHeight) {
            if ( tinyMCE.isIE ) {
                Dom.setStyle(ddTarget2_obj, "height", ddTarget1_obj.clientHeight+'px');
            }
            else {
                Dom.setStyle(ddTarget2_obj, "min-height", ddTarget1_obj.clientHeight+'px');
            }
        }
        else {
            if ( tinyMCE.isIE ) {
                Dom.setStyle(ddTarget1_obj, "height", ddTarget2_obj.clientHeight+'px');
            }
            else {
                Dom.setStyle(ddTarget1_obj, "min-height", ddTarget2_obj.clientHeight+'px');
            }
        }
    },
    
    //---------------------------------------------------------------------------------------------------------
    //  Returns DD object by object id
    //---------------------------------------------------------------------------------------------------------
    getObjByID : function (ID) {
        if ( this.contentObjects.length > 0 ) {
            for ( var i = 0; i < this.contentObjects.length; i ++ ) {
                if ( this.contentObjects[i].ID == ID ) {
                    return this.contentObjects[i];
                }
            }
        }

    }
};

//===============================================================================
YAHOO.example.DDPreset = function(id, sGroup, config) {

//create small dragged elenment from left icons
//-------------------------------------------------------------------------------
    YAHOO.example.DDPreset.superclass.constructor.call(this, id, sGroup, config);


    var el = this.getDragEl();
    Dom.setStyle(el, "opacity", 0.67);

    this.goingUp = false;
    this.lastY = 0;
};
YAHOO.extend(YAHOO.example.DDPreset, YAHOO.util.DDProxy, {
    
    startDrag: function(x, y) {
        
        hideAllAKPopups();//!!!

        // make the proxy look like the source element
        var dragEl  = this.getDragEl();
        var clickEl = this.getEl();

        dragEl.innerHTML = clickEl.innerHTML;
        Dom.setStyle(dragEl, "border", "none");
        dragEl.contentType = clickEl.id;
        
        //
        WarecorpDDblockApp.targetToInsert = null;
        WarecorpDDblockApp.targetToInsertAfter = null;
    },

    onDragDrop: function(e, id) {
        if ( id == 'ddTarget1' || id == 'ddTarget2' ) { 
        	
        	// Create content object
            currentItem = this.getDragEl();
            var onlyWide = (currentItem.contentType == 'ddGroupWidgetMap' || currentItem.contentType == 'ddFamilyWidgetMap')?1:0;
            if (onlyWide && id == 'ddTarget1') return;
            var newObj = WarecorpDDblockApp.createHTMLObject(currentItem.contentType, id);
            // load and save
            xajax_get_block_content_than_save(newObj.targetID,newObj.id,'cb-content-'+newObj.id,currentItem.contentType);
        }
    },
    
    endDrag: function(e) {
        if ( Dom.get("ddPreset") && Dom.get("ddPreset").parentNode ) {
            Dom.get("ddPreset").parentNode.removeChild(Dom.get("ddPreset"));
        }
        //!!!!!!!!!!!
       /* var srcEl = this.getEl();
        var proxy = this.getDragEl();

        // Show the proxy element and animate it to the src element's location
        Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion(
            proxy, {
                points: {
                    to: Dom.getXY(srcEl)
                }
            },
            0.2,
            YAHOO.util.Easing.easeOut
        )
        var proxyid = proxy.id;
        var thisid = this.id;

        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                Dom.setStyle(proxyid, "visibility", "hidden");
                Dom.setStyle(thisid, "visibility", "");
            });
        a.animate();*/
        //!!!!!!!!!!!!!!!!
    },
    onDrag: function(e) {

        // Keep track of the direction of the drag for use during onDragOver
        var y = Event.getPageY(e);

        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }

        this.lastY = y;
    },
    
    
    onDragOver: function(e, id) {//R div insert 
    	var srcEl = this.getEl();
        var destEl = Dom.get(id);
        
        var tmpItem = this.getDragEl();
        var onlyWide = (tmpItem.contentType == 'ddGroupWidgetMap' || tmpItem.contentType == 'ddFamilyWidgetMap')?1:0;
        var narrow = (id == 'ddTarget1')?1:0;
		    
        if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,15) == "ddContentObject") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;
            var narrow = (p.id == 'ddTarget1')?1:0;
            
            if (!onlyWide || !narrow) {
	            if (this.goingUp) {
	                WarecorpDDblockApp.targetToInsert = id;
	                WarecorpDDblockApp.targetToInsertAfter = false;
	                p.insertBefore(WarecorpDDblockApp.ddPresetDiv, destEl); // insert above
	            } else {
	                WarecorpDDblockApp.targetToInsert = id;
	                WarecorpDDblockApp.targetToInsertAfter = true;
	                p.insertBefore(WarecorpDDblockApp.ddPresetDiv, destEl.nextSibling); // insert below
	            }
            } else {
            	if ( Dom.get("ddPreset") && Dom.get("ddPreset").parentNode ) {
                    Dom.get("ddPreset").parentNode.removeChild(Dom.get("ddPreset"));
                }
            }
            //WarecorpDDblockApp.ddTargetSmoothing();
            DDM.refreshCache();
        } else if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,8) == "ddTarget") { 
            if ( (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,9) == "ddTarget1" && WarecorpDDblockApp.ddPresetDiv.offsetWidth > 200 ) || WarecorpDDblockApp.ddPresetDiv.offsetWidth==0  || (parseInt(WarecorpDDblockApp.elementsCountByTarget(destEl.id.substr(0,9))) == 0)  || (!WarecorpDDblockApp.ddPresetDiv.parentNode) ||  (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,9) == "ddTarget2" && WarecorpDDblockApp.ddPresetDiv.offsetWidth <= 200)   ) {
            	if (!onlyWide || !narrow) {
                    destEl.appendChild(WarecorpDDblockApp.ddPresetDiv);
                    WarecorpDDblockApp.targetToInsert = null;
                }
            }
        }
    }
});
//---------------------------------------------------------------------------------------------------------
//  REORDERING OF CONTENT BLOCKS
//---------------------------------------------------------------------------------------------------------
YAHOO.example.DDList = function(id, sGroup, config) {

    YAHOO.example.DDList.superclass.constructor.call(this, id, sGroup, config);
    var el = this.getDragEl();
    Dom.setStyle(el, "opacity", 0.67);// The proxy is slightly transparent 
    this.goingUp = false;
    this.lastY = 0;
};

YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy, {

    startDrag: function(x, y) {//ВЫЗЫВАЕТСЯ ДЛЯ �?Е 1 РАЗ , ДЛЯ ФФ ПОСТОЯННО
        
		hideAllAKPopups();// !!!
        // make the proxy look like the source element 
        var clickEl = this.getEl();
        var dragEl  = this.getDragEl();
        var tmpElement = WarecorpDDblockApp.getObjByID(clickEl.id);
        
        tmpElement.prevTargetID = tmpElement.targetID; //store position before drag
        Dom.setStyle(clickEl, "visibility", "hidden"); //hide source
            
        //deinit headline tinyMce
        if (tmpElement.editMode == 1 && !tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1 ) ){
            tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
            tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
            document.getElementById('tinyMCE_'+tmpElement.ID+'_div_wait_H').style.display = "block";
            document.getElementById('tinyMCE_'+tmpElement.ID+'_div_H').style.visibility = "hidden";
        }
        
        dragEl.className = clickEl.className; //orange or gray (current mode) header

        //it is necessary to replace all ids and names to exclude double ids and names. it is critical for some browsers    
        if(tmpElement.contentType != "ddFamilyWidgetMap" && tmpElement.contentType != "ddGroupWidgetMap" && tmpElement.contentType != "ddScript") {//clear inner HTML for maps when dragging 
	        if(tmpElement.editMode != 1 || !tinyMCE.isIE) {
	            var tmpInnerHTML = clickEl.innerHTML;
	            tmpInnerHTML = tmpInnerHTML.replace(/id\=\"[\-_0-9a-zA-Z]+\"/gi, '');
	            tmpInnerHTML = tmpInnerHTML.replace(/id\=[\-_0-9a-zA-Z]+/gi, '');
	            dragEl.innerHTML = tmpInnerHTML;
	        } else {
	            var tmpInnerHTML = clickEl.innerHTML;
	            tmpInnerHTML = tmpInnerHTML.replace(/id\=\"[\-_0-9a-zA-Z]+\"/gi, '');
	            tmpInnerHTML = tmpInnerHTML.replace(/id\=[\-_0-9a-zA-Z]+/gi, '');
	            tmpInnerHTML = tmpInnerHTML.replace(/name\=/gi, 'crutch=');
	            dragEl.innerHTML = tmpInnerHTML;
	        }
        } else {
        	dragEl.innerHTML = '';
        }
        
        //Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
        Dom.setStyle(dragEl, "borderWidth", "1px");
        Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
        Dom.setStyle(dragEl, "borderColor", Dom.getStyle(clickEl, "borderColor"));
        Dom.setStyle(dragEl, "borderStyle", Dom.getStyle(clickEl, "borderStyle"));
        
        //Dom.setStyle(dragEl, "border", "1px solid gray");
        //Dom.setStyle(dragEl, "padding", "0px 0px 0px 0px");
        //Dom.setStyle(dragEl, "fontSize", "0.75em");
        Dom.setStyle(dragEl, "textAlign", "left");
        //Dom.setStyle(dragEl, "lineHeight", "normal");
   
        //!!!! todo повторяется неоднократно
    
    },
    endDrag: function(e) {

        var srcEl = this.getEl();
        var proxy = this.getDragEl();
        var tmpElement = WarecorpDDblockApp.getObjByID(srcEl.id);
        
        //if target column changed, we nust reload blocks with TinyMCE frames (tmpElement.contentType == "ddContentBlock"). ALL blocks always must be reloaded after implementation of tinyMCE headlines
        if (true) {
            // if block in edit mode we need hide textarea content
            if(tmpElement.contentType == "ddContentBlock" && tmpElement.editMode == 1)
            {
                document.getElementById('tinyMCE_'+tmpElement.ID+'_div').style.display = "none";
                document.getElementById('tinyMCE_'+tmpElement.ID+'_div_buttons').style.display = "none";
                document.getElementById('tinyMCE_'+tmpElement.ID+'_div_wait').innerHTML='<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
                document.getElementById('tinyMCE_'+tmpElement.ID+'_div_wait').style.display = "block";
                
                tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
                tmpElement.innerText = document.getElementById('tinyMCE_'+tmpElement.ID).value;
            }
            xajax_get_block_content(tmpElement.targetID,tmpElement.ID,'cb-content-'+tmpElement.ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams('jscontent'));
        } else {
            //restoring content
            document.getElementById('cb-content-'+srcEl.id).innerHTML = tmpElement.storeInnerHTML;
        }
        
        
        // Show the proxy element and animate it to the src element's location
        Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion(
            proxy, {
                points: {
                    to: Dom.getXY(srcEl)
                }
            },
            0.2,
            YAHOO.util.Easing.easeOut
        )
        var proxyid = proxy.id;
        var thisid = this.id;

        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                Dom.setStyle(proxyid, "visibility", "hidden");
                Dom.setStyle(thisid, "visibility", "");
            });
        
        a.animate();
        
        //Resave order of blocks
        WarecorpDDblockApp.saveOrder();
        
        //SAVE
        WarecorpDDblockApp.save();
    },
    
    
    onDragDrop: function(e, id) {
        if (id == 'ddTarget1' || id == 'ddTarget2' ) {
            var clickEl = this.getEl();
            
            var tmpItem = this.getDragEl();
            var onlyWide = (tmpItem.contentType == 'ddGroupWidgetMap' || tmpItem.contentType == 'ddFamilyWidgetMap')?1:0;
            if (onlyWide && id == 'ddTarget1') return;
            
            WarecorpDDblockApp.changeTarget(clickEl.id, id);
            WarecorpDDblockApp.changeTitleByTargetId(clickEl.id, id);
            
            tmpElement = WarecorpDDblockApp.getObjByID(clickEl.id);
            tmpElement.changeTarget(tmpElement.targetID);

            // The position of the cursor at the time of the drop (YAHOO.util.Point)
            var pt = DDM.interactionInfo.point;

            // The region occupied by the source element at the time of the drop
            var region = DDM.interactionInfo.sourceRegion;

            // Check to see if we are over the source element's location.  We will
            // append to the bottom of the list once we are sure it was a drop in
            // the negative space (the area of the list without any list items)
            if (!region.intersect(pt)) {
                var destEl = Dom.get(id);
                var destDD = DDM.getDDById(id);
                
				//-----------------------------------------
				//msq
				if (tmpElement.contentType == "ddContentBlock"  && tmpElement.editMode == 1 ) {
				    tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
				    tmpElement.innerText = document.getElementById('tinyMCE_'+tmpElement.ID).value;
				}
				//-----------------------------------------
                
                destEl.appendChild(this.getEl());
                destDD.isEmpty = false;
                DDM.refreshCache();
            }
        }
    },

    onDrag: function(e) {
    	// Keep track of the direction of the drag for use during onDragOver
        var y = Event.getPageY(e);

        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }

        this.lastY = y;
        
        //autoscroll
        if (y>(document.documentElement.scrollTop-20+YAHOO.util.Dom.getViewportHeight()) && !this.goingUp){
            window.scrollBy(0, 50);
        }
        if (y<20+document.documentElement.scrollTop && this.goingUp){
            window.scrollBy(0, -50);
        }
    },

    onDragOver: function(e, id) {
        
        var srcEl = this.getEl();
        var destEl = Dom.get(id);

//      document.getElementById('aass').value = id;
        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
        
        if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,15) == "ddContentObject") {
	        var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;
            
            //--
			var tmpElement = WarecorpDDblockApp.getObjByID(srcEl.id);
			if (tmpElement.contentType == "ddContentBlock" && tmpElement.editMode == 1)
			{
			    tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
			    tmpElement.innerText = document.getElementById('tinyMCE_'+tmpElement.ID).value;
			    document.getElementById('tinyMCE_'+tmpElement.ID).style.width = "155px";
			    document.getElementById('tinyMCE_'+tmpElement.ID+'_div').style.width = "155px";
			}
            //--
			var narrow = (p.id == 'ddTarget1')?1:0;
			if (this.goingUp) {
                if (!tmpElement.onlyWide || !narrow) {
					p.insertBefore(srcEl, destEl); // insert above
	                WarecorpDDblockApp.changeTarget(srcEl.id, p.id);
	                WarecorpDDblockApp.changeTitleByTargetId(srcEl.id, p.id);
                }
            } else {
                if (!tmpElement.onlyWide || !narrow) {
	            	p.insertBefore(srcEl, destEl.nextSibling); // insert below
	                WarecorpDDblockApp.changeTarget(srcEl.id, p.id);
	                WarecorpDDblockApp.changeTitleByTargetId(srcEl.id, p.id);
                }
            }

            DDM.refreshCache();
        }
    }
        
});
Event.onDOMReady(WarecorpDDblockApp.init, WarecorpDDblockApp, true);
})();

function setFormValues (id, value) {
     document.getElementById(id).value = value;
}
