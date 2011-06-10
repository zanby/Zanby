{form from=$form class="search_form"}
{form_hidden name="new" value="1"}
<div class="prDropBox prDropBoxInner">
	<div class="prDropHeader">
		<h2>{t}Search groups outside your family{/t}</h2>
	</div>
	<table class="prForm">
		<col width="45%" />
		<col width="35%" />
		<col width="20%" />
		<tr>
			<td class="prTRight"><label for="keywords">{t}Keyword or Tag separated by comma:{/t}</label></td>
			{*
			<td><label for="category">{t}Category{/t}</label></td>
			<td></td>
		</tr>
		<tr> *}
			<td>{form_text name="keywords" value=$keywords|escape:"html" size="29"}{/form}</td>
			{*
			<td>{form_select name="category" selected=$category options=$allCategories}</td>
			<td></td>
		</tr>
		<tr>
			<td><label for="countryId">{t}Country{/t}</label></td>
			<td><label for="stateId">{t}State or Province{/t}</label></td>
			<td></td>
		</tr>
		<tr>
			<td>{form_select name="country" id="countryId" onchange="xajax_search_onchange_country(this.options[this.selectedIndex].value);" selected=$country options=$countries"}</td>
			<td>{form_select name="state" id="stateId" selected=$state options=$states}{/form}</td>
			*}
			<td>{t var="in_button"}Search{/t}{linkbutton name=$in_button onclick="document.forms['search_group'].submit(); return false;"}</td>
		</tr>
		{if $rememberForm}
		<tr>
			<td class="prTRight"><label for="search_name">{t}Remember search as:{/t}</label></td>
			{*
			<td></td>
			<td></td>
		</tr>
		<tr> *} 		{form from=$rememberForm}
			<td>{form_text name="search_name" size="29"}</td>
			<td>{t var="in_button_2"}Remember{/t}{linkbutton name=$in_button_2 onclick="document.forms['search_remember'].submit(); return false;"}</td>
			{/form} </tr>
		{/if}
		{if $savedSearches}
		<tr>
			<td colspan="2"><h3>{t}Saved Searches:{/t}</h3>
				<ul class="prClr2 prInnerTop">
					{foreach item=s key=key from=$savedSearches name=savedSearches}
					<li class="prFloatLeft prClr2"><a href="{$currentGroup->getGroupPath('invitesearch')}preset/new/saved/{$key}/" class="prFloatLeft{if !$smarty.foreach.savedSearches.first} prInnerLeft{/if}">{if $s}{$s}{else}{t}noname{/t}{/if}</a> <div class="prBgIcon prIndentLeftSmall"><a href="javascript:void(0)" onclick="xajax_deletesearch('{$currentGroup->getGroupPath('inviteSearchDelete')}id/{$key}/'); return false;"><img src="{$AppTheme->images}/buttons/close.gif" alt="Delete" title="Delete" /></a></div></li>
					{/foreach}
				</ul></td>
		</tr>
		{/if}
	</table>
</div>
</form>
