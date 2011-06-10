{literal}
<style type="text/css">
        .tree-documents-folder-inactive {color: #000000; cursor:pointer; text-decoration: none;}
        .tree-documents-folder-active {color : #CB0000; cursor: pointer; text-decoration: none;}
        .ygtvitem table td {border: 0px;}
        #tree_div_0 {margin:10px 0px 0px 10px; overflow-x: auto; overflow-y: hidden; noscroll; width:175px; padding-bottom: 15px;}
    </style>
{/literal}
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/tree.css" media="screen" />
<script type="text/javascript" src = "{$JS_URL}/yui/treeview/treeview.js" ></script>
<script type="text/javascript" src="{$AppTheme->common->js}/jquery/menu/fg.menu.js"></script>
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->js}/jquery/drag_drop/jquery.drag.drop.css" media="screen" />
<script type="text/javascript" src="{$AppTheme->common->js}/jquery/drag_drop/jquery.event.drag-1.5.js"></script>
<script type="text/javascript" src="{$AppTheme->common->js}/jquery/drag_drop/jquery.event.drop-1.2.js"></script>
{assign var="canCreateOwnerDocuments" value=$AccessManager->canCreateOwnerDocuments($CurrentGroup, $currGroup, $user->getId())}
{assign var="canManageOwnerDocuments" value=$AccessManager->canManageOwnerDocuments($CurrentGroup, $currGroup, $user->getId())}

{literal}
<script type="text/javascript">
        var cfgDocumentApplication = null;
        if ( !cfgDocumentApplication ) {
            cfgDocumentApplication = function () {
                return {
                    currentOwnerId  : '{/literal}{$currGroup->getId()}{literal}',
					initDragDrop	: '{/literal}{if $canManageOwnerDocuments}1{else}0{/if}{literal}',
                    hAddFile        : '{/literal}{$CurrentGroup->getGroupPath("documentAdd")}{literal}',
                    hAddWeblink     : '{/literal}{$CurrentGroup->getGroupPath("documentAddWeblink")}{literal}',                
                    hCreateFolder   : '{/literal}{$CurrentGroup->getGroupPath("documentCreateFolder")}{literal}',
                    hCheckIn       	: '{/literal}{$CurrentGroup->getGroupPath("documentCheckIn")}{literal}',
                    hCheckOut       : '{/literal}{$CurrentGroup->getGroupPath("documentCheckOut")}{literal}',
                    hCancelCheckOut : '{/literal}{$CurrentGroup->getGroupPath("documentCancelCheckOut")}{literal}',            
                    hRevisions 		: '{/literal}{$CurrentGroup->getGroupPath("documentRevisions")}{literal}',
                    hRevertRevision : '{/literal}{$CurrentGroup->getGroupPath("documentRevertRevision")}{literal}',
                    hDeleteGroup    : '{/literal}{$CurrentGroup->getGroupPath("documentDeleteGroup")}{literal}',
                    hShare			: '{/literal}{$CurrentGroup->getGroupPath("documentShareFile")}{literal}',
                    hManageSharing	: '{/literal}{$CurrentGroup->getGroupPath("documentManageSharing")}{literal}',
                    hUnShare		: '{/literal}{$CurrentGroup->getGroupPath("documentUnshareFile")}{literal}',				
                    hEdit       	: '{/literal}{$CurrentGroup->getGroupPath("documentEdit")}{literal}',
                    hMoveGroup      : '{/literal}{$CurrentGroup->getGroupPath("documentMoveGroup")}{literal}',
                    hAddToMy      	: '{/literal}{$CurrentGroup->getGroupPath("documentAddToMy")}{literal}'
                }
            }();
        };    	
        function jsTree(){ {/literal}{$DocumentTreeJS};{literal} }
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/documents/init.js"></script>
<input type="hidden" name="current_order" id="current_order" value="title" />
<input type="hidden" name="current_direction" id="current_direction" value="asc" />
<ul class="prDocTools">
	<li menu="moreactions" class="prDocTools-Left" {if !$canManageOwnerDocuments && !$allFamilySharing}style="display:none;"{/if}><a href="javascript:void(0);" id="lnkMoreActions"><span class="prDocSelect">{t}More Actions{/t}</span></a>
		<div class="fg-menu-hidden-menu" id="menuCheckInOut">
			<ul>
				<li action="checkin" type="singl-action"><a href="javascript:void(0);" onclick="DocumentApplication.checkIn(); return false;">{t}Check In{/t}</a><span class="fg-menu-hidden-item">{t}Check In{/t}</span></li>
				<li action="checkout"><a href="javascript:void(0);" onclick="DocumentApplication.checkOut(); return false;">{t}Check Out{/t}</a><span class="fg-menu-hidden-item">{t}Check Out{/t}</span></li>
				<li action="cancelcheckout"><a href="javascript:void(0);" onclick="DocumentApplication.cancelCheckOut(); return false;">{t}Cancel Check Out{/t}</a><span class="fg-menu-hidden-item">{t}Cancel Check Out{/t}</span></li>
				<li action="revision" type="singl-action"><a href="javascript:void(0);" onclick="DocumentApplication.revisionHistory(); return false;">{t}Revision History{/t}</a><span class="fg-menu-hidden-item">{t}Revision History{/t}</span></li>
				<li action="share" type="singl-action"><a href="javascript:void(0);" onclick="DocumentApplication.shareDocument(); return false;">{t}Share{/t}</a><span class="fg-menu-hidden-item">{t}Share{/t}</span></li>
				<li action="unshare"><a href="javascript:void(0);" onclick="DocumentApplication.unShareDocument(); return false;">{t}Unshare{/t}</a><span class="fg-menu-hidden-item">{t}Unshare{/t}</span></li>
			</ul>
		</div>
	</li>
	<li menu="move-document" class="prDocTools-Right" {if !$canManageOwnerDocuments}style="display:none;"{/if} onclick="DocumentApplication.moveGroup(); return false;"><a href=""><img src="{$AppTheme->images}/documents/docMove.gif" class="prVMiddle" width="16" height="16" /> {t}Move{/t}</a></li>
	<li menu="delete-document" {if !$canManageOwnerDocuments}style="display:none;"{/if}  onclick="DocumentApplication.deleteGroup(); return false;"><a href=""><img src="{$AppTheme->images}/documents/docDelete.gif" class="prVMiddle" width="16" height="16" /> {t}Delete{/t}</a></li>
	<li menu="edit-document" class="prDocTools-Left" {if !$canManageOwnerDocuments}style="display:none;"{/if} onclick="DocumentApplication.editItem(); return false;"><a href=""><img src="{$AppTheme->images}/documents/docEdit.gif" class="prVMiddle" width="16" height="16" /> {t}Edit{/t}</a></li>
	<li menu="add-document" class="prDocTools-Right" {if !$canCreateOwnerDocuments}style="display:none;"{/if}><a href="javascript:void(0);" id="lnkAddDocument"><img src="{$AppTheme->images}/documents/docAddDoc.gif" class="prVMiddle" width="16" height="16" /> <span class="prDocSelect">{t}Add Document{/t}</span></a>
		<div class="fg-menu-hidden-menu">
			<ul>
				<li><a href="javascript:void(0);" onclick="DocumentApplication.addFile(); return false;">{t}From Computer{/t}</a></li>
				<li><a href="javascript:void(0);" onclick="DocumentApplication.addWeblink(); return false;">{t}From Web{/t}</a></li>
			</ul>
		</div>
	</li>
	<li menu="new-folder" {if !$canManageOwnerDocuments}style="display:none;"{/if}><a href="javascript:void(0);" onclick="DocumentApplication.createFolder(); return false;"><img src="{$AppTheme->images}/documents/docNewFolder.gif" class="prVMiddle" width="16" height="16" /> {t}New Folder{/t}</a></li>
	{*{if $user && $user->getId()}
	<li menu="new-folder"><a href="javascript:void(0);" onclick="DocumentApplication.addToMy(); return false;">{t}Add to My Documents{/t}</a></li>
	{/if} 
	
	<li menu="empty">&nbsp;</li>
	*}
</ul>
<table cellpadding="0" cellspacing="0" class="prTableDocs">
	<col width="25%" />
	<col width="38%" />
	<col width="20%" />
	<col width="17%" />
	<tr>
		<th class="prTableDocsthLeft"><div class="prFloatLeft">{t}Folders{/t}</div></th>
		<th id="sortTitle"> <input type="checkbox" class="prNoBorder prIndentRight" name="checkAll" id="checkAll" value="1" onclick="DocumentApplication.onCheckAll();" />
			<a href="javascript:void(0);" onclick="xajax_sort_files(DocumentApplication.currentOwnerId, DocumentApplication.currentFolderId, YAHOO.util.Dom.get('current_order').value, YAHOO.util.Dom.get('current_direction').value, 'title');">{t}Name{/t}</a> </th>
		<th id="sortNote">
            
		<a href="javascript:void(0);">{t}Notes{/t}</a>
		</th>
		<th style="border-right:1px solid #CDD3D5;" id="sortUpdate"> <a href="javascript:void(0);" onclick="xajax_sort_files(DocumentApplication.currentOwnerId, DocumentApplication.currentFolderId, YAHOO.util.Dom.get('current_order').value, YAHOO.util.Dom.get('current_direction').value, 'update');">{t}Date{/t}</a> </th>
	</tr>
	<tr>
		<td class="prVTop"><div class="prFullWidth" id="tree_div_0"></div></td>
		<td valign="top" colspan="3" id="document_tree_content" class="{if !$foldersList && !$documentsList}prNodocsFrame{else}prDocsFilesFrame{/if} prVTop"> {include file="groups/documents/documents.content.template.tpl"} </td>
	</tr>
</table>
<!-- Popups -->
<div id="infoPanel" style="visibility:hidden; display:none;">
	<p class="prText2 prTCenter" id="infoPanelContent"></p>
</div>
<div id="moveGroupPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="moveGroupForm" action="" method="post" id="moveGroupForm">
			<input type="hidden" name="groups" id="movegroup_groups" />
			<input type="hidden" name="fgroups" id="movegroup_fgroups" />
			<input type="hidden" name="folder_id" id="movegroup_folder_id" value="" />
			<input type="hidden" name="owner_id" id="movegroup_owner_id" value="" />
			<input type="hidden" name="to_folder_id" id="movegroup_to_folder_id" value="" />
			<input type="hidden" name="to_owner_id" id="movegroup_to_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><div id="moveGroupPanelContent">
							<div id="moveGroupPanelContentDiv"></div>
						</div></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button"}Move{/t}{linkbutton id="btnMoveGroupFormSubmit" name=$in_button} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnMoveGroupFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="unshareFilePanel" style="visibility:hidden; display:none;">
	<div>
		<form name="unshareFilesForm" action="" method="post" id="unshareFilesForm">
			<input type="hidden" name="groups" id="unshare_groups" />
			<input type="hidden" name="folder_id" id="unshare_folder_id" value="" />
			<input type="hidden" name="owner_id" id="unshare_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prText2 prTCenter">{t}Are you sure you want unshare choosed documents?{/t}</p></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_2"}Unshare{/t}{linkbutton id="btnUnshareFilesFormSubmit" name=$in_button_2} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnUnshareFilesFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span></td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* DELETE GROUPS PANELS *}
<div id="deleteGroupPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="groupDeleteForm" action="" method="post" id="groupDeleteForm">
			<input type="hidden" name="groups" id="groupdelete_groups" />
			<input type="hidden" name="fgroups" id="groupdelete_fgroups" />
			<input type="hidden" name="folder_id" id="groupdelete_folder_id" value="" />
			<input type="hidden" name="owner_id" id="groupdelete_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prText2 prTCenter">{t}Are you sure you want permanently delete choosed documents or folders?{/t}</p></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_3"}Delete{/t}{linkbutton id="btnGroupDeleteFormSubmit" name=$in_button_3} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnGroupDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="deleteGroupAcceptPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="groupDeleteAcceptForm" action="" method="post" id="groupDeleteAcceptForm">
			<input type="hidden" name="fgroups_cancel" id="groupdeleteaccept_fgroups_cancel" />
			<input type="hidden" name="fgroups_accept" id="groupdeleteaccept_fgroups_accept" />
			<input type="hidden" name="fgroups" id="groupdeleteaccept_fgroups" />
			<input type="hidden" name="folder_id" id="groupdeleteaccept_folder_id" value="" />
			<input type="hidden" name="owner_id" id="groupdeleteaccept_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p> {t}Directory '<span id="groupdeleteaccept_foldername"></span>' in not empty!{/t} <br/>
							{t}Do you want to delete it with all its files and subfolders?{/t} </p></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_4"}Delete All{/t}{linkbutton id="btnGroupDeleteAcceptFormSubmit" name=$in_button_4} </span> {t}Or{/t} {t var="in_button_5"}Don't remove this folder{/t}{linkbutton id="btnGroupDeleteAcceptFormCancel" name=$in_button_5} </td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* /DELETE GROUPS PANELS *}
<div id="addToMyPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="addToMyForm" action="" method="post" id="addToMyForm">
			<input type="hidden" name="groups" id="addtomy_groups" />
			<input type="hidden" name="folder_id" id="addtomy_folder_id" value="" />
			<input type="hidden" name="owner_id" id="addtomy_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prTCenter prText2">{t}Are you sure you want add choosed documents to your documents?{/t}</p></td>
				</tr>
				<tr>
					<td class="prTCenter">{t var="in_button_6"}Add to My Documents{/t}{linkbutton id="btnAddtomySubmit" name=$in_button_6}<span class="prIEVerticalAling"><span class="ptIndentLeftSmall">{t}or{/t}</span><a class="prIndentSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="addToMyAcceptPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="addToMyAcceptForm" action="" method="post" id="addToMyAcceptForm" onsubmit="$('#btnAddToMyAcceptFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="groups_cancel" id="addtomyaccept_groups_cancel" />
			<input type="hidden" name="groups_accept" id="addtomyaccept_groups_accept" />
			<input type="hidden" name="groups" id="addtomyaccept_groups" />
			<input type="hidden" name="folder_id" id="addtomyaccept_folder_id" value="" />
			<input type="hidden" name="owner_id" id="addtomyaccept_owner_id" value="" />
			<table class="prForm">
				<tr id="addToMyAcceptMessageContainer">
					<td><p> {t}Document with name '<span id="addtomyaccept_documentname"></span>' already exists!{/t} <br/>
							{t}Please, enter different name to save document?{/t} </p></td>
				</tr>
				<tr id="addToMyAcceptErrorContainer" style="display:none;">
					<td>{t}Please, enter valid name...{/t}</td>
				</tr>
				<tr>
					<td><label for="addtomyaccept_groups_accept_name">{t}Save As{/t}</label>
						<div id="fields_table" >
							<input type="text" maxlength="30" name="groups_accept_name" id="addtomyaccept_groups_accept_name"  />
						</div></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_7"}Save Document{/t}{linkbutton id="btnAddToMyAcceptFormSubmit" name=$in_button_7} </span> Or {t var="in_button_8"}Don't save this document{/t}{linkbutton id="btnAddToMyAcceptFormCancel" name=$in_button_8} </td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* FOLDER PANELS *}
<div id="addFolderPanel" style="visibility:hidden; display:none;">
	<div>
		<form name="newFolderForm" action="" method="post" id="newFolderForm" onsubmit="$('#btnNewFolderFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="folder_id" id="create_folder_folder_id" value="" />
			<input type="hidden" name="owner_id" id="create_folder_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prMarkRequired">{t}Note: Your folder will be added in the <span id="addFolderPanelFolderLabel">top level of Documents</span>{/t}</p></td>
				</tr>
				<tr id="addFolderPanelErrorContainer" style="display:none;">
					<td id="addFolderPanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="create_folder_name">{t}Folder Name{/t}</label>
						<div id="fields_table" >
							<input type="text" maxlength="30" name="folder_name" id="create_folder_name"  />
						</div></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_9"}Add Folder{/t}{linkbutton id="btnNewFolderFormSubmit" name=$in_button_9} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnNewFolderFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="editFolderPanel" style="visibility:hidden; display:none;">
	<div class="bd">
		<form name="editFolderForm" action="" method="post" id="editFolderForm" onsubmit="$('#btnEditFolderFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="folder_id" id="edit_folder_folder_id" value="" />
			<input type="hidden" name="owner_id" id="edit_folder_owner_id" value="" />
			<input type="hidden" name="itemId" id="edit_folder_itemId" value="" />
			<input type="hidden" name="itemType" id="edit_folder_itemType" value="" />
			<input type="hidden" name="handle" value="1" />
			<table class="prForm">
				<tr id="editFolderPanelErrorContainer" style="display:none;">
					<td id="editFolderPanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="edit_folder_name">{t}Folder Name{/t}</label>
						<div id="fields_table" >
							<input type="text" maxlength="30" name="folder_name" id="edit_folder_name"  />
						</div></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_10"}Save Changes{/t}{linkbutton id="btnEditFolderFormSubmit" name=$in_button_10} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnEditFolderFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* /FOLDER PANELS *}

{* FILES PANELS *}
<div id="addFilePanel" style="display:none;">
	<div class="prFullWidth">
		<form name="newFileForm" action="" method="post" id="newFileForm" enctype="multipart/form-data" onsubmit="$('#btnNewFileFormSubmit').trigger('click'); return false;">
			<!--input type="hidden" name="MAX_FILE_SIZE" value="{$upload_max_filesize}" /-->
			<input type="hidden" name="folder_id" id="new_file_folder_id" value="" />
			<input type="hidden" name="owner_id" id="new_file_owner_id" value="" />
			<div>
				<p class="prMarkRequired">{t}Note: Your document  will be placed in the <span id="addFilePanelFolderLabel">top level of Documents</span>.{/t}</p>
			</div>
			<div id="addFilePanelErrorContainer" style="display:none;">
				<div id="addFilePanelErrorContent"></div>
			</div>
			<div class="prClr3 prIndentTop prLargeFormItem prBlockCentered">
				<label for="new_file">{t}Find the file you want on your computer{/t}</label>
				<div id="fields_table" class="prLargeFormItem">
					<input type="file" name="file" id="new_file" size="34" />
				</div>
				<div class="prIndentTopSmall">
					<div style="width: 20px; float:left;">
						<input type="checkbox" class="prNoBorder" name="file_is_bulk" id="new_file_is_bulk" value="1">
					</div>
					<label for="new_file_is_bulk" class="prFloatLeft">{t}Bulk upload{/t}</label>
				</div>
			</div>
			<div class="prIndentTopSmall"> {t}The bulk upload facility allows for a number of documents to be added to the document management system. Provide an archive (ZIP) file from your local computer, and all documents and folders within that archive will be added to the document management system.{/t} </div>
			<div class="prIndentTop prLargeFormItem prBlockCentered">
				<label for="new_file_description">{t}Description{/t}</label>
				<textarea name="file_description" id="new_file_description" rows="5" ></textarea>
			</div>
			<div class="prIndentTop prLargeFormItem prBlockCentered">
				<label for="new_file_tags">{t}Tags{/t}</label>
				<br />
				<input type="text" name="file_tags" id="new_file_tags" value="" class="prFullWidth" />
			</div>
            {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
			<div class="prIndentTop prLargeFormItem prBlockCentered">
				<div class="prTip">{t}Tags are a way to group your documents and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div>
			</div>
            {/if}
			<div class="prIndentTop prLargeFormItem prBlockCentered">
				<input type="radio" name="file_privacy" id="new_file_privacy_private" value="1" class="prAutoWidth prNoBorder" />
				<label for="new_file_privacy_private"> {t}Private{/t}</label>
				<input type="radio" name="file_privacy" id="new_file_privacy_public" value="0" checked="checked" class="prAutoWidth prNoBorder" />
				<label for="new_file_privacy_public"> {t}Public{/t}</label>
			</div>
			<div class="prTCenter"> <span > {t var="in_button_11"}Add Document{/t}{linkbutton id="btnNewFileFormSubmit" name=$in_button_11} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnNewFileFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </div>
		</form>
	</div>
</div>
<div id="editFilePanel" style="visibility:hidden; display:none;">
	<div>
		<form name="editFileForm" action="" method="post" id="editFileForm" onsubmit="$('#btnEditFileFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="folder_id" id="edit_file_folder_id" value="" />
			<input type="hidden" name="owner_id" id="edit_file_owner_id" value="" />
			<input type="hidden" name="itemId" id="edit_file_itemId" value="" />
			<input type="hidden" name="itemType" id="edit_file_itemType" value="" />
			<input type="hidden" name="handle" value="1" />
			<table class="prForm">
				<tr id="editFilePanelErrorContainer" style="display:none;">
					<td id="editFilePanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="edit_file_description">{t}Description{/t}</label>
						<textarea name="file_description" id="edit_file_description" rows="5" ></textarea>
					</td>
				</tr>
				<tr>
					<td><label for="edit_file_tags">{t}Tags{/t}</label>
						<input type="text" name="file_tags" id="edit_file_tags" value="" />
					</td>
				</tr>
                {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
				<tr>
					<td><div class="prTip">{t}Tags are a way to group your documents and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
				</tr>
                {/if}
				<tr>
					<td><input type="radio" name="file_privacy" id="edit_file_privacy_private" value="1" class="prAutoWidth prNoBorder" />
						<label for="edit_file_privacy_private"> {t}Private{/t}</label>
						<input type="radio" name="file_privacy" id="edit_file_privacy_public" value="0" checked="checked" class="prAutoWidth prNoBorder" />
						<label for="edit_file_privacy_public"> {t}Public{/t}</label>
					</td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_12"}Save Changes{/t}{linkbutton id="btnEditFileFormSubmit" name=$in_button_12} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnEditFileFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* /FILES PANELS *}

{* WEBLINK PANELS *}
<div id="addWebLinkPanel" style="display:none;">
	<div>
		<form name="newWebLinkForm" action="" method="post" id="newWebLinkForm" onsubmit="$('#btnNewWebLinkFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="folder_id" id="new_weblink_folder_id" value="" />
			<input type="hidden" name="owner_id" id="new_weblink_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prMarkRequired">{t}Note: Your link will be placed in the <span id="addWebLinkPanelFolderLabel">top level of Documents</span>.{/t}</p></td>
				</tr>
				<tr id="addWebLinkPanelErrorContainer" style="display:none;">
					<td id="addWebLinkPanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="new_weblink">{t}URL (e.g. link to a Google document, Zoho, etc.){/t}</label>
						<div id="fields_table" >
							<input type="text" name="weblink" id="new_weblink" size="32" />
						</div></td>
				</tr>
				<tr>
					<td><label for="new_weblink_description">{t}Description{/t}</label>
						<textarea name="weblink_description" id="new_weblink_description" rows="5" ></textarea>
					</td>
				</tr>
				<tr>
					<td><label for="new_weblink_tags">{t}Tags{/t}</label>
						<input type="text" name="weblink_tags" id="new_weblink_tags" value="" />
					</td>
				</tr>
                {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
				<tr>
					<td><div class="prTip">{t}Tags are a way to group your documents and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
				</tr>
                {/if}
				<tr>
					<td><input type="radio" name="weblink_privacy" id="new_weblink_privacy_private" value="1" class="prAutoWidth prNoBorder" />
						<label for="new_weblink_privacy_private"> {t}Private{/t}</label>
						<input type="radio" name="weblink_privacy" id="new_weblink_privacy_public" value="0" checked="checked" class="prAutoWidth prNoBorder" />
						<label for="new_weblink_privacy_public"> {t}Public{/t}</label>
					</td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_13"}Add Document Link{/t}{linkbutton id="btnNewWebLinkFormSubmit" name=$in_button_13} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnNewWebLinkFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="editWebLinkPanel" style="display:none;">
	<div>
		<form name="editWebLinkForm" action="" method="post" id="editWebLinkForm" onsubmit="$('#btnEditWebLinkFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="folder_id" id="edit_weblink_folder_id" value="" />
			<input type="hidden" name="owner_id" id="edit_weblink_owner_id" value="" />
			<input type="hidden" name="itemId" id="edit_weblink_itemId" value="" />
			<input type="hidden" name="itemType" id="edit_weblink_itemType" value="" />
			<input type="hidden" name="handle" value="1" />
			<table class="prForm">
				<tr id="editWebLinkPanelErrorContainer" style="display:none;">
					<td id="editWebLinkPanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="edit_weblink">{t}URL (e.g. link to a Google document, Zoho, etc.){/t}</label>
						<div id="fields_table" >
							<input type="text" name="weblink" id="edit_weblink" size="32" />
						</div></td>
				</tr>
				<tr>
					<td><label for="edit_weblink_description">{t}Description{/t}</label>
						<textarea name="weblink_description" id="edit_weblink_description" rows="5" ></textarea>
					</td>
				</tr>
				<tr>
					<td><label for="edit_weblink_tags">{t}Tags{/t}</label>
						<input type="text" name="weblink_tags" id="edit_weblink_tags" value="" />
					</td>
				</tr>
                {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
				<tr>
					<td><div class="prTip">{t}Tags are a way to group your documents and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
				</tr>
                {/if}
				<tr>
					<td><input type="radio" name="weblink_privacy" id="edit_weblink_privacy_private" value="1" class="prAutoWidth prNoBorder" />
						<label for="edit_weblink_privacy_private"> {t}Private{/t}</label>
						<input type="radio" name="weblink_privacy" id="edit_weblink_privacy_public" value="0" checked="checked" class="prAutoWidth prNoBorder" />
						<label for="edit_weblink_privacy_public"> {t}Public{/t}</label>
					</td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_14"}Save Changes{/t}{linkbutton id="btnEditWebLinkFormSubmit" name=$in_button_14} </span>
					<span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnEditWebLinkFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span></td>
				</tr>
			</table>
		</form>
	</div>
</div>
{* /WEBLINK PANELS *}
<div id="checkInPanel" style="display:none;">
	<div>
		<form name="checkInForm" action="" method="post" id="checkInForm" enctype="multipart/form-data" onsubmit="$('#btnCheckInFormSubmit').trigger('click'); return false;">
			<!--input type="hidden" name="MAX_FILE_SIZE" value="{$upload_max_filesize}" /-->
			<input type="hidden" name="groups" id="checkin_groups" />
			<input type="hidden" name="folder_id" id="checkin_folder_id" value="" />
			<input type="hidden" name="owner_id" id="checkin_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p>{t}Checking in a document updates the document and allows others to make changes to the document.{/t}</p></td>
				</tr>
				<tr>
					<td><p>{t}If you do not intend to change the document, or you do not wish to prevent others from changing the document, you should rather use the "cancel checkout" option.{/t}</p></td>
				</tr>
				<tr id="checkInPanelErrorContainer" style="display:none;">
					<td id="checkInPanelErrorContent"></td>
				</tr>
				<tr>
					<td><label for="new_file"><b>{t}File{/t}</b></label></td>
				</tr>
				<tr>
					<td><input type="file" name="checkin_file" id="checkin_file" /></td>
				</tr>
				<tr>
					<td><label for="new_file"><b>{t}Reason{/t}</b></label></td>
				</tr>
				<tr>
					<td><p>{t}Describe the changes made to the document.{/t}</p></td>
				</tr>
				<tr>
					<td><textarea name="checkin_reason" id="checkin_reason" rows="10" ></textarea></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_15"}Check In{/t}{linkbutton id="btnCheckInFormSubmit" name=$in_button_15} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnCheckInFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="checkOutPanel" style="display:none;">
	<div>
		<form name="checkOutForm" action="" method="post" id="checkOutForm" onsubmit="$('#btnCheckOutFormSubmit').trigger('click'); return false;">
			<input type="hidden" name="groups" id="checkout_groups" />
			<input type="hidden" name="folder_id" id="checkout_folder_id" value="" />
			<input type="hidden" name="owner_id" id="checkout_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p>{t}Checking out a document reserves it for your exclusive use. This ensures that you can edit the document without anyone else changing the document and placing it into the document management system.{/t}</p></td>
				</tr>
				<tr>
					<td><label for="new_file"><b>{t}Reason{/t}</b></label></td>
				</tr>
				<tr>
					<td><p>{t}The reason for the checkout of this document for historical purposes, and to inform those who wish to check out this document.{/t}</p></td>
				</tr>
				<tr>
					<td><textarea name="checkout_reason" id="checkout_reason" rows="10" ></textarea></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_16"}Check Out{/t}{linkbutton id="btnCheckOutFormSubmit" name=$in_button_16} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnCheckOutFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="cancelCheckOutPanel" style="display:none;">
	<div>
		<form name="cancelCheckOutForm" action="" method="post" id="cancelCheckOutForm">
			<input type="hidden" name="groups" id="cancelcheckout_groups" />
			<input type="hidden" name="folder_id" id="cancelcheckout_folder_id" value="" />
			<input type="hidden" name="owner_id" id="cancelcheckout_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p class="prText2 prTCenter">{t}Are you sure you want to Cancel Check Out?{/t}</p></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_17"}Cancel Check Out{/t}{linkbutton id="btnCancelCheckOutFormSubmit" name=$in_button_17} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnCancelCheckOutFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> </td>
				</tr>
			</table>
		</form>
	</div>
</div>
<div id="revertRevisionPanel" style="display:none;">
	<div>
		<form name="revertRevisionForm" action="" method="post" id="revertRevisionForm">
			<input type="hidden" name="revision" id="revertrevision_revision" />
			<input type="hidden" name="page" id="revertrevision_page" value="" />
			<input type="hidden" name="folder_id" id="revertrevision_folder_id" value="" />
			<input type="hidden" name="owner_id" id="revertrevision_owner_id" value="" />
			<table class="prForm">
				<tr>
					<td><p>{t}Are you sure you want to revert revision?{/t}</p></td>
				</tr>
				<tr>
					<td class="prTCenter"><span > {t var="in_button_18"}Revert Revision{/t}{linkbutton id="btnRevertRevisionFormSubmit" name=$in_button_18} </span>
					<span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnRevertRevisionFormCancel">{t}Cancel{/t}</a></span></td>
				</tr>
			</table>
		</form>
	</div>
</div>
