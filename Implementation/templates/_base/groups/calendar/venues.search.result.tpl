{if $countVenues > 0}
<!-- -->
    <div class="">
    	{$aSearches.find_page*10-9}-{if $countVenues > $aSearches.find_page*10}{$aSearches.find_page*10}{else}{$countVenues}{/if} {t}{tparam value=$countVenues}of %s{/t}
        {if $aSearches.find_page != 1}<a href="#null"  onclick="setFindSearches('find_page','{$aSearches.find_page-1}'); xajax_findaVenue(getFindSearches()); return false;">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
        {if $aSearches.find_page*10 < $countVenues}<a href="#null" onclick="setFindSearches('find_page','{$aSearches.find_page+1}'); xajax_findaVenue(getFindSearches()); return false;">{t}Next{/t} &raquo;</a>{/if}
    </div>
	<div class="prClr3">
    <table cellspacing="0" cellpadding="0" class="prResult">
    	<col width="30%" />
        <col width="5%" />
        <col width="51%" />
        <col width="14%" />
    	<thead>
        	<tr>
            	<th colspan="2" class="prInnerSmall">
                	{t}Name/Type{/t}
                </th>
                <th colspan="2" class="prInnerSmall">
                	{t}Address{/t}
                </th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$aoVenuesList item = venue}
        	<tr>
            	<td class="">
				<span>
                	{$venue->getName()|escape:html}
                    <div>{$venue->getCategory()->getName()|escape:html}</div>
                </span></td>
                <td class="">
                {if $currentGroup->getId() == $venue->getOwnerId() && $venue->getOwnerType() == 'group' }
                	<a href="#" title="Edit" onclick="xajax_editVenue( {$venue->getId()}, 's' ); return false;">
                        <img src="{$AppTheme->images}/buttons/edit.gif" />
                    </a>
                {/if}
                </td>
                <td class="">
                	<span>
                        {if $venue->getAddress1()}{$venue->getAddress1()|escape:html}<br />{/if}
                        {if $venue->getAddress2()}{$venue->getAddress2()|escape:html}<br />{/if}
                        {if $venue->getCity()}    {$venue->getCity()->name|escape:html},{/if}
                        {if $venue->getCity()}    {$venue->getCity()->getState()->name|escape:html},{/if}
                        {if $venue->getZipcode()} {$venue->getZipcode()},{/if}
                        {if $venue->getCity()}    {$venue->getCity()->getState()->getCountry()->name|escape:html}{/if}
                    </span>
                </td>
                <td class="">
                	{if $venue->getType() neq 'worldwide'}
                        <a href="#null" title="Insert" onclick='xajax_chooseSavedVenue("{$venue->getId()}"); return false;'><img src="{$AppTheme->images}/buttons/apply.gif" /></a>
                    {else}
                        <a href="#null" title="Insert" onclick='xajax_chooseSavedWWVenue("{$venue->getId()}"); return false;'><img src="{$AppTheme->images}/buttons/apply.gif" /></a>
                    {/if}
                    {if !($currentGroup->getId() == $venue->getOwnerId() && $venue->getOwnerType() == 'group') }
                        <a href="#null" onclick="xajax_copyVenueFromSearch('{$venue->getId()}'); return false;" title="Add"><img src="{$AppTheme->images}/buttons/add.gif" /></a>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
	</div>
    <div class="">
    	{$aSearches.find_page*10-9}-{if $countVenues > $aSearches.find_page*10}{$aSearches.find_page*10}{else}{$countVenues}{/if} {t}{tparam value=$countVenues}of %s{/t}
        {if $aSearches.find_page != 1}<a href="#null"  onclick="setFindSearches('find_page','{$aSearches.find_page-1}'); xajax_findaVenue(getFindSearches()); return false;">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
        {if $aSearches.find_page*10 < $countVenues}<a href="#null" onclick="setFindSearches('find_page','{$aSearches.find_page+1}'); xajax_findaVenue(getFindSearches()); return false;">{t}Next{/t} &raquo;</a>{/if}
    </div>
<!-- /saved -->
{else}
<div class="prInnerTop prTBold">{t}No venues{/t}</div>
{/if}