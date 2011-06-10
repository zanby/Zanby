{*popup_item*}
{if $a_thumbs_hash} <span class="prText2">{t}Selected photo{/t}</span> {/if}
<div class="clear" style="height: 10px;"><span /></div>
<!-- popup content -->
<div class="pu-content">
	<div class="prCOCentrino"> <a href="#null"><img id="a_image_preview" src="{$currentAvatar->setWidth(150)->getImage()}" name="{$currentAvatar->getId()}" title="" alt="" /></a> </div>
	<div id="a_gallery_thumbs"> {$a_thumbs_content} </div>
	<div class="prClearer"></div>
</div>
<!-- /popup content -->
<!-- content object buttons pannel -->
<div class="co-buttons-pannel prTCenter prIndentTop">
		<!-- - half of buttons group width for cenral alignment -10px for poups -->
		{if $a_thumbs_hash}
		
		{t var="in_button"}OK{/t}
		{linkbutton name=$in_button onclick="storeBGI('`$cloneId`',document.getElementById('a_image_preview').name);popup_window.close();return false;"}

		{/if} <span class="prIEVerticalAling">{t}or{/t} 
		<a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
<!-- /content object buttons pannel -->
<div class="prClearer"></div>
{*popup_item*}