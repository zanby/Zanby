<ul class="prClr2 prInner">
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}">{t}Search and Browse Evets{/t}</a></li>
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}view/countries/">{t}World{/t}</a></li>
    {if $objCountry}
        <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}view/allstates/country/{$objCountry->id}/">{$objCountry->name}</a></li>
        {if $objState}
            <li class="prFloatLeft">{$objState->name}</li>
        {/if}
    {else}
        <li class="prFloatLeft">{t}All Cities{/t}</li>
    {/if}
</ul>
<div class="prInner">
	<div class="prClr2">

    <h3>{t}Note: Cities associated with events are displayed at the list below{/t}</h3>

	{foreach item=c key=key name='city' from=$allCities}
	{if $smarty.foreach.city.iteration % $onCol == 1 || $onCol == 1}
	<ul class="prFloatLeft">
	{/if}
		<li>
			<a href="{$currentUser->getUserPath('calendarsearch')}preset/city/id/{$c.id}/">{$c.name}</a> {*({$c.cnt})*}
		</li>
	{if $smarty.foreach.city.iteration % $onCol == 0}
	</ul>
	{/if}
	{/foreach}
	</div>
</div>