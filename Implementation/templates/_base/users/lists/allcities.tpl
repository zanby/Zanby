<ul class="prClr2 prInner">
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('listssearch')}">{t}Search and Browse Lists{/t}</a></li>
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('listssearch')}view/countries/">{t}World{/t}</a></li>
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('listssearch')}view/allstates/country/{$objCountry->id}/">{$objCountry->name}</a></li>
    <li class="prFloatLeft">{$objState->name}</li>
</ul>
<div class="prInner">
	{foreach item=c key=key name='city' from=$allCities}
		{if $smarty.foreach.city.iteration % $onCol == 1 || $onCol == 1}
		<ul class="prFloatLeft">{/if}   
		<li>
			{if is_array($c)}
				<a href="{$currentUser->getUserPath('listssearch')}preset/city/id/{$key}/">{$c.name}</a> {*({$c.cnt})*} 
			{else}
				{$c}
			{/if}
		</li>
		{if $smarty.foreach.city.iteration % $onCol == 0}
		</ul>
		{/if}    
	{/foreach}	
</div>                     