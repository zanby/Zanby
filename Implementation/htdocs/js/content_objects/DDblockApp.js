function applyThemeToTinyMCE(cTMCEID, bcolor, fcolor, fstyle){
    iframe = frames['mce_editor_'+cTMCEID]; 
	if (!iframe.document) return;
	if (bcolor){ setTimeout("iframe.document.body.style.backgroundColor='"+bcolor+"'",1); }
	if (fcolor){ setTimeout("iframe.document.body.style.color='"+fcolor+"'",1); }
	if (fstyle){ setTimeout("iframe.document.body.style.fontFamily='"+fstyle+"'",1); }
}
function applyThemeToTinyMCE2(cTMCEID, bcolor, fcolor, fstyle,cTMCEID2){
    iframe = frames['mce_editor_'+cTMCEID]; 
	if (!iframe.document) return;
	if (bcolor){ setTimeout("iframe.document.body.style.backgroundColor='"+bcolor+"'",1); }
	if (fcolor){ setTimeout("iframe.document.body.style.color='"+fcolor+"'",1); }
	if (fstyle){ setTimeout("iframe.document.body.style.fontFamily='"+fstyle+"'",1); }
	setTimeout("applyThemeToTinyMCE('"+cTMCEID2+"', '"+bcolor+"', '"+fcolor+"', '"+fstyle+"');",1);
}
//Enable edit mode
function setEditMode(objId) {
	showEditModeButtons(objId);
	document.getElementById(objId).className = "prGrayBorder";
    if (parseInt(document.getElementById(objId).style.width) < 200) { document.getElementById(objId).className = 'prGrayBorder znWidget0-conarrow'; }
	WarecorpDDblockApp.setEditMode(objId);
	return false;
}
//Reset edit mode
function resetEditMode(objId) {
	WarecorpDDblockApp.resetEditMode(objId);
	return false;
}
//Disable edit mode and enable simple mode
function cancelEditMode(objId) {
	showViewModeButtons(objId);
	document.getElementById(objId).className = "prGrayBorder";
    if (parseInt(document.getElementById(objId).style.width) < 200) { document.getElementById(objId).className = 'prGrayBorder znWidget0-conarrow'; }
	WarecorpDDblockApp.cancelEditMode(objId);
	return false;
}
//Disable edit mode, apply and enable simple mode
function applyEditMode(objId) {
	tmpElement = WarecorpDDblockApp.getObjByID(objId);
		if (tmpElement.contentType == 'ddScript') {
			tmpElement.saveScriptCode();
		} else {
	showViewModeButtons(objId);
	document.getElementById(objId).className = "prGrayBorder";
    if (parseInt(document.getElementById(objId).style.width) < 200) { document.getElementById(objId).className = 'prGrayBorder znWidget0-conarrow'; }
	WarecorpDDblockApp.applyEditMode(objId);
}
	return false;
}
function applyEditMode2(objId) 
{
			showViewModeButtons(objId);
			//document.getElementById(objId).className = "prContentObject prContentObject-fixed";
			if (parseInt(document.getElementById(objId).style.width) < 200) 
			{
				//document.getElementById(objId).className = 'prContentObject prContentObject-fixed prContentObject-narrow';
			}
			WarecorpDDblockApp.applyEditMode(objId);
			
	return false;
}
//show edit mode buttons
function showEditModeButtons(elementId) {
	if (document.getElementById(elementId+'_edit_mode_buttons')) { document.getElementById(elementId+'_edit_mode_buttons').style.display = "block"; }
	if (document.getElementById(elementId+'_view_mode_buttons')) { document.getElementById(elementId+'_view_mode_buttons').style.display = "none"; }
	if (document.getElementById('cb-header-'+elementId)) { document.getElementById('cb-header-'+elementId).className = "prCO-headline prClr3"; }
	return false;
}
//show view mode buttons
function showViewModeButtons(elementId) {
	if (document.getElementById(elementId+'_edit_mode_buttons')) { document.getElementById(elementId+'_edit_mode_buttons').style.display = "none"; }
	if (document.getElementById(elementId+'_view_mode_buttons')) { document.getElementById(elementId+'_view_mode_buttons').style.display = "block"; }
	if (document.getElementById('cb-header-'+elementId)) { document.getElementById('cb-header-'+elementId).className = "prCO-headline prCO-headline-view prClr3"; }
	return false;
}
//returns block width
function getBlockWidth(targetId) {
	if (targetId == 'ddTarget1') { return "223px"; }
	else { return "547px"; }
}
// Returns title for dragable window
function getBlockTitle(blockType) {
	var blockTitle = 'Content Block';
	switch(blockType) {
		case 'ddPicture': 					blockTitle = 'Profile Photo';			break;
		case 'ddPhotoGalleries': 			blockTitle = 'Photo Galleries';			break;
		case 'ddProfileDetails':			blockTitle = 'Profile Details';			break;
		case 'ddProfileDetailsAT':			blockTitle = 'Profile Details';			break;
		case 'ddMyDocuments':				blockTitle = 'My Documents';			break;
		case 'ddGroupDocuments':			blockTitle = 'Group Documents';			break;
		case 'ddProfileHeadline':			blockTitle = 'Profile Headline';		break;
		case 'ddMyPhotos':					blockTitle = 'My Photos';				break;
		case 'ddMyLists':					blockTitle = 'My Lists';				break;
		case 'ddGroupLists':				blockTitle = 'Group Lists';				break;
		case 'ddFamilyLists':				blockTitle = 'Family Lists';			break;
		case 'ddRSSFeed':					blockTitle = 'RSS Feed';				break;
		case 'ddMyGroups':					blockTitle = 'My Groups';				break;
		case 'ddFriends':					blockTitle = 'Friends';					break;
		case 'ddMessages':					blockTitle = 'Messages';				break;
		case 'ddFamilyDiscussions':			blockTitle = 'Discussions';				break;
		case 'ddFamilyIcons':				blockTitle = 'Family Icons';			break;
		case 'ddGroupFamilyIcons':			blockTitle = 'Family Icons';			break;
		case 'ddMyDiscussions':				blockTitle = 'Discussions';				break;
		case 'ddFamilyMemberIndex':			blockTitle = 'Family Members';			break;
		case 'ddProfileIntroduction':		blockTitle = 'Profile Introduction';	break;
		//case 'ddGroupHeadline':				blockTitle = 'Group Headline';			break;
		//case 'ddGroupDescription':			blockTitle = 'Description';				break;
		case 'ddGroupPhotos':				blockTitle = 'Group Galleries';			break;
		case 'ddMyLists':					blockTitle = 'My Lists';				break;
		case 'ddGroupImage':				blockTitle = 'Picture';					break;
		case 'ddMyFriends':					blockTitle = 'Friends';					break;
		case 'ddGroupMembers':				blockTitle = 'Members';					break;
		case 'ddImage':						blockTitle = 'Picture';					break;
		case 'ddMyEvents':					blockTitle = 'My Events';				break;
		case 'ddGroupEvents':				blockTitle = 'Group Events';			break;
		case 'ddFamilyEvents':				blockTitle = 'Family Events';			break;
		case 'ddMyVideos':					blockTitle = 'My Videos';				break;
		case 'ddGroupVideos':				blockTitle = 'Group Videos';			break;
		case 'ddFamilyVideos':				blockTitle = 'Family Videos';			break;
		case 'ddFamilyVideoContentBlock':	blockTitle = 'Single Video';			break;
		case 'ddMyVideoContentBlock':		blockTitle = 'Single Video';			break;
		case 'ddFamilyPeople':				blockTitle = 'Family People';			break;
		case 'ddFamilyTopVideos':			blockTitle = 'Top Videos';			    break;
		
		case 'ddMogulus':					blockTitle = 'LiveStream Video';	    break;
		case 'ddIframe':					blockTitle = 'Iframe';			    	break;
		case 'ddScript':					blockTitle = 'Script';			    	break;
		
		case 'ddGroupAvatar': 				blockTitle = 'Profile Photo';			break;
        case 'ddElectedOfficial':           blockTitle = 'Elected&nbsp;officials';  break;
	}
	
	return blockTitle;
}
//===============================================================================
// STARTUP
//===============================================================================
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
		
		
	
		
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		var DDT = new YAHOO.example.DDList('ddTarget1','group1',{scroll: false});
		DDT.setHandleElId("leftTargetHandle");
		//DDT.addInvalidHandleId('ddTarget1');
		
	 	var DDT2 = new YAHOO.example.DDList('ddTarget2','group1',{scroll: false});
		DDT2.setHandleElId("rightTargetHandle");
		//DDT.addInvalidHandleId('ddTarget2');
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		 
		
		
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
	//	Creates new HTML Object (div with frame)
	//---------------------------------------------------------------------------------------------------------
	createHTMLObject : function(contentType, targetId, ignoredForDrag)
	{
		DDCBlockFactory.clone(targetId,contentType);
		
		var newObj = document.createElement("div");
		Dom.setStyle(newObj, 'margin-bottom', '10px');
		newObj.id = "ddContentObject"+this.index;
		newObj.targetID = targetId;
		this.index++;
				
		newObj.className = "prGrayBorder";
		newObj.style.width = getBlockWidth(targetId);
		
		if (parseInt(newObj.style.width) < 200) 
        {
            newObj.className = "prGrayBorder znWidget0-conarrow";
        }
        
        newInnerHTML = '<div id="cb-header-'+newObj.id+'" class="prCO-headline prCO-headline-view prClr3">'+
							'<h4 id="'+newObj.id+'_title" class="prFloatLeft">'+getBlockTitle(contentType)+'</h4>'+
								'<div class="prHeaderTools" id="'+newObj.id+'_view_mode_buttons" style="display:none;">'+
									'<span>';
										
                                       //  if (contentType != 'ddElectedOfficial') {
                                            newInnerHTML = newInnerHTML + '<a class="znCO-edit" href="#" onclick="setEditMode(\''+newObj.id+'\'); return false;" title="Edit">&nbsp;</a>\n';
                                            newInnerHTML = newInnerHTML + '<a class="znCO-close" href="#" onclick="WarecorpDDblockApp.removeItem(\''+newObj.id+'\'); return false;" title="Delete">&nbsp;</a>';
                                        //}

									newInnerHTML = newInnerHTML + '</span>'+
								'</div>'+
										
								//buttons for edit mode
								'<div class="prHeaderTools" id="'+newObj.id+'_edit_mode_buttons" style="display:none;">'+
									'<span>'+
									
										'<a href="#null" onclick="applyEditMode(\''+newObj.id+'\'); return false;" class="prCO-save" title="Save">&nbsp;</a>\n'+
										'<a href="#null" onclick="cancelEditMode(\''+newObj.id+'\'); return false;" class="prCO-cancel" title="Cancel">&nbsp;</a>\n'+
										'<a href="#null" onclick="WarecorpDDblockApp.removeItem(\''+newObj.id+'\'); return false;" class="prCO-close" title="Delete">&nbsp;</a>'+
     								'</span>'+
								'</div>'+
						'</div>';
		
		newInnerHTML = newInnerHTML + '<div id="cb-content-'+newObj.id+'">'+
							//content here
							'<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>'+
					   	'</div>';
					   

		newObj.innerHTML = newInnerHTML;
		//Dom.setStyle(newObj, "border", "1px solid gray");
		Dom.setStyle(newObj, 'z-index', '10000');
		//Dom.setStyle(newObj, 'className', 'nodragndrop');
		
        //alert(WarecorpDDblockApp.targetToInsert);
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




		
		
		
		//alert (newObj.id);
		//var DD = new YAHOO.example.DDList(newObj.id,'group1',{scroll: false});
		var DD = new YAHOO.example.DDList(newObj.id,'',{scroll: false});
		
		//DD.setHandleElId(newObj.id+'_title');
		DD.setHandleElId('cb-header-'+newObj.id);
		DDM.refreshCache();
		
		this.changeTitleByTargetId(newObj.id, targetId);
		this.saveOrder();
			
		return newObj;
	},
	
	//------------------------------------------------------------------------------------------------
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
					
					//document.getElementById('cb-content-'+this.contentObjects[i].ID).innerHTML='<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
					//xajax_get_block_content(this.contentObjects[i].targetID,this.contentObjects[i].ID,'cb-content-'+this.contentObjects[i].ID,this.contentObjects[i].contentType,this.contentObjects[i].editMode,this.contentObjects[i].getParams());
					
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
				if ( this.contentObjects[i].contentType == 'ddGroupAvatar' ) {
					
					//document.getElementById('cb-content-'+this.contentObjects[i].ID).innerHTML='<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
					//xajax_get_block_content(this.contentObjects[i].targetID,this.contentObjects[i].ID,'cb-content-'+this.contentObjects[i].ID,this.contentObjects[i].contentType,this.contentObjects[i].editMode,this.contentObjects[i].getParams());
					
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
		for (var targetNum = 1; targetNum<=2; targetNum++)
		{
			var targetEl = document.getElementById('ddTarget'+targetNum);
			var targetNodes=targetEl.childNodes;
			var tmpCounter = 1;
			
			for(var i = 0; i < targetNodes.length; i++)
			{
				
				var currentElement = this.getObjByID(targetNodes[i].id);
				if (currentElement)
				{
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
		
		for(var i = 0; i < targetNodes.length; i++)
		{
			var currentElement = this.getObjByID(targetNodes[i].id);
			if (currentElement)
			{
				tmpCounter++;
			}
		}
		
		return tmpCounter;
	
		
	},
	//---------------------------------------------------------------------------------------------------------
	//	Reset Apply Cancel Edit
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
		
		if (tmpElement.contentType != 'ddProfileDetails' && tmpElement.contentType != 'ddProfileDetailsAT')
		{
			tmpElement.applyEditMode();
			xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
		}
		else if (tmpElement.haveGlobalChanges())
		{
			tmpElement.applyEditMode();
			document.getElementById('cb-content-'+elementId).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
			WarecorpDDblockApp.save();
		}
		else
		{
			tmpElement.applyEditMode();// TODO эта строка добавлена для того чтобы сохранялся хеадлайн раньше ее тут небыло, теперь в любом случае будет вызываться перерисовка всех блоков этого типа на странице
			xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
		}
		
		
		//document.getElementById('cb-content-'+elementId).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
		//SAVE
		//WarecorpDDblockApp.save();
	},
	
	//Cancel
	cancelEditMode : function(elementId) {
		hideAllAKPopups();
		tmpElement = this.getObjByID(elementId);
		tmpElement.editMode = 0;
		tmpElement.restoreParams();
		tmpElement.cancelEditMode();
		xajax_get_block_content_than_save(tmpElement.targetID,elementId,'cb-content-'+elementId,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams());
		
		
		//document.getElementById('cb-content-'+elementId).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
		//SAVE
		//WarecorpDDblockApp.save();
	},
	
	//Redraw
	redrawElement : function(elementId) {
		tmpElement = this.getObjByID(elementId);
		if (!tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1)){
			
			tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
			tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
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
		
		//alert(111);	
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
	//		symCount = 19;	
		//}
	
		//var newTitle = getBlockTitle(tmpElement.contentType);
		//
		//if (targetId == 'ddTarget1' &&  newTitle.length > symCount)
		//{
		//	newTitle = newTitle.substr(0,symCount)+'...';
		//}
		
		//document.getElementById(tmpElement.ID+'_title').innerHTML = newTitle;
		
		//width
		document.getElementById(tmpElement.ID).style.width = getBlockWidth(targetId);
        if (parseInt(document.getElementById(tmpElement.ID).style.width) < 200) 
        {            
			if (document.getElementById(tmpElement.ID).className.indexOf('prContentObject-narrow') == -1) {
				document.getElementById(tmpElement.ID).className = document.getElementById(tmpElement.ID).className + ' prContentObject-narrow';
			}
        }
        else
        {
            document.getElementById(tmpElement.ID).className = document.getElementById(tmpElement.ID).className.replace (/prContentObject-narrow/gi, ''); 
        }
	},
	
	
	
	//--------------------------------------------
	save : function() {
			
		items = new Array();
		if ( this.contentObjects.length > 0 ) {
			for ( var i = 0; i < this.contentObjects.length; i ++ ) {
				
				// возможно вставить проверку на то находиться ли блок в режиме редактирования, т.к 
				// сейчас при сохранении одного блока сохраняются также те которые находятся в режиме редактирования (ОБЩ?Й БАГ!!!!!!!!!)
				
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
	//	Returns DD object by object id
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

		//Dom.setStyle(clickEl, "width", "50px");

		//dragEl.innerHTML = '<div>'+clickEl.innerHTML+'</div>';
		dragEl.innerHTML = clickEl.innerHTML;
		Dom.setStyle(dragEl, "border", "none");
		dragEl.contentType = clickEl.id;
		
		//
		WarecorpDDblockApp.targetToInsert = null;
		WarecorpDDblockApp.targetToInsertAfter = null;
    },

    onDragDrop: function(e, id) {
	
		if ( id == 'ddTarget1' || id == 'ddTarget2' ) { 
		
			//alert (WarecorpDDblockApp.targetToInsert+"  "+WarecorpDDblockApp.targetToInsertAfter);
			// Create content object
			currentItem = this.getDragEl();
			var newObj = WarecorpDDblockApp.createHTMLObject(currentItem.contentType, id);
			// load and save
			xajax_get_block_content_than_save(newObj.targetID,newObj.id,'cb-content-'+newObj.id,currentItem.contentType);
        }
    },
	
    endDrag: function(e) {
        if ( Dom.get("ddPreset") && Dom.get("ddPreset").parentNode ) {//Red div
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
		
		//document.getElementById('aass').value = id;
		//document.getElementById('aass').value =WarecorpDDblockApp.ddPresetDiv.offsetWidth;//Dom.getStyle(WarecorpDDblockApp.ddPresetDiv, "width");//  WarecorpDDblockApp.elementsCountByTarget(1);//destEl.childNodes.length;//;/Dom.getStyle(WarecorpDDblockApp.ddPresetDiv, "width");
        if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,15) == "ddContentObject") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;

            if (this.goingUp) {
				WarecorpDDblockApp.targetToInsert = id;
				WarecorpDDblockApp.targetToInsertAfter = false;
                p.insertBefore(WarecorpDDblockApp.ddPresetDiv, destEl); // insert above
            } else {
				WarecorpDDblockApp.targetToInsert = id;
				WarecorpDDblockApp.targetToInsertAfter = true;
                p.insertBefore(WarecorpDDblockApp.ddPresetDiv, destEl.nextSibling); // insert below
            }
			//WarecorpDDblockApp.ddTargetSmoothing();
            DDM.refreshCache();
        } 
		
		else if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,8) == "ddTarget") 
		{
			//alert(WarecorpDDblockApp.ddPresetDiv.style.width);	
			if ( (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,9) == "ddTarget1" && WarecorpDDblockApp.ddPresetDiv.offsetWidth > 200 ) || WarecorpDDblockApp.ddPresetDiv.offsetWidth==0  || (parseInt(WarecorpDDblockApp.elementsCountByTarget(destEl.id.substr(0,9))) == 0)  || (!WarecorpDDblockApp.ddPresetDiv.parentNode) ||  (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,9) == "ddTarget2" && WarecorpDDblockApp.ddPresetDiv.offsetWidth <= 200)   ) {
			   // if ( !destEl.hasChildNodes() ) {
					destEl.appendChild(WarecorpDDblockApp.ddPresetDiv);
					WarecorpDDblockApp.targetToInsert = null;
			   // }
			}
		}
		
		
		
		
		
        //Dom.get("debug").innerHTML = destEl.id;
    }//,
//    onDragEnter: function(e, id) {
//        Dom.get("debug").innerHTML = id;
//    }
//    ,
//    onDragOut: function(e, id) {
//        Dom.get("debug1").innerHTML = id;
//    }
});
/**
*
**/ 














//---------------------------------------------------------------------------------------------------------
//	REORDERING OF CONTENT BLOCKS
//---------------------------------------------------------------------------------------------------------
YAHOO.example.DDList = function(id, sGroup, config) {

    YAHOO.example.DDList.superclass.constructor.call(this, id, sGroup, config);

    var el = this.getDragEl();
	// The proxy is slightly transparent
    Dom.setStyle(el, "opacity", 0.67); 

	this.goingUp = false;
    this.lastY = 0;
};

YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy, {

	startDrag: function(x, y) {//ВЫЗЫВАЕТСЯ ДЛЯ ?Е 1 РАЗ , ДЛЯ ФФ ПОСТОЯННО
		
		hideAllAKPopups();// !!!
        
	
		// make the proxy look like the source element 
		var clickEl = this.getEl();
		
		var dragEl  = this.getDragEl();
		
		//dragEl.innerHTML  = 'aaa';
		
		
		
		var tmpElement = WarecorpDDblockApp.getObjByID(clickEl.id);
		//store position before drag
		tmpElement.prevTargetID = tmpElement.targetID;
		
		//hide source
		
		Dom.setStyle(clickEl, "visibility", "hidden");
			
			
		//deinit headline tinyMce
		if (tmpElement.editMode == 1 && !tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1 ) ){
			tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
			tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
			
			document.getElementById('tinyMCE_'+tmpElement.ID+'_div_wait_H').style.display = "block";
            document.getElementById('tinyMCE_'+tmpElement.ID+'_div_H').style.visibility = "hidden";
		}
		//clickEl.innerHTML='';
	//	return; //only frame
		
		
		
		// orange or gray header
		dragEl.className = clickEl.className;
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!		
		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Прокомментировать	
		//if(tmpElement.contentType != "ddContentBlock" || tmpElement.editMode != 1 || !tinyMCE.isIE)  изменения в связи с добавлением тинимцешных хедлайнов
		if(tmpElement.editMode != 1 || !tinyMCE.isIE)
		{
			var tmpInnerHTML = clickEl.innerHTML;
			tmpInnerHTML = tmpInnerHTML.replace(/id\=\"[\-_0-9a-zA-Z]+\"/gi, '');
			tmpInnerHTML = tmpInnerHTML.replace(/id\=[\-_0-9a-zA-Z]+/gi, '');
			dragEl.innerHTML = tmpInnerHTML;
		}
		else
		{
			var tmpInnerHTML = clickEl.innerHTML;
			tmpInnerHTML = tmpInnerHTML.replace(/id\=\"[\-_0-9a-zA-Z]+\"/gi, '');
			tmpInnerHTML = tmpInnerHTML.replace(/id\=[\-_0-9a-zA-Z]+/gi, '');
			tmpInnerHTML = tmpInnerHTML.replace(/name\=/gi, 'crutch=');
			dragEl.innerHTML = tmpInnerHTML;
		}
		
        
		//Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
       
	   	Dom.setStyle(dragEl, "borderWidth", "1px");
		Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
		Dom.setStyle(dragEl, "borderColor", Dom.getStyle(clickEl, "borderColor"));
		Dom.setStyle(dragEl, "borderStyle", Dom.getStyle(clickEl, "borderStyle"));
		
	   //Dom.setStyle(dragEl, "border", "1px solid gray");
		//Dom.setStyle(dragEl, "padding", "0px 0px 0px 0px");
		Dom.setStyle(dragEl, "fontSize", "0.75em");
		Dom.setStyle(dragEl, "textAlign", "left");
		//Dom.setStyle(dragEl, "lineHeight", "normal");
   
   		//!!!! todo повторяется неоднократно
	
	
//------------------------------------------
// блок закомментирован при введении тинимцешных хедлайнов
		//store inner HTML
//  		if(tmpElement.contentType != "ddContentBlock" || tmpElement.editMode != 1)
//		{
//			tmpElement.storeInnerHTML = document.getElementById('cb-content-'+clickEl.id).innerHTML;
//			document.getElementById('cb-content-'+clickEl.id).innerHTML = '<div align="center"><img style="padding:5px;" src="'+AppTheme.images+'/decorators/waiting.gif" alt=""/></div>';
//		}
//------------------------------------------
		
    },


















    endDrag: function(e) {

		var srcEl = this.getEl();
		var proxy = this.getDragEl();
		var tmpElement = WarecorpDDblockApp.getObjByID(srcEl.id);
		
		//if target column changed, we nust reload content block. next blocks always must be reloaded: ddContentBlock
		// smotri novoe (headlines+) if (tmpElement.contentType == "ddContentBlock" || (tmpElement.prevTargetID && (tmpElement.prevTargetID != tmpElement.targetID) ))
		
		//if target column changed, we nust reload content block. ALL blocks always must be reloaded
		if (true)
		{
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
			
			//а это для хеадлайнов
			/*if (tmpElement.editMode == 1 && !tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1) ){
				tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
				tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
			}*///27032008
	
			xajax_get_block_content(tmpElement.targetID,tmpElement.ID,'cb-content-'+tmpElement.ID,tmpElement.contentType,tmpElement.editMode,tmpElement.getParams('jscontent'));
			
		}
		else
		{
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

			// If there is one drop interaction, the li was dropped either on the list,
			// or it was dropped on the current location of the source element.
			
			//if (DDM.interactionInfo.drop.length === 1) {   ????

				var clickEl = this.getEl();
				
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
if (tmpElement.contentType == "ddContentBlock"  && tmpElement.editMode == 1 )
{
	tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
	tmpElement.innerText = document.getElementById('tinyMCE_'+tmpElement.ID).value;
}

//а это для хеадлайнов

/*if (tmpElement.editMode == 1 && !tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1 ) && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1 ) ){
	tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
	tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
}*///27032008
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

//		document.getElementById('aass').value = id;
        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
		
        if (destEl.nodeName.toLowerCase() == "div" && destEl.id.substr(0,15) == "ddContentObject") {
          
		   
		   var orig_p = srcEl.parentNode;
           var p = destEl.parentNode;
			
//-----------------------------------------			
//msq 
var tmpElement = WarecorpDDblockApp.getObjByID(srcEl.id);
if (tmpElement.contentType == "ddContentBlock" && tmpElement.editMode == 1)
{
	tinyMCEDeinit('tinyMCE_'+tmpElement.ID);
	tmpElement.innerText = document.getElementById('tinyMCE_'+tmpElement.ID).value;
	document.getElementById('tinyMCE_'+tmpElement.ID).style.width = "155px";
	document.getElementById('tinyMCE_'+tmpElement.ID+'_div').style.width = "155px";
}
//а это для хеадлайнов
/*if (tmpElement.editMode == 1 && !tmpElement.headlineAbsent && !(tmpElement.contentType == 'ddMyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddFamilyLists' && tmpElement.listDisplayType == 1)  && !(tmpElement.contentType == 'ddGroupLists' && tmpElement.listDisplayType == 1) ){
	tinyMCEDeinit('tinyMCE_'+tmpElement.ID+'_H');
	tmpElement.headline = document.getElementById('tinyMCE_'+tmpElement.ID+'_H').value;
}*///27032008
//-----------------------------------------			
			
            if (this.goingUp) {
                p.insertBefore(srcEl, destEl); // insert above
				WarecorpDDblockApp.changeTarget(srcEl.id, p.id);
				WarecorpDDblockApp.changeTitleByTargetId(srcEl.id, p.id);
				
            } else {
                p.insertBefore(srcEl, destEl.nextSibling); // insert below
				WarecorpDDblockApp.changeTarget(srcEl.id, p.id);
				WarecorpDDblockApp.changeTitleByTargetId(srcEl.id, p.id);
				
            }

            DDM.refreshCache();
        }
    }
		
});

Event.onDOMReady(WarecorpDDblockApp.init, WarecorpDDblockApp, true);

})();
