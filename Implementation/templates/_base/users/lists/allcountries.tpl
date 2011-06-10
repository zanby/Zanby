<ul class="prClr2 prInner">
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('listssearch')}">{t}Search and Browse Lists{/t}</a></li>
    <li class="prFloatLeft">{t}World{/t}</li>
</ul>
<div class="prInner">
	{foreach item=c key=key name='country' from=$allCountries}
	{if $smarty.foreach.country.iteration % $onCol == 1 || $onCol == 1}	
	<ul class="prFloatLeft">{/if}   
	<li>
		{if $preset_country}
			{if is_array($c)}
				<a href="{$currentUser->getUserPath('listssearch')}view/allstates/country/{$key}/">{$c.name}</a> {*({$c.cnt})*} 
			{else}
				{$c}
			{/if}
		{else}
			{if is_array($c)}
				<a href="{$currentUser->getUserPath('listssearch')}preset/country/id/{$key}/">{$c.name}</a> {*({$c.cnt})*} 
			{else}
				{$c}
			{/if}
		{/if}
	</li>
	{if $smarty.foreach.country.iteration % $onCol == 0}</ul>{/if}    
	{/foreach}
</div>                     