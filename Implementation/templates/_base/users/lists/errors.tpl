{if is_array($record)}
  {if $record.errors}
    <div class="prFormErrors">
	{assign var='errTitle' value=0}
	{foreach from=$record.errors item=e}
		{if $errTitle==0}
			<h1>{t}Error Message:{/t}</h1>
			{assign var='errTitle' value=1}
		{/if}
		<div class="prIndentBottom">{$e|escape:"html"}</div>
	{/foreach}
	</div>    
  {/if}
{else}
  {if $record->errors}
    <div class="prFormErrors prIndentBottom">
	{assign var='errTitle' value=0}
	{foreach from=$record->errors item=e}
		{if $errTitle==0}
			<h1>{t}Error Message:{/t}</h1>
			{assign var='errTitle' value=1}
		{/if}
		<div class="prIndentBottom">{$e|escape:"html"}</div>
	{/foreach}
	</div>   
  {/if}
{/if}