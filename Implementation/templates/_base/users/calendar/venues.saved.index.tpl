<div class="prClr3">
    <ul>
        <li class="prFloatLeft prIndentLeftSmall"><a href="#" onclick="setSearches('l','all'); xajax_loadSavedVenues(getSearches()); return false;">{t}All{/t}</a></li>
        <li class="prFloatLeft prIndentLeftSmall">-</li>
        {foreach from=$letters key=k item=l}
        <li class="prFloatLeft prIndentLeftSmall">
            {if $l.link}
                <a href="#" onclick="setSearches('l','{$k}'); xajax_loadSavedVenues(getSearches()); return false;" {if $l.selected} class="" {/if}>{$k}</a>
            {else}
                {if $l.selected}{$k class=""}{else}{$k}{/if}
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
<!-- -->
<div class="prClr3 prInner">
    <div class="prIndentTop">
        <label>{t}Sort by type:{/t} </label> 
        <select class="" name="search_venue_category" id="search_venue_category" onchange="setSearches('c',this.options[this.selectedIndex].value); xajax_loadSavedVenues(getSearches()); return false;">
            {foreach from=$categories key = k item = v}
                <option value="{$k}" {if $k == $aSearches.c} selected="selected"{/if}>{$v}</option>';
            {/foreach}
        </select>
    </div>
    {if $countVenues>0}
	<div>
    	{$aSearches.p*10-9}-{if $countVenues > $aSearches.p*10}{$aSearches.p*10}{else}{$countVenues}{/if} {t}of{/t} {$countVenues}
        {if $aSearches.p != 1}<a href="#null" onclick="setSearches('p','{$aSearches.p-1}'); xajax_loadSavedVenues(getSearches()); return false;">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
        {if $aSearches.p*10 < $countVenues}<a href="#null" onclick="setSearches('p','{$aSearches.p+1}'); xajax_loadSavedVenues(getSearches()); return false;">{t}Next{/t} &raquo;</a>{/if}
    </div>
	{/if}
    <table cellspacing="0" cellpadding="0" class="prResult">
    	<col width="32%" />
        <col width="8%" />
        <col width="45%" />
        <col width="15%" />
    	<thead>
        	<tr>
            	<th colspan="2">
                	{t}Name/Type{/t}
                </th>
               	<th colspan="2">
                	{t}Address{/t}
                </th>
                 
            </tr>
        </thead>
        <tbody>
        {foreach from=$aoSimpleVenuesList item = venue}
        	<tr>
            	<td>
				<span>
                	{$venue->getName()|escape:html}
                    <div>{$venue->getCategory()->getName()}</div>
                </span></td>
                <td>
                	<a href="#null" title="Edit" onclick="xajax_editVenue( {$venue->getId()}, 's' ); return false;">
                        <img src="{$AppTheme->images}/buttons/edit.gif" />
                    </a>
                </td>
                <td>
                	<span>
                        {if $venue->getAddress1()}{$venue->getAddress1()|escape:html}<br />{/if}
                        {if $venue->getAddress2()}{$venue->getAddress2()|escape:html}<br />{/if}
                        {$venue->getCity()->name|escape:html},
                        {$venue->getCity()->getState()->name|escape:html},
                        {if $venue->getZipcode()}{$venue->getZipcode()},{/if}
                        {$venue->getCity()->getState()->getCountry()->name|escape:html}
                    </span>
				</td>	
                <td>
                	<a href="#null" title="Insert" onclick='xajax_chooseSavedVenue({$venue->getId()}); return false;'><img src="{$AppTheme->images}/buttons/apply.gif" /></a>
                    <a href="#null" onclick="xajax_copyVenue({$venue->getId()}); return false;" title="Add"><img src="{$AppTheme->images}/buttons/add.gif" /></a>
                    <a href="#null" title="Delete" onclick="xajax_deleteVenue({$venue->getId()}); return false;"><img src="{$AppTheme->images}/buttons/close.gif" /></a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    {literal}
    <script type="text/javascript">
        function getSearches()
        {
            var search = new Array();
            
            search['l'] = document.getElementById('l').value;
            search['c'] = document.getElementById('c').value;
            search['p'] = document.getElementById('p').value;
            
            return search;
        }
        
        function setSearches(a,v)
        {
            document.getElementById('p').value = 1;
            if (a == 'c') {
                document.getElementById('l').value = 'all';
            }
            document.getElementById(a).value = v;
        }
    </script>
    {/literal}
    <div>
	{if $countVenues>0}
        {$aSearches.p*10-9}-{if $countVenues > $aSearches.p*10}{$aSearches.p*10}{else}{$countVenues}{/if} of {$countVenues}
        {if $aSearches.p != 1}<a href="#null" onclick="setSearches('p','{$aSearches.p-1}'); xajax_loadSavedVenues(getSearches()); return false;">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
        {if $aSearches.p*10 < $countVenues}<a href="#null" onclick="setSearches('p','{$aSearches.p+1}'); xajax_loadSavedVenues(getSearches()); return false;">{t}Next{/t} &raquo;</a>{/if}
    {else}
		<div class="prTBold prIndentTop">No venues</div>
	{/if}
	</div>
</div>
<!-- /saved -->