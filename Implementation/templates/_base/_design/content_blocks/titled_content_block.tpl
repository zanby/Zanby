<div class="znRamp"><div class="znRamp-inner"><div class="znRamp-outer">
	<div class="znContentHeadline znClearContainer3">
		<h1 class="znFloatLeft">{$title}</h1>
		<div class="znHeaderTools znFloatRight">
			{if $addLink}
			    {if $addLink|@is_array}
                    {foreach from=$addLink item=link key=name}
						{if $name|@is_numeric && $addLinkName.$name}
						    {linkbutton name=$addLinkName.$name link=$link}&nbsp;
						{else}
							{linkbutton name=$name link=$link}&nbsp;
						{/if}
                    {/foreach}
				{else}
				    <div class="znHeaderLink znFloatLeft znHeaderLink">
                        {linkbutton name=$addLinkName link=$addLink html=$html htmlPosition=$htmlPosition onclick=$onclick}
                    </div>
                {/if}
			{/if}
			{if (isset($module))}
				{include file="_design/menu/topgroup_buttons.tpl" module=$module disablePrint=$disablePrint disableBookmark=$disableBookmark disableRss=$disableRss disableEmail=$disableEmail addLink=$addLink}
			{/if}
		</div>
	</div>
{$content}
</div></div></div>
