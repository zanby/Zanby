{*popup_item*}
<div class="prClr2">
	<div class="prIndentRight prFloatLeft">
		<img width="120" height="90" id="a_image_preview" src="{$currentImage->setWidth(180)->setHeight(135)->getImage()}" />
		<input type="hidden" name="choosed_photo_id" id="choosed_photo_id" value={$currentImage->getId()} />
	</div>
	<div class="prFfloatRight">
		<div id="a_gallery_thumbs">
			{include file='users/calendar/ajax/action.event.attach.photo.thumbs.tpl'}
		</div>
	</div>
</div>
<div class="prTCenter prIndentTop">
	{if $weAreChoosingAvatar}
	{t var='button_01'}Ok{/t}
        {linkbutton name=$button_01 onclick="xajax_doAvatarLoadFromGalleries(YAHOO.util.Dom.get('choosed_photo_id').value, '$jsCallbackCode');"}
	{else}
		{t var='button_02'}Ok{/t}
        {linkbutton name=$button_02 onclick="xajax_doAttachPhoto(0, YAHOO.util.Dom.get('choosed_photo_id').value);"}
	{/if}
	<span class="prIndentLeft prIEVerticalAling">{t}or{/t} <a href="#" {if $wearechoosingavatar}onclick="{$jsCallbackCode2}"{else}onclick="popup_window.close(); return false;"{/if}>
	{t}Cancel{/t}</a>
	</span>
</div>
{*popup_item*}