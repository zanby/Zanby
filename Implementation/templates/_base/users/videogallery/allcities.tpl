<div class="prInner">
	<ul class="prClr2">
		<li class="prFloatLeft"><a href="{$currentUser->getUserPath('videossearch')}">{t}Search and Browse Videos{/t}</a></li>
		<li class="prFloatLeft"><a href="{$currentUser->getUserPath('videossearch')}view/cities/">{t}World{/t}</a></li>
		<li class="prFloatLeft"><a href="{$currentUser->getUserPath('videossearch')}view/country/id/{$country->id}/">{$country->name}</a></li>
		<li class="prFloatLeft">{$state->name}</li>
	</ul>
</div>
<div class="prInner">
	<table class="prForm">
		<col width="25%" />
		<col width="25%" />
		<col width="25%" />
		<col width="25%" />
		<tr> {foreach item=c key=key name='city' from=$allCities}
			{if $smarty.foreach.city.iteration % $onCol == 1 || $onCol == 1}
			<td class="prVTop"><ul>
					{/if}
					<li> {if $c.cnt > 0} <a href="{$user->getUserPath('videossearch')}{$type}/city/id/{$key}/">{$c.name}</a> {else}
						{$c.name}
						{/if} </li>
					{if $smarty.foreach.city.iteration % $onCol == 0}
				</ul></td>
			{/if}    
			{/foreach} </tr>
	</table>
</div>
