<!-- user content -->
{*popup_item*}
<label for="galleries">{t}Move Photo to the following Gallery{/t}</label>
<div class="prInnerSmallTop">
	<select name="galleries" id="galleries" class="prLargeFormItem">
		{foreach from=$galleries key=id item=title}
		<option value="{$id}">{$title}</option>
		{/foreach}
	</select>
</div>
<div class="prInnerTop prTCenter">
	<a class="prButton" href="#null" onclick="{$JsApplication}.showMoveToHandle(document.getElementById('galleries').value); return false;"><span>{t}Move{/t}</span></a>
	<span class="prIndentLeftSmall">{t}or{/t} <a href="#null" onclick="{$JsApplication}.hideMoveToPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
{*popup_item*}
<!-- /user content -->
