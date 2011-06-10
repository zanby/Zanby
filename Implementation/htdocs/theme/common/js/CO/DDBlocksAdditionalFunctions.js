/*
 *	@author Alexander Komarovski 
 */

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
			WarecorpDDblockApp.applyEditMode(objId);
		}
	return false;
}
function applyEditMode2(objId) {
	showViewModeButtons(objId);
	WarecorpDDblockApp.applyEditMode(objId);
			
	return false;
}
//show edit mode buttons
function showEditModeButtons(elementId) {
	if (document.getElementById(elementId+'_edit_mode_buttons')) { document.getElementById(elementId+'_edit_mode_buttons').style.display = "block"; }
	if (document.getElementById(elementId+'_view_mode_buttons')) { document.getElementById(elementId+'_view_mode_buttons').style.display = "none"; }
	if (document.getElementById('cb-header-'+elementId)) { document.getElementById('cb-header-'+elementId).className = "prCO-headline"; }
	return false;
}
//show view mode buttons
function showViewModeButtons(elementId) {
	if (document.getElementById(elementId+'_edit_mode_buttons')) { document.getElementById(elementId+'_edit_mode_buttons').style.display = "none"; }
	if (document.getElementById(elementId+'_view_mode_buttons')) { document.getElementById(elementId+'_view_mode_buttons').style.display = "block"; }
	if (document.getElementById('cb-header-'+elementId)) { document.getElementById('cb-header-'+elementId).className = "prCO-headline prCO-headline-view-mode"; }
	return false;
}

//returns block target
function getBlockTarget(objId) {
    tmpElement = WarecorpDDblockApp.getObjByID(objId);
    return tmpElement.targetID;
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
		case 'ddGroupAvatar': 				blockTitle = 'Group Photo';             break;
        case 'ddFamilyAvatar': 				blockTitle = 'Group Family Photo';      break;
		case 'ddFamilyWidgetMap': 			blockTitle = 'Family Map';				break;
		case 'ddGroupWidgetMap': 			blockTitle = 'Group Map';				break;
		case 'ddElectedOfficial':           blockTitle = 'Elected&nbsp;officials';  break;
        case 'ddRoundInfo':                 blockTitle = 'Round Info';              break;
        case 'ddRoundEvents':               blockTitle = 'Map of Events';           break;
	}
	
	return blockTitle;
}
