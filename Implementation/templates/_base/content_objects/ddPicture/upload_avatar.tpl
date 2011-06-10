{*popup_item*}
<div id="swferror" style="display:none;"></div>
<div id="error"></div>
<form id="edit_gallery" action="{$user->getUserPath('uploadAvatarOK/upload/1')}" method="post" name="edit_gallery" enctype="multipart/form-data" id="form0">
<input type="hidden" name="inputCount" id="inputCount" value="6" />
<input type="hidden" id="upload_type" name="upload_type" value="upload" />
<input type="hidden" name="cloneId" value="{$cloneId}" />
<!--<iframe name="ifr1" id="ifr1" width="100%" height="0" frameborder="0" scrolling="no"></iframe> -->
<p class="prText2">{t}Find the image you want on your computer{/t}</p>
<!-- popup content -->
<div class="prInnerTopSmall prIndentLeft" id="fields_table" _style="display:none;"> {if $avatarsLeft >=6}
	{assign var=loopvalue value=6}
	{else}
	{assign var=loopvalue value=$avatarsLeft}
	{/if}
	
	{section name=files loop=$loopvalue}
	<div class="prIndentBottom">
		<input type="file" name="edit[img_{$smarty.section.files.iteration}]" id="edit-img_{$smarty.section.files.iteration}" size="45" />
	</div>
	{/section}
	<div id="avatarInput">
		<div id="lastInput"></div>
	</div>
	<div id="more_avatars_link"{if !($avatarsleft-$loopvalue)} style="display:none;"{/if}>
		<div class="prIndentBottom"> <span> <a href="#null" onclick="
div=document.getElementById('avatarInput');
lastDiv=document.getElementById('lastInput');
document.getElementById('inputCount').value = parseFloat(document.getElementById('inputCount').value)+parseFloat(1);
newInput='<div style=\'margin-bottom:4px;\'><input type=\'file\' name=\'edit[img_'+document.getElementById('inputCount').value+']\' id=\'edit-img_'+document.getElementById('inputCount').value+'\' size=50></div>';
newnode=document.createElement('div');
newnode.innerHTML=newInput;
div.insertBefore(newnode,lastDiv);
if (document.getElementById('inputCount').value >= {$avatarsLeft}) document.getElementById('more_avatars_link').style.display='none';
return false;">{t}More links{/t}</a></span> </div>
	</div>
</div>
{include file="content_objects/ddPicture/swfupload.tpl"}
<div class="prInnerTop prIndentLeft"> {t var="in_button"}Upload Profile photos{/t}{linkbutton name=$in_button onclick="uploadandsubmit(function()"|cat:$smarty.ldelim|cat:"uploadAvatars();"|cat:$smarty.rdelim|cat:"); return false;"} <span class="prIEVerticalAling prIndentLeftSmall">or&nbsp;<a id="btnCancel1" href="#null" onclick="cancelupload(function() {$smarty.ldelim}if (swfversion == true) uploadAvatars(); else xajax_select_avatar('{$cloneId}','','reload');{$smarty.rdelim}); return false;"><span>{t}Cancel{/t}</span></a> {t}and go back to profile photos list.{/t}</span> </div>
</form>
{*popup_item*}