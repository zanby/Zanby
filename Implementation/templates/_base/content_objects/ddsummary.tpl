<!-- Common container -->
<div class="prCOarea">
	<!-- Target1 -->
       <div id="ddTarget1" class="prCOTarget1">
		{foreach from=$contentBlocksHTML item=current}
			{if $current.target == 1}
			    <div class="prContentObject" {if $current.id}id="{$current.id}"{/if} style="{if $current.Style.backgroundColor}background-color:{$current.Style.backgroundColor}; {/if}
													{if $current.Style.borderColor}border-color:{$current.Style.borderColor}; {/if}
													{if $current.Style.borderStyle}border-style:{$current.Style.borderStyle};{/if}
												">
                    {$current.content}
                </div>
			{/if}
		{/foreach}
	</div>
	<!-- Target2 -->
    <div id="ddTarget2" class="prCOTarget2">
    	{foreach from=$contentBlocksHTML item=current}
			{if $current.target == 2}
				<div class="prContentObject" {if $current.id}id="{$current.id}"{/if} style="{if $current.Style.backgroundColor}background-color:{$current.Style.backgroundColor}; {/if}
													{if $current.Style.borderColor}border-color:{$current.Style.borderColor}; {/if}
													{if $current.Style.borderStyle}border-style:{$current.Style.borderStyle};{/if}
												">
                    {$current.content}
                </div>
			{/if}
		{/foreach}
	</div>
</div>