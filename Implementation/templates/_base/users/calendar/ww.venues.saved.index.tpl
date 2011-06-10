<div class="prClr3">
    <ul>
        <li class="prFloatLeft prIndentLeftSmall"><a href="#" onclick="setWWSearches('wl','all'); xajax_loadSavedWWVenues(getWWSearches()); return false;">{t}All{/t}</a></li>
        <li class="prFloatLeft prIndentLeftSmall">-</li>
        {foreach from=$letters key=k item=l}
        <li class="prFloatLeft prIndentLeftSmall">
            {if $l.link}
                <a href="#" onclick="setWWSearches('wl','{$k}'); xajax_loadSavedWWVenues(getWWSearches()); return false;" {if $l.selected} class=""{/if}>{$k}</a>
            {else}
                {if $l.selected}{$k class=""}{else}{$k}{/if}
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
<!-- -->
<div class="prClr3 prInner">
    <div class="prInnerSmall">
        <label>{t}Sort by type:{/t} </label> 
        <select class="" name="search_venue_category" id="search_venue_category" onchange="setWWSearches('wc',this.options[this.selectedIndex].value); xajax_loadSavedWWVenues(getWWSearches()); return false;">
            {foreach from=$categories key = k item = v}
                <option value="{$k}" {if $k == $aSearches.wc} selected="selected"{/if}>{$v}</option>';
            {/foreach}
        </select>
    </div>
    {if $countVenues>0}
	<div>
    	{$aSearches.wp*10-9}-{if $countVenues > $aSearches.wp*10}{$aSearches.wp*10}{else}{$countVenues}{/if} {t}of{/t} {$countVenues}
        {if $aSearches.wp != 1}<a href="#null" onclick="setWWSearches('wp','{$aSearches.wp-1}'); xajax_loadSavedWWVenues(getWWSearches()); return false;">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
        {if $aSearches.wp*10 < $countVenues}<a href="#null" onclick="setWWSearches('wp','{$aSearches.wp+1}'); xajax_loadSavedWWVenues(getWWSearches()); return false;">{t}Next{/t} &raquo;</a>{/if}
    </div>
	{/if}
    <table cellspacing="0" cellpadding="0" class="prResult">
    	<col width="32%" />
        <col width="8%" />
        <col width="45%" />
        <col width="14%" />
    	<thead>
        	<tr>
            	<th colspan="2">
                	{t}Name/Type{/t}
                </th>
                <th colspan="2">
                	{t}Description{/t}
                </th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$aoWorldwideVenuesList item = venue}
        	<tr>
            	<td>				
                	{$venue->getName()|escape:html}<br />
                    {$venue->getCategory()->getName()|escape:html}
                </td>
                <td>
                	<a href="#" title="Edit" onclick="xajax_editWWVenue( '{$venue->getId()}', 'ws' ); return false;">
                        <img src="{$AppTheme->images}/buttons/edit.gif" />
                    </a>
                </td>
                <td>
                	{$venue->getDescription()|escape:html}
                </td>
                <td>
                	<a href="#null" title="Insert" onclick='xajax_chooseSavedWWVenue({$venue->getId()}); return false;'><img src="{$AppTheme->images}/buttons/apply.gif" /></a>
                    <a href="#null" onclick="xajax_copyWWVenue({$venue->getId()}); return false;" title="Copy"><img src="{$AppTheme->images}/buttons/add.gif" /></a>
                    <a href="#null" title="Delete" onclick="xajax_deleteWWVenue({$venue->getId()}); return false;"><img src="{$AppTheme->images}/buttons/close.gif" /></a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    {literal}
    <script type="text/javascript">
        function getWWSearches()
        {
            var search = new Array();
            
            search['wl'] = document.getElementById('wl').value;
            search['wc'] = document.getElementById('wc').value;
            search['wp'] = document.getElementById('wp').value;
            
            return search;
        }
        
        function setWWSearches(a,v)
        {
            document.getElementById('wp').value = 1;
            if (a == 'wc') {
                document.getElementById('wl').value = 'all';
            }
            document.getElementById(a).value = v;
        }
    </script>
    {/literal}
    <div>
	{if $countVenues>0}
    	{$aSearches.wp*10-9}-{if $countVenues > $aSearches.wp*10}{$aSearches.wp*10}{else}{$countVenues}{/if} {t}of{/t} {$countVenues}
        {if $aSearches.wp != 1}<a href="#null">&laquo; {t}Prev{/t}</a>{/if}
        {if $countVenues > 10}|{/if}
			{if $aSearches.wp*10 < $countVenues}<a href="#null">{t}Next{/t} &raquo;</a>{/if}
	{else}
		<div class="prTBold prIndentTop">No venues</div>
	{/if}	
    </div>
</div>