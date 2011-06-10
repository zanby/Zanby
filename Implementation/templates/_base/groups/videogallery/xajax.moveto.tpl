{*popup_item*}
<!-- user content -->
<label for="collections">{t}Move Video to the following Collection{/t}</label>
<div class="prInnerSmallTop">
<select name="collections" id="collections">
	{foreach from=$collections key=id item=title}
	<option value="{$id}">{$title}</option>
	{/foreach}
</select>
</div>      
<div class="prInnerTop prTCenter">
		<a class="prButton" href="#null" onClick="{$JsApplication}.showMoveToHandle(document.getElementById('collections').value); return false;"><span>{t}Move{/t}</span></a>
		<span class="prIndentLeftSmall"><a class="prButton" href="#null" onClick="{$JsApplication}.hideMoveToPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
<!-- /user content -->
{*popup_item*}