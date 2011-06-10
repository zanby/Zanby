<ul class="prClr2 prInner">
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}">{t}Search and Browse Events{/t}</a></li>
    <li class="prFloatLeft"><a href="{$currentUser->getUserPath('calendarsearchindex')}view/countries/">{t}World{/t}</a></li>
    <li class="prFloatLeft">{$objCountry->name}</li>
</ul>
<div class="prInner">
	<div class="prClr2">

    <h3 class="prInnerTop" style="margin-bottom:5px;">{t}Note: States associated with events are displayed at the list below{/t}</h3>

	{foreach item=s key=key name='state' from=$allStates}
        {if $smarty.foreach.state.iteration % $onCol == 1 || $onCol == 1}
            <ul class="prFloatLeft">
        {/if}
            <li>
                <a href="{$currentUser->getUserPath('calendarsearchindex')}view/allcities/state/{$s.id}/">{$s.name}</a>
            </li>
        {if $smarty.foreach.state.iteration % $onCol == 0}
            </ul>
        {/if}
    {/foreach}
	</div>
</div>