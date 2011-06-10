{tab template="tabs1" active=$active style="margin: 0px 0px 0px -1px;"}

{if $currentGroup->getGroupType() == "family"}
	{if $active == "group_settings"}
		{tabitem link=$CurrentGroup->getGroupPath('settings') name="group_settings"}{t}Settings{/t}{/tabitem}
	{else}
		{tabitem link=$CurrentGroup->getGroupPath('settings') name="group_settings" first="first"}{t}Settings{/t}{/tabitem}
	{/if}
	{tabitem link=$CurrentGroup->getGroupPath('hierarchy') name="hierarchy"}{t}Hierarchy{/t}{/tabitem}
	{tabitem link=$CurrentGroup->getGroupPath('brandgallery') name="brandgallery"}{t}Brand Gallery{/t}{/tabitem}
  	{tabitem link=$CurrentGroup->getGroupPath('invite1') name="invitations"}{t}Invitations{/t}{/tabitem}
{else}
	{if $active == "group_settings"}
		{tabitem link=$CurrentGroup->getGroupPath('settings') name="group_settings"}{t}Settings{/t}{/tabitem}
	{else}
		{tabitem link=$CurrentGroup->getGroupPath('settings') name="group_settings" first="first"}{t}Settings{/t}{/tabitem}
	{/if}
   	{tabitem link=$CurrentGroup->getGroupPath('webbadges') name="brandgallery"}{t}Promotion{/t}{/tabitem}
{/if}	
	
	{if $active == "avatars"}
		{tabitem link=$CurrentGroup->getGroupPath('avatars') name="avatars"}{t}Family Photos{/t}{/tabitem}
	{else}
		{tabitem link=$CurrentGroup->getGroupPath('avatars') name="avatars" last="last"}{t}Family Photos{/t}{/tabitem}
	{/if}
{/tab}