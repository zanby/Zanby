<div class="prClr">
<h2 class="prFloatLeft">{t}Categories{/t}</h2>
<div class="prFloatRight prInner">{if $current_hierarchy->getHierarchyType() == 2 && $mode == 1}<a href="#" onclick="xajax_add_category(0, '{$h_grouping[0].id}', '{$current_hierarchy->getId()}'); return false;">+ {t}Add Custom Category{/t}</a>{else}&nbsp;{/if}</div>
</div>
	{if $mode == '1'}
		<div class="prInnerSmallTop prClr3">
				{tab template="tabs1" active="category_grouping"}
                {if $current_hierarchy->getHierarchyType() == 2}
					{tabitem link=$currentGroup->getGroupPath('hierarchy/mode/1/hid')|cat:$current_hierarchy->getId()|cat:'/' name="category_grouping" first="first"}{t}Category Grouping{/t}{/tabitem}
                {/if}
					{tabitem link=$currentGroup->getGroupPath('hierarchy/mode/2/hid')|cat:$current_hierarchy->getId()|cat:'/' name="category" last="last"}{t}Category Hierarchy{/t}{/tabitem}
				{/tab}
			</div>   
		<div class="prInner prClr3">
			{foreach from=$h_grouping item=g name=fgrouping}
				<div  id="GroupingInputs{$g.id}">
				{include file="groups/hierarchy/hierarchy.grouping.inputs.template.tpl" current_hierarchy=$current_hierarchy g=$g}							
				</div>
			{/foreach}
		</div>
	{else}
		<div class="prInnerSmallTop prClr3">
			{tab template="tabs1" active="category"}
            {if $current_hierarchy->getHierarchyType() == 2}
				{tabitem link=$currentGroup->getGroupPath('hierarchy/mode/1/hid')|cat:$current_hierarchy->getId()|cat:'/' name="category_grouping" first="first"}{t}Category Grouping{/t}{/tabitem}
            {/if}
				{tabitem link=$currentGroup->getGroupPath('hierarchy/mode/2/hid')|cat:$current_hierarchy->getId()|cat:'/' name="category" last="last"}{t}Category Hierarchy{/t}{/tabitem}
			{/tab}
		</div>    
		<div class="prClr3">
			<table cellpadding="0" cellspacing="0" border="0" class="prForm">
				<col width="45%" />
				<col width="10%" />
				<col width="45%" />
				<tr>
					<td>
						{foreach from=$h_grouping item=g name=fgrouping}
							<div class="prGrayBorder" style="background-color:#f5f5f1;">
							  <div class="prInner" id="tree_div_{$g.id}"></div>
							</div>
						{/foreach}
					</td>
                    {if $current_hierarchy->getHierarchyType() == 1}
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    {else}

					<td class="prTCenter prInnerTop">
						<img class="prIndentTop" src="{$AppTheme->images}/decorators/arrowLeftBig.gif" />
					</td>
					<td>
						<div class="prDropHeader prIndentBottom">
							<h3>{t}Group Holding Tank{/t}</h3>
							<div class="prHeaderTools"><a href="#null" onclick="showHoldingTankHelp(); return false;">?</a></div>
						</div>
						<div id="HoldingTankHelpBox" style="display:none;">
							{t}The Group Holding Tank contains all of your groups. <br />
							If a group is listed in the group holding tank, it is not sorted through the hierarchy. <br />
							To place groups in the hierarchy, drag them from the holding tank and place them in the appropriate folder, 
							or widen the Hierarchy scope and focus until they are included.{/t}
							<div class="prTRight"><a href="#null" onclick="showHoldingTankHelp(); return false;">{t}close{/t}</a></div>
						</div>
						<div class="prGrayBorder prClr2" id="HoldingGroupBoxMain" style="background-color:#f5f5f1;">
							<div id="HoldingGroupBox" class="prInner">
								{foreach from=$groups item=g}
									<div id="group_{$g->getId()}_div" groupID="{$g->getId()}">{$g->getName()|escape:"html"}</div>
								{/foreach}
							</div>
						</div>
					</td>
                    {/if}
				</tr>
			</table>
		</div>
	{/if}
<div class="prTRight prInner">{if $current_hierarchy->getHierarchyType() == 2 && $mode == 1}<a href="#" onclick="xajax_add_category(0, '{$h_grouping[0].id}', '{$current_hierarchy->getId()}'); return false;">+ {t}Add Custom Category{/t}</a>{else}&nbsp;{/if}</div>