{*popup_item*}
<!-- popup content -->
<div class="prInnerTop">
	<div class="prCOCentrino"> <a href="#null"><img id="a_image_preview" src="{$currentAvatar->setWidth(150)->getImage()}" name="{$currentAvatar->getId()}" title="" alt="" /></a> </div>
	<div class="prCOCentrino" id="a_gallery_thumbs"> {$a_thumbs_content} </div>
</div>
<!-- /popup content -->
<!-- content object buttons pannel -->
<div class="prIndent prCOCentrino">
		<!-- - half of buttons group width for cenral alignment -10px for poups -->
		{if $a_thumbs_hash}
			<span class="prIndentLeftSmall"><a class="prButton" href="#null" onclick="storeGBGI('{$cloneId}',document.getElementById('a_image_preview').name);popup_window.close();return false;"><span>{t}OK{/t}</span></a></span>  
		{/if} <span class="prIEVerticalAling">{t}or{/t}
		<a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
<!-- /content object buttons pannel -->
{*popup_item*}