{*popup_item*}
<form target="ifr1" action="{$CurrentGroup->getGroupPath('uploadBGIOK')}" method="post" name="edit_gallery" enctype="multipart/form-data" id="form0">
  <input type="hidden" name="inputCount" id="inputCount" value="6" />
  <input type="hidden" name="cloneId" value="{$cloneId}" />
	<iframe name="ifr1" id="ifr1" width="100%" height="17" frameborder="0" scrolling="no"></iframe>
	<div class="prText2 prTCenter">{t}Find the image you want on your computer{/t}</div>
	<div class="clear" style="height: 10px;"><span /></div>
	<!-- popup content -->
	<div>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td><input type="file" name="edit[img_1]" id="edit-img_{$smarty.section.files.iteration}" size="47" /></td></tr>
			<tr><td><div id="avatarInput"><div id="lastInput"></div></div></td></tr>                                                             
		</table>
	</div>
	<!-- /popup content -->
	<!-- content object buttons pannel -->
	<div class="co-buttons-pannel prTCenter prIndentTop">
		<!-- - half of buttons group width for cenral alignment -10px for poups -->
		
		{t var="in_button"}OK{/t}
		{linkbutton name=$in_button onclick="getElementById('form0').submit(); return false;" style="float: left;"}
		
		<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="xajax_select_bgi('`$cloneId`', '', 'reload'); return false;">{t}Cancel{/t}</a></span>
	</div>
	<!-- /content object buttons pannel -->
</form>
{*popup_item*}