{include file="content_objects/edit_mode_settings_wide.tpl"}


<!-- ============================================== -->
<div class="prInnerSmall">
	<table class="prForm">
		<col width="35%" />
		<col width="16%" />
		<col width="49%" />
		<tbody>
			<tr>
				<td>
					<select id='display_gm_type_select_{$cloneId}' onchange="display_gm_type_select_change('{$cloneId}', this.selectedIndex);return false;">
			            <option>Display as List</option>
			            <option {if $display_type}selected="selected"{/if}>Display as thumbnail gallery</option>
			        </select>
				</td>
				<td>
					<input name="family_people_entity_to_display_{$cloneId}" id="family_people_entity_to_display_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" checked="checked" onclick="family_people_entity_to_display_change('{$cloneId}',1);" /><label for="family_people_entity_to_display_1_{$cloneId}"> People</label>
				</td>
				<td>
					<input name="family_people_entity_to_display_{$cloneId}" id="family_people_entity_to_display_2_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $entity_to_display==2}checked="checked"{/if} onclick="family_people_entity_to_display_change('{$cloneId}',2);" /><label for="family_people_entity_to_display_2_{$cloneId}"> Groups</label>
				</td>
			</tr>
			<tr>
				<td class="prTBold" colspan="3">
					<div><label>Sort by:</label></div>
				</td>
			</tr>
			<tr>
				<td class="prNoPadding" colspan="2">
					<select name="default_gm_index_sort_{$cloneId}" id="default_gm_index_sort_1_{$cloneId}" onchange="default_gm_index_sort_change('{$cloneId}', this.value);return false;">
			            <option value="1" {if $default_index_sort==1}selected="selected"{/if}>By Region &mdash; Newest to Oldest</option>
			            <option value="2" {if $default_index_sort==2}selected="selected"{/if}>Newest to Oldest</option>
			        </select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3">
					<label for="display_gm_number_in_each_region_{$cloneId}">Show top </label>
					<select id="display_gm_number_in_each_region_{$cloneId}" onchange="set_gm_display_number_in_each_region(this.value, '{$cloneId}');return false;" class="prAutoWidth">
			            <option value="1" {if $display_number_in_each_region==1}selected="selected"{/if}>1</option>
			            <option value="2" {if $display_number_in_each_region==2}selected="selected"{/if}>2</option>
			            <option value="3" {if $display_number_in_each_region==3}selected="selected"{/if}>3</option>
			            <option value="4" {if $display_number_in_each_region==4}selected="selected"{/if}>4</option>
			            <option value="5" {if $display_number_in_each_region==5}selected="selected"{/if}>5</option>
			            <option value="6" {if $display_number_in_each_region==6}selected="selected"{/if}>6</option>
			            <option value="7" {if $display_number_in_each_region==7}selected="selected"{/if}>7</option>
			            <option value="8" {if $display_number_in_each_region==8}selected="selected"{/if}>8</option>
			            <option value="9" {if $display_number_in_each_region==9}selected="selected"{/if}>9</option>
			            <option value="10" {if $display_number_in_each_region==10}selected="selected"{/if}>10</option>
			            <option value="20" {if $display_number_in_each_region==20}selected="selected"{/if}>20</option>
			            <option value="30" {if $display_number_in_each_region==30}selected="selected"{/if}>30</option>
			            <option value="40" {if $display_number_in_each_region==40}selected="selected"{/if}>40</option>
			            <option value="50" {if $display_number_in_each_region==50}selected="selected"{/if}>50</option>
			        </select>
					{if $default_index_sort == 1}
				        <label>Members  per region</label>
				    {else}
				        <label>Members</label>
				    {/if}
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- ============================================== -->

{include file="content_objects/headline_block_wide.tpl"}

<!-- ============================================== -->
{if $default_index_sort == 1}
<div class="prInner">
	<a id="href-group-members-popup_{$cloneId}" href="#null" onclick="switchGroupMembersPopup('{$cloneId}');return false;">Country fields</a>&nbsp;&raquo;
	<div id="group-members-popup_{$cloneId}" style="display:none;">
		{foreach from=$countriesList item=current name=cntrList key=key}
			{assign var=iter value=$smarty.foreach.cntrList.iteration-1}
			<div{if !$smarty.foreach.cntrList.first} class="prIndentTop"{/if}><input onclick="group_members_element_hide({$iter},(document.getElementById('group_members_hide_check_{$iter}_{$cloneId}').checked)?0: document.getElementById('group_members_hide_check_{$iter}_{$cloneId}').value,'{$cloneId}');" id="group_members_hide_check_{$iter}_{$cloneId}" type="checkbox" checked="checked" value="{$key}" class="prAutoWidth prNoBorder" /><label for="group_members_hide_check_{$iter}_{$cloneId}"> {$current|escape:'html'}</label></div>
		{/foreach}
	</div>
</div>
{/if}
<!-- ============================================== -->

{include file="content_objects/ddFamilyPeople/light_block_wide$gmode.tpl"}
{include file="content_objects/edit_mode_buttons.tpl"}