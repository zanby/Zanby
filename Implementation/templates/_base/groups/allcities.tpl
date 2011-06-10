<div class="prSubContentLeft prClr3">
	<div>		    
		<a href="/{$LOCALE}/groups/index/">{t}Groups{/t}</a> > 
		<a href="/{$LOCALE}/groups/index/view/allcountries/">{t}World{/t}</a> > 
		<a href="/{$LOCALE}/groups/index/view/allstates/country/{$country->id}/">{$country->name}</a> > {$state->name}
	</div>		
	<h2>{t}All Cities{/t}</h2>
		{if $topCities}
		<h3>{t}{tparam value=$state->name}Top %s cities{/t}</h3>
		<table>
		{foreach item = c key = k name = 'cities' from = $topCities}
		  {if $smarty.foreach.cities.iteration % 2 == 1 } <tr>{/if}
		  <td><a href="/{$LOCALE}/groups/search/preset/city/id/{$c.city_id}">{t}{tparam value=$c.city_name}{tparam value=$c.groups_count}%s - %s Groups{/t}</a></td>
		  {if $smarty.foreach.cities.iteration % 2 == 0 } </tr>{/if}
		{/foreach}
		</table>
		{/if}
		<h3>{t}All cities{/t}</h3>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<col width="25%" />
			<col width="25%" />
			<col width="25%" />
			<col width="25%" />
			<tr>
			{foreach item=c key=key name='city' from=$CountGroupsByCities}
			{if $smarty.foreach.city.iteration % $onCol == 1 || $onCol == 1}<td style="vertical-align: top;"><ul>{/if}	
			<li>
			{if $CountGroupsByCities.$key != 0}
			  <a href="/{$LOCALE}/search/groups/preset/new/keywords/{$allCities.$key}/">{$allCities.$key}</a> 
			  {else}{$c}{/if}
			  </li>
			{if $smarty.foreach.city.iteration % $onCol == 0}</ul></td>{/if}	
			{/foreach}
			</tr>              	                                                                                                                                                                                
		</table>  
</div>
<div class="prSubContentRight">
  <a href="{$BASE_URL}/{$LOCALE}/newgroup/"><img alt="Start Group" src="{$AppTheme->images}/buttons/startGroup.gif" /></a>
</div>
