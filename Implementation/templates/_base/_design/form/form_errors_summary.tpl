<div class="prFormErrors">
	{assign var='errTitle' value=0}
    {foreach item=e from=$errors name=iErrors}
        {if $e}
			{if $errTitle==0}
				<h1>{t}Error Message:{/t}</h1>
				{assign var='errTitle' value=1}
			{/if}
			<div class="prIndentBottom">{$e|escape:"html"}</div>
		{/if}
    {/foreach}
</div>