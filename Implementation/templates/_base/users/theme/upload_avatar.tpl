{*popup_item*}
<form target="ifr1" action="{$user->getUserPath('uploadBCKGAvatarOK')}" method="post" name="edit_gallery" enctype="multipart/form-data" id="form0">        
	<iframe name="ifr1" id="ifr1"  width="412" height="28" frameborder="0" scrolling="no"></iframe>
	<p class="prText2 prTCenter">{t}Find the image you want on your computer{/t}</p>
	<div class="prInnerSmallTop prTCenter">
		<input type="file" name="edit[img_1]" id="edit-img_1" size="40" />
	</div>
	<div class="prInnerTop prTCenter">
		{t var='button'}Upload Photo{/t}
		{linkbutton name=$button link="#null" onclick="getElementById('form0').submit(); return false;"} <span class="prIEVerticalAling prIndentBottom">{t}or{/t} 
		<a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	</div>
</form>
{*popup_item*}