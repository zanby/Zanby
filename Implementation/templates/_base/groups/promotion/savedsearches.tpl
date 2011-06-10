{*
{if $savedSearches}
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td><h2>{t}Saved Searches:{/t}</h2>
<ul>
{foreach item=s key=key from=$savedSearches name=savedSearches}    	
<li><a href="{$currentGroup->getGroupPath('invitesearch')}preset/new/saved/{$key}/">{if $s}{$s}{else}{t}noname{/t}{/if}</a>
<a href="javascript:void(0)" onclick="xajax_deletesearch('{$currentGroup->getGroupPath('inviteSearchDelete')}id/{$key}/'); return false;">&nbsp;</a> </li>
{/foreach}

</ul>

</td>
</tr>
</table>
{/if}
*}
{*
{if $savedSearches}
    {t}Saved Searches:{/t}
    {foreach item=s key=key from=$savedSearches name=savedSearches}    	
        <a href="{$currentGroup->getGroupPath('invitesearch')}preset/new/saved/{$key}/">{if $s}{$s}{else}{t}noname{/t}{/if}</a>
        <a href="javascript:void(0)" onclick="xajax_deletesearch('{$currentGroup->getGroupPath('inviteSearchDelete')}id/{$key}/'); return false;">{t}Delete{/t}</a>
        {if !$smarty.foreach.savedSearches.last}&nbsp;&nbsp;&nbsp;&nbsp;{/if}
    {/foreach}
    <br />
{/if}
*}