<div class="prSubContentLeft">
	<div>		    
		<a href="/{$LOCALE}/groups/index/">{t}Groups{/t}</a> > <a href="/{$LOCALE}/groups/index/view/allcountries/">{t}World{/t}</a> > {$country->name}
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
		                   {if $CountGroupsByStates.$key != 0}
		                         <a href="/{$LOCALE}/groups/index/view/allcities/state/{$key}/">{$s}</a> 
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
   
<div class="prSubContentRight">
  <a href="{$BASE_URL}/{$LOCALE}/newgroup/"><img alt="Start Group" src="{$AppTheme->images}/buttons/startGroup.gif" /></a>
</div>
