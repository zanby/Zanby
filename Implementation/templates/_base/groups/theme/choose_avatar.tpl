{*popup_item*}
<div class="prClr3">
    <img class="prFloatLeft prPerWidth31" width="120" height="90" id="a_image_preview" src="{$currentImage->setWidth(120)->setHeight(90)->getImage()}" name="{$a_preview_nid}" />
	<div class="prFloatRight prPerWidth63" id="a_gallery_thumbs">{$a_thumbs_content}</div>
</div>
<div class="prInnerTop prTCenter">
   <a href="#null" class="prButton" onclick="xajax_copy_avatar(document.getElementById('a_image_preview').name);popup_window.close(); return false;"><span>{t}Ok{/t}</span></a>
    {t}or{/t} <a href="#null" onclick="popup_window.close(); return false;"><span>{t}Cancel{/t}</span></a>
</div>
{*popup_item*}