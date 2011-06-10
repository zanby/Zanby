<ul class="prClr2 prInner">
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}">{t}Search and Browse Events{/t}</a></li>
    <li class="prFloatLeft">{t}World{/t}</li>
</ul>
<div class="prInner">
	<div class="prClr2">

    <h3 class="prInnerTop" style="margin-bottom:5px;">{t}Note: Countries associated with events are displayed at the list below{/t}</h3>

	{foreach item=c key=key name='country' from=$allCountries}
	{if $smarty.foreach.country.iteration % $onCol == 1 || $onCol == 1}
	<ul class="prFloatLeft">
	{/if}
		<li>
		{if $preset_country}
            <a href="{$currentUser->getUserPath('calendarsearchindex')}view/allstates/country/{$c.id}/">{$c.name}</a>
		{else}
            <a href="{$currentUser->getUserPath('calendarsearch')}preset/country/id/{$c.id}/">{$c.name}</a> {*({$c.cnt})*}
		{/if}
		</li>
	{if $smarty.foreach.country.iteration % $onCol == 0}
	</ul>
	{/if}
	{/foreach}
	</div>
</div>