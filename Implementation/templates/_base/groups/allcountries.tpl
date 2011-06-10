<div class="prSubContentLeft">
	<div>
		<a href="/{$LOCALE}/groups/index/">{t}Groups{/t}</a> > {t}World{/t}
	</div>
	<h2>{t}All Countries{/t}</h2>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<col width="25%" />
								<col width="25%" />
								<col width="25%" />
								<col width="25%" />
								<tr>
								{foreach item=c key=key name='country' from=$allCountries}
		{if $smarty.foreach.country.iteration % $onCol == 1 || $onCol == 1}<td style="vertical-align: top;"><ul>{/if}	
		<li>    
		{if $CountGroupsByCountries.$key != 0}
		<a href="/{$LOCALE}/groups/index/view/allstates/country/{$key}/">{$allCountries.$key}</a>   
		{else}{$c}
		{/if}</li>
		{if $smarty.foreach.country.iteration % $onCol == 0}</ul></td>{/if}	
		{/foreach}
		</tr>
	</table> 
</div> 
   
<div class="prSubContentRight">
  <a href="{$BASE_URL}/{$LOCALE}/newgroup/"><img alt="Start Group" src="{$AppTheme->images}/buttons/startGroup.gif" /></a>
</div>
