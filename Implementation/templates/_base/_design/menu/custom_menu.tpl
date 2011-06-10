<div class="top_line">
{foreach from=$tabs item=tab name=outer key=k}
    {if $tab.active}
    	{if $smarty.foreach.outer.first}
	<div class="tab_start">
    	<div class="tab_center_start">&nbsp;</div>
	    <div class="justify"></div>
	</div>
    	{/if}
	<div class="tab_active">
    	<div class="tab_active_left"></div>
        <div class="tab_active_center"><a href="{$tab.url}"><nobr>{$tab.title}</nobr></a></div>
        <div class="tab_active_right"></div>
        <div class="justify"></div>
	</div>        
    {else}
    	{if $smarty.foreach.outer.last}
	<div class="tab_start">
    	<div class="tab_center"><a href="{$tab.url}"><nobr>{$tab.title}</nobr></a>{if $tab.separator == 1}<img  src="{$AppTheme->images}/decorators/menu_tab3_marker.gif" alt="" width="1" height="16" border="0"/>{/if}</div>
        <div class="tab_right"></div>
	</div>        	
    	{else} 
	<div class="tab_start">
    	<div class="tab_center"><a href="{$tab.url}"><nobr>{$tab.title}</nobr></a>{if $tab.separator == 1}<img  src="{$AppTheme->images}/decorators/menu_tab3_marker.gif" alt="" width="1" height="16" border="0"/>{/if}</div>
        <div class="justify"></div>
	</div>        	
        {/if}
	{/if}
{/foreach}
</div>      

