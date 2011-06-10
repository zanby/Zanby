{*popup_item*}
<div class="prClr3">
	<div class="prFloatLeft prPerWidth31">
    	<img width="120" height="90" id="a_image_preview" src="{$currentImage->setWidth(120)->setHeight(90)->getImage()}" name="{$a_preview_nid}" />
	</div>
    <div id="a_gallery_thumbs" class="prFloatRight prPerWidth63">{$a_thumbs_content}</div>    
</div>

<div class="prInnerTop prTCenter">
	{t var='button'}Ok{/t}
	{linkbutton name=$button onclick="xajax_copy_avatar(document.getElementById('a_image_preview').name);popup_window.close(); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span></span>  
</div>
{*popup_item*}