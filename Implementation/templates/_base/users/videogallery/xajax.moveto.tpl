<!-- user content -->
<label for="collections">{t}Move Video to the following Collection{/t}</label>
<div class="prInnerSmallTop">
<select name="collections" id="collections">
	{foreach from=$collections key=id item=title}
	<option value="{$id}">{$title}</option>
	{/foreach}
 </select>
</div>
{*popup_item*}
<!-- popup -->
<div class="prInnerTop prTCenter">
		<a class="prButton" href="#null" onclick="{$JsApplication}.showMoveToHandle(document.getElementById('collections').value); return false;"><span>{t}Move{/t}</span></a>
		<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#null" onclick="{$JsApplication}.hideMoveToPanel(); return false;">{t}Cancel{/t}</a></span>
</div>
<!-- /popup -->
{*popup_item*}
<!-- /user content -->
