{*popup_item*}
<div class="prClr2">
	<div class="prIndentRight prFloatLeft">
		<img width="120" height="90" id="a_image_preview" src="{$currentImage->setWidth(180)->setHeight(135)->getImage()}" />
		<input type="hidden" name="choosed_photo_id" id="choosed_photo_id" value={$currentImage->getId()} />
	</div>
	<div class="prFfloatRight">
		<div id="a_gallery_thumbs" class="prFloatRight">
			{include file='groups/calendar/ajax/action.event.attach.photo.thumbs.tpl'}
		</div>
	</div>
</div>

<div class="prTCenter prIndentTop">
	{t var="in_button"}Ok{/t}
	{linkbutton name=$in_button onclick="xajax_doAttachPhoto(0, YAHOO.util.Dom.get('choosed_photo_id').value);"}
	<span class="prIEVerticalAling prIndentLeft">{t}or{/t} <a href="#"  onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}