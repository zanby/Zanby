{*popup_item*}
<!-- user content -->
<label for="newsgroup">{t}Publish video to the following group{/t}</label>
<div class="prInnerSmallTop">
	<select name="newsgroup" id="newsgroup">
		{foreach from=$newsGroups key=id item=title}
		<option value="{$id}">{$title}</option>
		{/foreach}
	</select>
</div>
<div class="prInnerTop prTCenter">
			<a class="prButton" href="#null" onClick="{$JsApplication}.showPublishPanelHandle(document.getElementById('newsgroup').value); return false;"><span>{t}Publish{/t}</span></a>
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" id="btnCancel1" href="#null" onClick="{$JsApplication}.hidePublishPanel(); return false;">{t}Cancel{/t}</a></span>
</div>
<!-- /user content -->		
{*popup_item*}		