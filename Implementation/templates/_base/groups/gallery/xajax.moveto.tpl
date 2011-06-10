{*popup_item*}
<!-- user content -->
<label for="galleries">{t}Move Photo to the following Gallery{/t}</label>
<div class="prInnerSmallTop">
	<select name="galleries" id="galleries" class="prLargeFormItem">
		{foreach from=$galleries key=id item=title}
		<option value="{$id}">{$title}</option>
		{/foreach}
	</select>
</div>
<div class="prInnerTop prTCenter">
		<a class="prButton" href="#null" onClick="{$JsApplication}.showMoveToHandle(document.getElementById('galleries').value); return false;"><span>{t}Move{/t}</span></a>
		<span class="prIndentLeftSmall">{t}or{/t} <a href="#null" onClick="{$JsApplication}.hideMoveToPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>

<!-- /user content -->
{*popup_item*}