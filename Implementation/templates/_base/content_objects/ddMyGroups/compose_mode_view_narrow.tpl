<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

{if $groupsList}
	{if !$family_groups_empty}
		<div class="prCOSectionHeader">
			<h3>{t}Group Families{/t}</h3>
			<ul class="prInnerBottom">
				{foreach from=$familyGroupsList item=current name=grList}
			        {assign var=iter value=$smarty.foreach.grList.iteration-1}
			        {assign var=currId value=$current->getId()}
			        {assign var=currUnHide value=$family_unhide[$currId]}
					<li class="prClr3{if !$smarty.foreach.grList.first} prIndentTop{/if}" id="fgpdiv_{$iter}_{$cloneId}" style="display: {if $currUnHide || ($auto_disp_family && !in_array($currId,$not_new_groups))}block{else}none{/if}">
						<span class="prFloatLeft"><a href="{$current->getGroupPath('summary')}">{$current->getName()|escape:'html'}</a></span>
					</li>
				{/foreach}
			</ul>
		</div>
	{/if}
{/if}


{if $groupsList}
	{if !$groups_empty}
		<div class="prCOSectionHeader">
			<h3>{t}My Groups{/t}</h3>
			<ul class="prInnerBottom">
				{foreach from=$groupsList item=current name=grList}
			        {assign var=iter value=$smarty.foreach.grList.iteration-1}
			        {assign var=currId value=$current->getId()}
			        {assign var=currUnHide value=$unhide[$currId]}
					<li class="prClr3{if !$smarty.foreach.grList.first} prIndentTop{/if}" id="gpdiv_{$iter}_{$cloneId}" style="display: {if $currUnHide || ($auto_disp_simple eq 1 && !in_array($currId,$not_new_groups))}block{else}none{/if}">
						<span class="prFloatLeft"><a href="{$current->getGroupPath('summary')}">{$current->getName()|escape:'html'}</a></span>
					</li>
				{/foreach}
			</ul>
		</div>
	{/if}
{/if}

</div>
