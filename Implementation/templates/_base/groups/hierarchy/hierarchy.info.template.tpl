<select onchange="changeCurrentHierarchy(this.options[this.selectedIndex].value, '{$CurrentGroup->getGroupPath('hierarchy')}')" class="prSmallFormItem">
	
	{foreach from=$hierarchyList item=h}
	
	<option value="{$h->getId()}" {if $h->getId() == $current_hierarchy->getId()}selected{/if}>{$h->getName()|escape:html}</option>
	
	{/foreach}

</select>
<div class="prInnerTop prClr3">
	<h2 class="prFloatLeft prNoInner prIndentRight">{$current_hierarchy->getName()|escape:html}</h2>
	<div class="prFloatLeft prIndentTopSmall"> [ <a href="#null" onclick="xajax_remane_hierarchy(getMouseCoordinateX(event), getMouseCoordinateY(event), {$curr_hid}); return false;">{t}Rename{/t}</a> {if !$current_hierarchy->isSystem()}| <a href="#null" onclick="xajax_delete_hierarchy({$curr_hid}); return false;">{t}Delete{/t}</a>{/if} ] </div>
	<div class="prFloatRight"> {t var="in_button"}Hierarchy Preview{/t}{linkbutton name=$in_button link=$currentGroup->getGroupPath('previewhierarchy/hid')|cat:$current_hierarchy->getId()|cat:'/'} </div>
</div>
