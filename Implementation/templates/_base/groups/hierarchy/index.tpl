{literal}
<script src = "/js/yui/yahoo/yahoo.js" ></script>
<script src = "/js/yui/event/event.js" ></script>
<script src = "/js/yui/treeview/treeview.js" ></script>
<script src = "/js/yui/dom/dom.js" ></script>
<script src = "/js/yui/dragdrop/dragdrop.js" ></script>
<script src = "/js/yui/animation/animation.js" ></script>
<script src = "/js/yui/element/element-beta.js" ></script>
<script src = "/js/jquery-latest.pack.js" ></script>
<script src = "/js/json.js" ></script>
<script src = "/js/hierarchy.js" ></script>	
{/literal} 
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/tree.css" media="screen" />
<div class="prClr3">
	<h2 class="prFloatLeft">{t}Hierarchy Designer{/t}</h2>
	<div class="prHeaderTools prIndentTop"><a href="#" onclick="xajax_add_hierarchy(getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">+ {t}Add Hierarchy{/t}</a></div>
</div> 
<div>
	{t}The Hierarchy Designer allows you to determine how your membership is organized,
	and how your visitors and members will navigate your discussions.{/t}
</div>
<div class="prInnerTop prClr3" id="HierarchyInfo">
	{include file="groups/hierarchy/hierarchy.info.template.tpl"}
</div>
<div class="prHierarchyBox prClr3">
	<h2>{t}Constraints{/t}</h2>
	<table cellpadding="0" cellspacing="0" border="0" class="">
		<col width="21%" />
		<col />
		<tr>
			<td nowrap="nowrap"><label>{t}Hierarchy Type:{/t}</label></td>
			<td id="ConstraintsLevel1">
				 <select class="prSmallFormItem" name="hierarchy_type" id="hierarchy_type" onchange="changeConstraints({$curr_hid}, 1, this.options[this.selectedIndex].value);">
				 {foreach from=$c_hierarchy_type item=ht}
					<option value="{$ht.value}"{if $ht.value == $current_hierarchy->getHierarchyType()} selected{/if}>{$ht.name}
				 {/foreach}
				 </select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div class="prText4">{t}Custom categories can be modified in any way you choose.<br />They will NOT be updated automatically.{/t}</div>
			</td>
		</tr>
	</table>
	<div class="prHierarchyBox prClr3">
		<table cellpadding="0" cellspacing="0" border="0" class="prForm"> 
			<col width="25%" />
			<col width="50%"/>
			<col />
			<tr>
				<td colspan="2">
					<h2 class="prWithoutInnerTop">{t}Select Filter{/t}</h2>
					<div class="prText4">{t}You can sort your data as a starting point for your custom hierarchy.{/t}</div>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="prTRight"><label>{t}Category Filter:{/t}</label></td>
				<td id="ConstraintsLevel2">
					 <select name="category_type" id="category_type" onchange="changeConstraints({$curr_hid}, 2, this.options[this.selectedIndex].value);">
					 {foreach from=$c_category_type item=ct}
						<option value="{$ct.value}"{if $ct.value == $current_hierarchy->getCategoryType()} selected{/if}>{$ct.name}
					 {/foreach}
					 </select>
				</td>
				<td></td>
				 
			</tr>
			<tr>
				<td class="prTRight"><label>{t}Category Focus:{/t}</label></td>
				<td id="ConstraintsLevel3">
					 <select name="category_focus" id="category_focus">
					 {foreach from=$c_category_focus item=cf}
						<option value="{$cf.value}"{if $cf.value == $current_hierarchy->getCategoryFocus()} selected{/if}>{$cf.name}
					 {/foreach}
					 </select>
				</td>
				 <td></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><div class="prInnerTop prTRight">
						{t var="in_button"}Apply Filter{/t}
						{linkbutton name=$in_button link=#null onclick="saveConstraints('$curr_hid'); return false;"}
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>
</div>	
<div class="prHierarchyBox prClr3">		
	<h2>{t}Options{/t}</h2>
	<table class="prFullWidth" cellpadding="0" cellspacing="0" border="0">
		<col width="25%" />
		<col width="50%" />
		<col width="25%" />
		<tr>
		<td></td>
			<td colspan="2">
				<input class="prInnerSmall" type="checkbox" name="default" id="default" value="1"{if $current_hierarchy->isDefault()} checked disabled{/if}>
				<label>{t}Use as default (applies to message forums){/t}</label>
			</td>
		</tr>
		<input type="hidden" name="present_custom_levels" id="present_custom_levels" value="1" />
		{if $current_hierarchy->getHierarchyType() == 1}
		<tr>
		<td></td>
			<td colspan="2">
				<input class="prInnerSmall" type="checkbox" onClick="NoThirdLevelSortingChecked(this);" name="no_third_level" id="no_third_level" value="1"{if $current_hierarchy->isNoThirdLevel()} checked{/if}>
				<label>{t}No Third-Level Sorting{/t}</label>
			</td>
		</tr>
		{/if}
		{if $current_hierarchy->getHierarchyType() == 1}
		<tr id="BreakToNextLevelBox"{if $current_hierarchy->isNoThirdLevel()}  style="display:none"{/if}>
			<td class="prTRight">
				<label>{t}Break to next level after:{/t}</label>
			</td>
			<td><select name="break_after" id="break_after" class="prFullWidth prIndentTopSmall">
					<option value="0"{if $current_hierarchy->getBreakAfter() == 0} selected{/if}>
					<option value="1"{if $current_hierarchy->getBreakAfter() == 1} selected{/if}>{t}1 group{/t}
					<option value="2"{if $current_hierarchy->getBreakAfter() == 2} selected{/if}>{t}2 groups{/t}
					<option value="3"{if $current_hierarchy->getBreakAfter() == 3} selected{/if}>{t}3 groups{/t}
					<option value="4"{if $current_hierarchy->getBreakAfter() == 4} selected{/if}>{t}4 groups{/t}
					<option value="5"{if $current_hierarchy->getBreakAfter() == 5} selected{/if}>{t}5 groups{/t}
					<option value="6"{if $current_hierarchy->getBreakAfter() == 6} selected{/if}>{t}6 groups{/t}
					<option value="7"{if $current_hierarchy->getBreakAfter() == 7} selected{/if}>{t}7 groups{/t}
					<option value="8"{if $current_hierarchy->getBreakAfter() == 8} selected{/if}>{t}8 groups{/t}
					<option value="9"{if $current_hierarchy->getBreakAfter() == 9} selected{/if}>{t}9 groups{/t}
					<option value="10"{if $current_hierarchy->getBreakAfter() == 10} selected{/if}>{t}10 groups{/t}
					<option value="20"{if $current_hierarchy->getBreakAfter() == 20} selected{/if}>{t}20 groups{/t}
					<option value="30"{if $current_hierarchy->getBreakAfter() == 30} selected{/if}>{t}30 groups{/t}
					<option value="40"{if $current_hierarchy->getBreakAfter() == 40} selected{/if}>{t}40 groups{/t}
					<option value="50"{if $current_hierarchy->getBreakAfter() == 50} selected{/if}>{t}50 groups{/t}
				</select></td>
				<td></td>
		</tr>
		{/if}
		{if $current_hierarchy->getHierarchyType() == 2}
		<tr>
			<td>
				<label>{t}Group display within level is:{/t}</label>
			</td>
			<td><select name="group_display" id="group_display">
					<option value="0"{if $current_hierarchy->getGroupDisplay() == 0} selected{/if}>{t}none{/t}
					<option value="1"{if $current_hierarchy->getGroupDisplay() == 1} selected{/if}>{t}Alphabetical{/t}
				</select></td>
			<td></td>
		</tr>
		{/if}
		<tr>
			<td></td>
			<td class="prTRight prInnerTop">{t var="in_button_2"}Apply Changes{/t}{linkbutton name=$in_button_2 link=#null onclick="saveOptions($curr_hid); return false;"}</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</div>
<div class="prHierarchyBox prClr3">
	{include file="groups/hierarchy/hierarchy.category.tpl"}
</div>
<div class="prTRight prIndentTop">
	{linkbutton name="Hierarchy Preview" link=$currentGroup->getGroupPath('previewhierarchy/hid')|cat:$current_hierarchy->getId()|cat:'/'}
</div>

{literal}
<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(initDragedObjects);
    function jsTree() {
        {/literal}{$TreeScript}{literal}        
    }
</script>
{/literal}