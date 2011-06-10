{*<p id="list_{$list->getId()}">
    {if $list->isSpecialView()}
        <a href="#null" onclick="xajax_doExpandList('{$objEvent->getId()}', '{$objEvent->getUid()}', '{$list->getId()}'); return false;">
        {$list->getListTypeName()|escape:html} {$list->getTitle()|escape:html}</a>
        <p>{$list->getRecordsCount()} item{if $list->getRecordsCount() != 1}s{/if} with {$list->getVolunteersCount()}  volunteer{if $list->getVolunteersCount() != 1}s{/if} </p>
    {else}
        <a href="#null" onclick="xajax_doExpandList('{$objEvent->getId()}', '{$objEvent->getUid()}', '{$list->getId()}'); return false;">{$list->getTitle()|escape:html}</a>       
    {/if}
</p>*}
{TitlePane id='GroupSettingsTransfer'|cat:$list->getId()}
            {TitlePane_Title}{if $list->isSpecialView()}
        {$list->getListTypeName()|escape:html} {$list->getTitle()|escape:html}
        <p>{$list->getRecordsCount()} item{if $list->getRecordsCount() != 1}s{/if} with {$list->getVolunteersCount()}  volunteer{if $list->getVolunteersCount() != 1}s{/if} </p>
    {else}
        {$list->getTitle()|escape:html}     
    {/if}{/TitlePane_Title}             
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_transfer_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_transfer_hide('{$gid}');{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {if $list->isSpecialView()}	
	{include file="users/lists/lists.view.base.special.tpl"}
{else}
	{include file="users/lists/lists.view.base.tpl"}
{/if}	
            {/TitlePane_Content}
{/TitlePane}