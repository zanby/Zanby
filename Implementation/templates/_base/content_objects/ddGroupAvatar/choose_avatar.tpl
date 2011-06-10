{*popup_item*}
{if $a_thumbs_hash}
<p class="prText2 prTCenter">{t}Selected photo will be your primary group's photo{/t}</p>
{/if}
<div class="prInnerTop prPopupScroll">
	 <div class="prCOCentrino"><a href="#null"><img id="a_image_preview_GA" src="{$CurrentGroup->getAvatar()->setWidth(150)->getImage()}" name="{$CurrentGroup->getAvatar()->getId()}" title="" alt="" /></a> </div>
</div>
	<div class="prCOCentrino prInnerTop" id="a_gallery_thumbs_GA"> {$a_thumbs_content} </div>
<div class="prIndent prCOCentrino">
	{if $a_thumbs_hash}
		{if $smarty.section.thumb.iteration <= 13}
				{linkbutton id="a_gallery_thumbs_GA_add" name="Add picture to group profile photos gallery" link="#null" onclick="xajax_upload_avatar('`$cloneId`'); return false;"}
		{/if}
	{else}
		{linkbutton name="Create group profile photo gallery" link="#null" onclick="xajax_upload_avatar('`$cloneId`'); return false;"}
	{/if}
	<div class="prInner">
		{if $a_thumbs_hash}
		<span class="prIndentLeftSmall">
		{t var="in_button"}OK{/t}
		{linkbutton name=$in_button link="#null" onclick="storeAvatar('`$cloneId`',document.getElementById('a_image_preview_GA').name);popup_window.close();return false;"}
		<span class="prIEVerticalAling">{t}or{/t} </span>
		{/if}
		<a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a>{if $a_thumbs_hash}</span>{/if}
	</div>
</div>
{*popup_item*}