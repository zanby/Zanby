{include file="content_objects/edit_mode_settings_narrow.tpl"}

{include file="content_objects/headline_block_narrow.tpl"}

{assign var="maxLinesWithoutScroll" value="6"}

<div class="prInner">
	 <div class="prCOSectionHeader"><h3>{t}Group Families{/t}</h3></div>
     <div id="my-family-groups-popup_{$cloneId}">
         
            <a href="#" onclick="display_all('{$cloneId}','family',1);return false;">All</a> | <a href="#" onclick="display_all('{$cloneId}','family',0);return false;">None</a>
         
        {assign var="count" value=$familyGroupsList|@count}
        {if $count < $maxLinesWithoutScroll}
            {assign var="height" value=$count*24+$count}
        {else}
            {assign var="height" value="160"}
        {/if}
        <div class="prIndentTop" style="overflow:auto;height:{$height}px;">
             
            {foreach from=$familyGroupsList item=current name=grList}
                {assign var=iter value=$smarty.foreach.grList.iteration-1}
                {assign var=currId value=$current->getId()}
                {assign var=currUnHide value=$family_unhide[$currId]}
                <div{if !$smarty.foreach.grList.first} class="prIndentTop"{/if}>
                    <input onclick="family_group_element_hide({$iter},(!document.getElementById('family_group_hide_check_{$iter}_{$cloneId}').checked)?0: document.getElementById('family_group_hide_check_{$iter}_{$cloneId}').value,'{$cloneId}');" id="family_group_hide_check_{$iter}_{$cloneId}" type="checkbox" value="{$current->getId()}" class="prAutoWidth prNoBorder" {if $currUnHide}checked="checked"{/if} /><label for="hide_check_0_{$cloneId}"> {$current->getName()|escape:'html'}</label>
                </div>
            {/foreach}
             
        </div>
         <div class="prIndentTop">
            <input type="checkbox" onclick="group_automaticaly_display('{$cloneId}', 'family', this.checked);" id="family_group_automaticaly_display" name="family_group_automaticaly_display" value="1" class="prAutoWidth prNoBorder" {if $auto_disp_family eq 1}checked="checked"{/if} /> <label for="family_group_automaticaly_display">{t}Automatically show new Group Families which I join{/t}</label>
         </div>
	 </div>
</div>

<div class="prInner">
	 <div class="prCOSectionHeader"><h3>{t}My Groups{/t}</h3></div>
     <div id="my-groups-popup_{$cloneId}">
         
            <a href="#" onclick="display_all('{$cloneId}','simple',1);return false">All</a> | <a href="#" onclick="display_all('{$cloneId}','simple',0);return false;">None</a>
        
        {assign var="count" value=$groupsList|@count}
        {if $count < $maxLinesWithoutScroll}
            {assign var="height" value=$count*24+$count}
        {else}
            {assign var="height" value="160"}
        {/if}
        <div class="prIndentTop" style="overflow:auto;height:{$height}px;">
            {foreach from=$groupsList item=current name=grList}
                {assign var=iter value=$smarty.foreach.grList.iteration-1}
                {assign var=currId value=$current->getId()}
                {assign var=currUnHide value=$unhide[$currId]}
                <div{if !$smarty.foreach.grList.first} class="prIndentTop"{/if}>
                    <input onclick="group_element_hide({$iter},(!document.getElementById('group_hide_check_{$iter}_{$cloneId}').checked)?0: document.getElementById('group_hide_check_{$iter}_{$cloneId}').value,'{$cloneId}');" id="group_hide_check_{$iter}_{$cloneId}" type="checkbox" value="{$current->getId()}" class="prAutoWidth prNoBorder" {if $currUnHide}checked="checked"{/if} /><label for="group_hide_check_{$iter}_{$cloneId}"> {$current->getName()|escape:'html'}</label>
                </div>
            {/foreach}
         </div>
		<div class="prIndentTop">
            <input type="checkbox" onclick="group_automaticaly_display('{$cloneId}', 'simple', this.checked);" id="simple_group_automaticaly_display" name="simple_group_automaticaly_display" value="1" class="prAutoWidth prNoBorder" {if $auto_disp_simple eq 1}checked="checked"{/if} /> <label for="simple_group_automaticaly_display">{t}Automatically show new groups which I join{/t}</label>
         </div>
	 </div>
</div>

{include file="content_objects/edit_mode_buttons.tpl"}