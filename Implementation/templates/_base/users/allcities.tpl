<div class="prSubContentLeft">
	<div>		    
		<a href="/{$LOCALE}/users/index/">{t}Members{/t}</a> > 
		<a href="/{$LOCALE}/users/index/view/allcountries/">{t}World{/t}</a> > 
		<a href="/{$LOCALE}/users/index/view/allstates/country/{$country->id}/">{$country->name}</a> > {$state->name}
	</div>		
	<h2>{t}All Cities{/t}</h2>

		{if $topCities}
		<h3>{t}{tparam value=$state->name}Top %s cities{/t}</h3>
		<table>
		{foreach item = c key = k name = 'cities' from = $topCities}
		  {if $smarty.foreach.cities.iteration % 2 == 1 } <tr>{/if}
		  <td><a href="/{$LOCALE}/search/members/preset/new/keywords/{$c.city_name}">{t}{tparam value=$c.city_name}{tparam value=$c.users_count}%s - %s Users{/t}</a></td>
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
		{foreach item=c key=key name='city' from=$CountUsersByCities}
		{if $smarty.foreach.city.iteration % $onCol == 1 || $onCol == 1}<td style="vertical-align: top;"><ul>{/if}	
		<li>
		{if $CountUsersByCities.$key != 0}
		  <a href="/{$LOCALE}/search/members/preset/new/keywords/{$allCities.$key}">{$allCities.$key}</a> 
		  {else}{$c}{/if}
		  </li>
		{if $smarty.foreach.city.iteration % $onCol == 0}</ul></td>{/if}	
		{/foreach}
		</tr>              	                                                                                                                                                                                
		</table>  
</div>         