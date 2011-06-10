{*popup_item*}
<div id="swferror" style="display:none;">
</div>
<div id="error">
</div>
<form id="edit_gallery" action="{$CurrentGroup->getGroupPath('uploadAvatarOK/upload/1')}" method="post" name="edit_gallery" enctype="multipart/form-data" id="form0">
	<input type="hidden" name="inputCount" id="inputCount" value="6" />
	<input type="hidden" id="upload_type" name="upload_type" value="upload" />
	<input type="hidden" name="cloneId" value="{$cloneId}" />


	<!--<iframe name="ifr1" id="ifr1" width="100%" height="17" frameborder="0" scrolling="no"></iframe>-->

	<p class="prText2">Find the image you want on your computer</p>
	<div class="prIndentTopSmall prIndentLeft" id="fields_table" _style="display:none;">

	{if $avatarsLeft >=6}
				{assign var=loopvalue value=6}
			{else}
				{assign var=loopvalue value=$avatarsLeft}
			{/if}

			{section name=files loop=$loopvalue}
				<div class="prIndentBottom"><input type="file" name="edit[img_{$smarty.section.files.iteration}]" id="edit-img_{$smarty.section.files.iteration}" size="45" /></div>
			{/section}
			<div id="avatarInput"><div id="lastInput"></div></div>
				<div id="more_avatars_link"{if !($avatarsLeft-$loopvalue)} style="display:none;"{/if}>
				<div class="prIndentBottom">
					<span>
					<a href="#null" onclick="
div=document.getElementById('avatarInput');
lastDiv=document.getElementById('lastInput');
document.getElementById('inputCount').value = parseFloat(document.getElementById('inputCount').value)+parseFloat(1);
newInput='<div style=\'margin-bottom:4px;\'><input type=\'file\' name=\'edit[img_'+document.getElementById('inputCount').value+']\' id=\'edit-img_'+document.getElementById('inputCount').value+'\' size=50></div>';
newnode=document.createElement('div');
newnode.innerHTML=newInput;
div.insertBefore(newnode,lastDiv);
if (document.getElementById('inputCount').value >= {$avatarsLeft}) document.getElementById('more_avatars_link').style.display='none';
return false;">{t}More links{/t}</a></span>
				</div>
			</div>

	</div>
	{include file="content_objects/ddGroupAvatar/swfupload.tpl"}
	<div class="prIndentTopSmall prIndentLeft">
	{t var="in_button"}Upload Profile Photos{/t}
	{linkbutton color="blue" name=$in_button onclick="uploadandsubmit(function()"|cat:$smarty.ldelim|cat:"uploadAvatars('$cloneId');"|cat:$smarty.rdelim|cat:"); return false;"}

		{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function() {$smarty.ldelim}if (swfversion == true) uploadAvatars('{$cloneId}'); else xajax_select_avatar('{$cloneId}','','reload');{$smarty.rdelim}); return false;">{t}Cancel{/t}</a> {t}and go back to profile photos list.{/t}
	</div>
	</form>
{*popup_item*}