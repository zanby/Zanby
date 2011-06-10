{literal}
<script type="text/javascript">
function changeCurrentHierarchy(id, url) {
	document.location.replace(url + 'hid/' + id + '/');
}
</script>
{/literal}
<div class="prClr3">
	<h2 class="prFloatLeft">{t}Hierarchy Designer{/t}</h2>
	<div class="prFloatRight prIndentTop"><a href="#" onclick="xajax_add_hierarchy(getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">	</a></div>
</div>
<div> {t}The Hierarchy Designer allows you to determine how your membership is organized, <br />
	and how your visitors and members will navigate your discussions.{/t} </div>
<div class="prInnerTop prInnerRight">
	<select class="prSmallFormItem" onchange="changeCurrentHierarchy(this.options[this.selectedIndex].value, '{$CurrentGroup->getGroupPath('previewhierarchy')}')">
		
		
				{foreach from=$hierarchyList item=h}
				
		
		<option value="{$h->getId()}" {if $h->getId() == $current_hierarchy->getId()}selected{/if}>{$h->getName()|escape:html}</option>
		
		
				{/foreach}
			
	
	</select>
</div>
<div class="prClr3">
	<h2 class="prFloatLeft prInnerRight">{$current_hierarchy->getName()|escape:html}</h2>
	<div class="prFloatRight prButtonPanel prIndentTop"> {t var="in_button"}Edit Hierarchy{/t}{linkbutton name=$in_button link=$currentGroup->getGroupPath('hierarchy/hid')|cat:$current_hierarchy->getId()|cat:'/'} </div>
</div>
	{foreach from=$globalCategories item=main}
	{foreach from=$main item=level1}
	{foreach from=$level1.categories item=cat1}
<!-- Hierarchy List begin -->
<div class="prHierarchyBox prClr3">
	<h2 class="prWithoutInnerBottom">{$cat1.name|escape:html}</h2>
	<table cellpadding="0" cellspacing="0" border="0" class="prFullWidth">
		<col width="50%" />
		<col width="50%" />
		{if $cat1.categories}
		<tr>
			<td> {foreach name='fCatLevel2' from=$cat1.categories item=cat2}
				{if $smarty.foreach.fCatLevel2.iteration <= ceil($cat1.countOfCategories/2)}
				<ul>
					<li>
						<h3>{$cat2.name|escape:html}</h3>
						{foreach from=$cat2.categories item=cat3}
						<ul>
							<li>
								<h4>{$cat3.name|escape:html}</h4>
							</li>
							{foreach from=$cat3.groups item=group4}
							<li><a href="{$group4.group->getGroupPath('summary')}">{$group4.name|escape:html} ({$group4.group->getMembers()->getCount()})</a></li>
							{/foreach}
						</ul>
						{/foreach}
						{foreach from=$cat2.groups item=group3}
					<li><a href="{$group3.group->getGroupPath('summary')}">{$group3.name|escape:html} ({$group3.group->getMembers()->getCount()})</a></li>
					{/foreach}
					</li>
				</ul>
				{/if}
				{/foreach} </td>
			<td> {foreach name='fCatLevel2' from=$cat1.categories item=cat2}
				{if $smarty.foreach.fCatLevel2.iteration > ceil($cat1.countOfCategories/2)}
				<ul>
					<li>
						<h3>{$cat2.name|escape:html}</h3>
						{foreach from=$cat2.categories item=cat3}
						<ul>
							<li>
								<h4>{$cat3.name|escape:html}</h4>
							</li>
							{foreach from=$cat3.groups item=group4}
							<li><a href="{$group4.group->getGroupPath('summary')}">{$group4.name|escape:html} ({$group4.group->getMembers()->getCount()})</a></li>
							{/foreach}
						</ul>
						{/foreach}
						{foreach from=$cat2.groups item=group3}
					<li><a href="{$group3.group->getGroupPath('summary')}">{$group3.name|escape:html} ({$group3.group->getMembers()->getCount()})</a></li>
					{/foreach}
					</li>
				</ul>
				{/if}
				{/foreach} </td>
		</tr>
		{/if}
		{if $cat1.groups}
		<tr>
			<td><ul>
					{foreach name='fGroupLevel2' from=$cat1.groups item=group2}													
					{if $smarty.foreach.fGroupLevel2.iteration <= ceil($cat1.countOfGroups/2)}
					<li><a href="{$group2.group->getGroupPath('summary')}">{$group2.name|escape} ({$group2.group->getMembers()->getCount()})</a></li>
					{/if}													
					{/foreach}
				</ul></td>
			<td><ul>
					{foreach name='fGroupLevel2' from=$cat1.groups item=group2}													
					{if $smarty.foreach.fGroupLevel2.iteration > ceil($cat1.countOfGroups/2)}
					<li><a href="{$group2.group->getGroupPath('summary')}">{$group2.name|escape} ({$group2.group->getMembers()->getCount()})</a></li>
					{/if}
					{/foreach}
				</ul></td>
		</tr>
		{/if}
	</table>
	<!-- Hierarchy List end -->
</div>
{/foreach}
	{/foreach}
	{/foreach}
<!-- toggle multisection end -->
<div class="prTRight prIndentTop"> {t var="in_button_2"}Edit Hierarchy{/t}{linkbutton name=$in_button_2 link=$currentGroup->getGroupPath('hierarchy/hid')|cat:$current_hierarchy->getId()|cat:'/'} </div>
