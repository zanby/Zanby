	<div class="prInner">
		<ul class="prClr2">
			<li class="prFloatLeft"><a href="{$user->getUserPath('videossearch')}">{t}Search and Browse Videos{/t}</a></li>
			<li>{t}World{/t}</li>
		</ul>
	</div>	

	<div class="prInner">
		<div class="prClr2">
			{foreach item=c key=key name='country' from=$allCountries}
			{if $smarty.foreach.country.iteration % $onCol == 1 || $onCol == 1}
			<ul class="prFloatLeft">{/if}   
			<li>
				{if $c.cnt > 0}
					<a href="{$user->getUserPath('videossearch')}{$type}/country/id/{$key}/">{$c.name}</a>
				{else}
					{$c.name}
				{/if}
			</li>
			{if $smarty.foreach.country.iteration % $onCol == 0}
			</ul>{/if}    
			{/foreach}
		</div>  
	</div>                     