{include file="content_objects/edit_mode_settings_wide.tpl"}

<!-- ============================================== -->
<table class="prForm prIndentSmall">
	<tbody>
		<tr>
			<td class="prInnerSmallBottom">
				<label for="list_display_type_select_{$cloneId}">{t}List Display:{/t}</label>
				<select class="prAutoWidth" id='list_display_type_select_{$cloneId}' onchange="list_display_type_select_change('{$cloneId}', this.selectedIndex);return false;">
		<option selected="selected">{t}List Index{/t}</option>
		<option {if $list_display_type}selected="selected"{/if}>{t}Display an individual list{/t}</option>
	</select>
			</td>
		</tr>
	{if !$list_display_type}
			<tr>
				<td class="prTBold"><label>{t}Select list categories for display:{/t}</label></td>
			</tr>
			<tr>
				<td>
					<div class="prClr2">
	{foreach from=$listsCategories item=item key=key name=lc}
							<div class="prFloatLeft prIndentRight">
								<input id="list_categories_check_{$key}_{$cloneId}" type="checkbox" class="prAutoWidth prNoBorder" value="1" {if $displayCategories[$key]}checked="checked"{/if} onclick="list_categories_check_change('{$cloneId}',{$key}, this.checked);" /><label for="list_categories_check_{$key}_{$cloneId}"> {$item|escape:'html'}</label>
							</div>
	{/foreach}
					</div>
				</td>
			</tr>
			<tr>
				<td class="prTBold"><label>{t}What is the default index sort within each list category?{/t}</label></td>
			</tr>
			<tr>
				<td>
					<div class="prClr2">
						<div class="prFloatLeft prIndentRight">
							<input name="list_default_index_sort_{$cloneId}" id="list_default_index_sort_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $list_default_index_sort==1 || !$list_default_index_sort}checked="checked"{/if} onclick="list_default_index_sort_change('{$cloneId}',1);" /><label for="list_default_index_sort_1_{$cloneId}"> {t}Most Ranked{/t}</label>
						</div>
						<div class="prFloatLeft prIndentRight">
							<input name="list_default_index_sort_{$cloneId}" id="list_default_index_sort_2_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $list_default_index_sort==2}checked="checked"{/if} onclick="list_default_index_sort_change('{$cloneId}',2);" /><label for="list_default_index_sort_2_{$cloneId}"> {t}Most Items to least{/t}</label>
						</div>
						<div class="prFloatLeft prIndentRight">
							<input name="list_default_index_sort_{$cloneId}" id="list_default_index_sort_3_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $list_default_index_sort==3}checked="checked"{/if} onclick="list_default_index_sort_change('{$cloneId}',3);" /><label for="list_default_index_sort_3_{$cloneId}"> {t}Newest to Oldest{/t}</label>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="prTBold"><label>{t}Set the number of lists you wish to display in each category:{/t}</label></td>
			</tr>
			<tr>
				<td>
					<select class="prAutoWidth" id="list_display_number_in_each_category_{$cloneId}" onchange="set_list_display_number_in_each_category(this.value, '{$cloneId}');return false;">
		<option value="1" {if $list_display_number_in_each_category==1}selected="selected"{/if}>{t}Show 1 list{/t}</option>
		<option value="2" {if $list_display_number_in_each_category==2}selected="selected"{/if}>{t}Show 2 lists{/t}</option>
		<option value="3" {if $list_display_number_in_each_category==3}selected="selected"{/if}>{t}Show 3 lists{/t}</option>
		<option value="4" {if $list_display_number_in_each_category==4}selected="selected"{/if}>{t}Show 4 lists{/t}</option>
		<option value="5" {if $list_display_number_in_each_category==5}selected="selected"{/if}>{t}Show 5 lists{/t}</option>
		<option value="6" {if $list_display_number_in_each_category==6}selected="selected"{/if}>{t}Show 6 lists{/t}</option>
		<option value="7" {if $list_display_number_in_each_category==7}selected="selected"{/if}>{t}Show 7 lists{/t}</option>
		<option value="8" {if $list_display_number_in_each_category==8}selected="selected"{/if}>{t}Show 8 lists{/t}</option>
		<option value="9" {if $list_display_number_in_each_category==9}selected="selected"{/if}>{t}Show 9 lists{/t}</option>
		<option value="10" {if $list_display_number_in_each_category==10}selected="selected"{/if}>{t}Show 10 lists{/t}</option>
		<option value="20" {if $list_display_number_in_each_category==20}selected="selected"{/if}>{t}Show 20 lists{/t}</option>
		<option value="30" {if $list_display_number_in_each_category==30}selected="selected"{/if}>{t}Show 30 lists{/t}</option>
		<option value="40" {if $list_display_number_in_each_category==40}selected="selected"{/if}>{t}Show 40 lists{/t}</option>
		<option value="50" {if $list_display_number_in_each_category==50}selected="selected"{/if}>{t}Show 50 lists{/t}</option>
	</select>
				</td>
			</tr>
			<tr>
				<td>
					<input id="list_show_summaries_{$cloneId}"  type="checkbox" class="prAutoWidth prNoBorder" {if $list_show_summaries}checked="checked"{/if} onclick="list_show_summaries_check((document.getElementById('list_show_summaries_{$cloneId}').checked)?1:0,'{$cloneId}');" /><label for="list_show_summaries_{$cloneId}"> {t}Show List Summaries{/t}</label>
				</td>
			</tr>
	{else}
			<tr>
				<td class="prTBold"><label for="list_to_display_{$cloneId}">{t}Select a list to display:{/t}</label></td>
			</tr>
			<tr>
				<td>
					<select class="prAutoWidth" id="list_to_display_{$cloneId}" onchange="set_list_to_display(this.value, '{$cloneId}');return false;">
		<option value="0" selected="selected">&nbsp;</option>
		{foreach from=$listsCategories item=item key = typid}
		{if $listsList->getListsListByType($typid)}
								<optgroup label="{$item|escape:html}" />
			{foreach from=$listsList->setOrder('title ASC')->getListsListByType($typid) item=list key=key}
			<option value="{$list->getId()}" {if $list_to_display == $list->getId()}selected="selected"{/if} >{$list->getTitle()|escape:html}</option>
			{/foreach}
		{/if}
		{/foreach}
	</select>
				</td>
			</tr>
			<tr>
				<td class="prTBold"><label>{t}What is the default sort status for the list?{/t}</label></td>
			</tr>
			<tr>
				<td>
					<div class="prClr2">
						<div class="prFloatLeft">
							<input name="list_default_sort_{$cloneId}" id="list_default_sort_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" checked="checked" onclick="list_default_sort_change('{$cloneId}',1);" /><label for="list_default_sort_1_{$cloneId}"> {t}Most Ranked{/t}</label>
						</div>
						<div class="prFloatLeft">
							<input name="list_default_sort_{$cloneId}" id="list_default_sort_2_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $list_default_sort==2}checked="checked"{/if} onclick="list_default_sort_change('{$cloneId}',2);" /><label for="list_default_sort_2_{$cloneId}"> {t}Most Items to least{/t}</label>
						</div>
						<div class="prFloatLeft">
							<input name="list_default_sort_{$cloneId}" id="list_default_sort_3_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $list_default_sort==3}checked="checked"{/if} onclick="list_default_sort_change('{$cloneId}',3);" /><label for="list_default_sort_3_{$cloneId}"> {t}Newest to Oldest{/t}</label>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="prTBold"><label for="list_display_number_in_each_category_{$cloneId}">{t}Set the number of list items you wish to display before the{/t} &#34;{t}more{/t}&#34; {t}link:{/t}</label></td>
			</tr>
			<tr>
				<td>
					<select class="prAutoWidth" id="list_display_number_in_each_category_{$cloneId}" onchange="set_list_display_number_in_each_category(this.value, '{$cloneId}');return false;">
		<option value="1" {if $list_display_number_in_each_category==1}selected="selected"{/if}>{t}First 1 item{/t}</option>
		<option value="2" {if $list_display_number_in_each_category==2}selected="selected"{/if}>{t}First 2 items{/t}</option>
		<option value="3" {if $list_display_number_in_each_category==3}selected="selected"{/if}>{t}First 3 items{/t}</option>
		<option value="4" {if $list_display_number_in_each_category==4}selected="selected"{/if}>{t}First 4 items{/t}</option>
		<option value="5" {if $list_display_number_in_each_category==5}selected="selected"{/if}>{t}First 5 items{/t}</option>
		<option value="6" {if $list_display_number_in_each_category==6}selected="selected"{/if}>{t}First 6 items{/t}</option>
		<option value="7" {if $list_display_number_in_each_category==7}selected="selected"{/if}>{t}First 7 items{/t}</option>
		<option value="8" {if $list_display_number_in_each_category==8}selected="selected"{/if}>{t}First 8 items{/t}</option>
		<option value="9" {if $list_display_number_in_each_category==9}selected="selected"{/if}>{t}First 9 items{/t}</option>
		<option value="10" {if $list_display_number_in_each_category==10}selected="selected"{/if}>{t}First 10 items{/t}</option>
		<option value="20" {if $list_display_number_in_each_category==20}selected="selected"{/if}>{t}First 20 items{/t}</option>
		<option value="30" {if $list_display_number_in_each_category==30}selected="selected"{/if}>{t}First 30 items{/t}</option>
		<option value="40" {if $list_display_number_in_each_category==40}selected="selected"{/if}>{t}First 40 items{/t}</option>
		<option value="50" {if $list_display_number_in_each_category==50}selected="selected"{/if}>{t}First 50 items{/t}</option>
	</select>
				</td>
			</tr>
			<tr>
				<td>
					<input id="list_show_summaries_{$cloneId}"  type="checkbox" class="prAutoWidth prNoBorder" {if $list_show_summaries}checked="checked"{/if} onclick="list_show_summaries_check((document.getElementById('list_show_summaries_{$cloneId}').checked)?1:0,'{$cloneId}');" /><label for="list_show_summaries_{$cloneId}"> {t}Show List Summaries{/t}</label>
				</td>
			</tr>
	{/if}
	</tbody>
</table>
<!-- ============================================== -->

{if !$list_display_type}
	{include file="content_objects/headline_block_wide.tpl"}
{/if}
{include file="content_objects/ddMyLists/light_block_wide.tpl"}
{include file="content_objects/edit_mode_buttons.tpl"}
