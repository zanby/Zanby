	<div class="prInner">
		<ul class="prClr2">
			<li class="prFloatLeft"><a href="{$currentUser->getUserPath('videossearch')}">{t}Search and Browse Videos{/t}</a></li>
			<li class="prFloatLeft"><a href="{$currentUser->getUserPath('videossearch')}view/cities/">{t}World{/t}</a></li>
			<li>{$country->name}</li>
		</ul>
	</div>	

	<div class="prInner">
		<div class="prClr2">
			{foreach item=c key=key name='state' from=$allStates}
			{if $smarty.foreach.state.iteration % $onCol == 1 || $onCol == 1}
			<ul class="prFloatLeft">{/if}   
			<li>
				{if $c.cnt > 0}
					<a href="{$user->getUserPath('videossearch')}{$type}/state/id/{$key}/">{$c.name}</a>
				{else}
					{$c.name}
				{/if}
			</li>
			{if $smarty.foreach.state.iteration % $onCol == 0}
			</ul>{/if}    
			{/foreach}
		</div> 
	</div>                     