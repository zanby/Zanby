
<div>
<a href="#null" class="prInnerRight" onclick="xajax_doCollapseList('{$objEvent->getId()}', '{$objEvent->getUid()}', '{$list->getId()}'); return false;">&nbsp;</a>
</div>
{if $list->isSpecialView()}
	{include file="groups/lists/lists.view.base.special.tpl"}
{else}
	{include file="groups/lists/lists.view.base.tpl"}
{/if}
