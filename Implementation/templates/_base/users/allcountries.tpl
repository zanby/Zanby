<div class="prSubContentLeft">
	<div>
		<a href="/{$LOCALE}/users/index/">{t}Members{/t}</a> > {t}World{/t}
	</div>
	<h2>{t}All Countries{/t}</h2>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<col width="25%" />
								<col width="25%" />
								<col width="25%" />
								<col width="25%" />
								<tr>
								{foreach item=c key=key name='country' from=$allCountries}
		{if $smarty.foreach.country.iteration % $onCol == 1 || $onCol == 1}<td style="vertical-align: top;"><ul>{/if}	
			<li>{if $CountUsersByCountries.$key != 0}  
			<a href="/{$LOCALE}/users/index/view/allstates/country/{$key}/">{$allCountries.$key}</a> 
				{else}{$c}
			{/if}
			</li>
		{if $smarty.foreach.country.iteration % $onCol == 0}
		</ul>
		{/if}	
		{/foreach}
		</tr>
	</table> 
</div>