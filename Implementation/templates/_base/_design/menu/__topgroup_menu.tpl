
    {tab template="tabs2" active=$active}
        {tabitem link=$currentGroup->getGroupPath("summary") name="summary" first="first"}{t}Summary{/t}{/tabitem}
        
        {if $user->isAuthenticated()}
	        {if !$CurrentGroup->isPrivate() || $CurrentGroup->getMembers()->isMemberExistsAndApproved($user->getId())}
		        {tabitem link=$currentGroup->getGroupPath("members") name="members"}{t}Members{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("discussion") name="discussion"}{t}Discussions{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("photos") name="photos"}{t}Photos{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("videos") name="videos"}{t}Videos{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("lists") name="lists"}{t}Lists{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("calendar.list.view") name="events"}{t}Events{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("documents") name="documents"}{t}Documents{/t}{/tabitem}
	        {/if}
	        
	        {if $CurrentGroup->getMembers()->isHost($user->getId()) || $CurrentGroup->getMembers()->isCohost($user->getId()) }
	           {tabitem link=$currentGroup->getGroupPath("settings") name="settings"}{t}Tools{/t}{/tabitem}
	        {/if}
	    {else}
	        {if !$CurrentGroup->isPrivate()}
		        {tabitem link=$currentGroup->getGroupPath("members") name="members"}{t}Members{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("discussion") name="discussion"}{t}Discussions{/t}{/tabitem}        
		        {tabitem link=$currentGroup->getGroupPath("photos") name="photos"}{t}Photos{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("videos") name="videos"}{t}Videos{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("lists") name="lists"}{t}Lists{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("calendar.list.view") name="events"}{t}Events{/t}{/tabitem}
		        {tabitem link=$currentGroup->getGroupPath("documents") name="documents"}{t}Documents{/t}{/tabitem}
	        {/if}
        {/if}
    {/tab} 
 