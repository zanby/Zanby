<div class="prSubContentLeft">
	<div>		    
		<a href="/{$LOCALE}/users/index/">{t}Members{/t}</a> > <a href="/{$LOCALE}/users/index/view/allcountries/">{t}World{/t}</a> > {$country->name}
	</div>		
	<h2>{t}All States{/t}</h2>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		    <col width="25%" />
		    <col width="25%" />
		    <col width="25%" />
		    <col width="25%" />
		    <tr>
		       {foreach item=s key=key name='state' from=$allStates}
		          {if $smarty.foreach.state.iteration % $onCol == 1 || $onCol == 1}
		          <td style="vertical-align: top;">
		            <ul>
		          {/if}	
		               <li>
		                   {if $CountUsersByStates.$key != 0}
		                         <a href="/{$LOCALE}/users/index/view/allcities/state/{$key}/">{$s}</a> 
		                   {else}
		                         {$s}
		                   {/if}
		              </li>
					  {if $smarty.foreach.state.iteration % $onCol == 0}
					    </ul></td>
					  {/if}	
		      {/foreach}
			</tr>
		</table>  
</div>                