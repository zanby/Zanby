<h3>{t}Browse Groups{/t}</h3>
<a href="/{$LOCALE}/groups/index/">{t}Groups{/t}</a> / <a href="/{$LOCALE}/groups/index/view/allcountries/">{t}World{/t}</a> / <a href="/{$LOCALE}/groups/index/view/allstates/country/{$country->id}/">{$country->name}</a> / <a href="/{$LOCALE}/groups/index/view/allcities/state/{$state->id}/">{$state->name}</a> / 
{$city->name} / <br>
<br>
{t}{tparam value=$city->name}Groups and group categories near %s{/t} <br>
<br>
<a href="#">{t}All Groups{/t}</a>
<table border="1">
<tr>
{foreach item=c name='category' from=$allCategories}
		 {if $smarty.foreach.category.iteration % 7 == 1}
<td>{/if} <a href="#">{$c->name}</a> <br>
  {if $smarty.foreach.category.iteration % 7 == 0}</td>
{/if}	
			{/foreach}
</tr>
</table>			

<h3>{t}Search Results{/t}</h3>
.................<br>
.................<br>
.................<br>
