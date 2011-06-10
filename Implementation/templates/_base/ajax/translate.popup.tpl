{*popup_item*}
	{form from=$form id="translateMessageForm" onSubmit="handleTranslateMessageForm(); return false;"}
	{form_hidden name='key' value=$key}
	{form_hidden name='file' value=$file}
	{form_errors_summary}
	<table class="prForm">
	    <col width="20%" />
	    <col width="80%" />
        <tr>
            <td class="prTRight"><label for="photoDescription">Message key : </label> </td>
            <td>{$key}</td>
        </tr>
        <tr>
            <td class="prTRight"><label for="photoDescription">File : </label> </td>
            <td>{$file}</td>
        </tr>
	    {foreach from=$messages item=message key=locale}
	    <tr>
	        <td class="prTRight"><label for="photoDescription">{$localeNames[$locale]} : </label> </td>
	        <td>{form_textarea name="description_"|cat:$locale id="photoDescription_"|cat:$locale rows=8 value=$message}</td>
	    </tr>
	    {/foreach}
	</table>
	{/form}
	<div class="prInnerTop prTCenter">
		<a class="prButton" href="#null" onClick="handleTranslateMessageForm(); return false;"><span>Save Changes</span></a>
		<span class="prIndentLeftSmall">or <a href="#null" onClick="popup_window.close(); return false;"><span>Cancel</span></a></span>
	</div>
{*popup_item*}