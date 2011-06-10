{*popup_item*}<!-- user content -->
<label for="newsgroup">{t}Publish Gallery to the following newsgroup{/t}</label>
<div class="prInnerSmallTop">
	<select name="newsgroup" id="newsgroup">
		{foreach from=$newsGroups key=id item=title}
		<option value="{$id}">{$title}</option>
		{/foreach}
	</select>
</div>

<div class="prInnerTop">
	<a class="prButton" href="#null" onClick="{$JsApplication}.showPublishPanelHandle(document.getElementById('newsgroup').value); return false;"><span>{t}Publish{/t}</span></a>
	<span class="prIndentLeftSmall"><a class="prButton" id="btnCancel1" href="#null" onClick="{$JsApplication}.hidePublishPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>	

<!-- /user content -->
{*popup_item*}