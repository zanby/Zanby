{if $CurrentGroup}
    {if $user->isAuthenticated()} 
        <div class="znFloatLeft znWidgetInner3 znHeaderGButton">
    	{if $CurrentGroup->getGroupType() == "family"}
    		{if $CurrentGroup->getHost()->getId() == $user->getId()}
    			{if $user->getGroups()->setMembersRole('host')->setTypes('simple')->setExcludeIds($CurrentGroup->getGroups()->setAssocValue('zgi.id')->returnAsAssoc()->getList())->getCount()>0}
    				<a href="{$currentGroup->getGroupPath('joinfamilygroup')}" class="znbJoinFamilyGroupButton">&nbsp;</a>
    			{/if}
    			<img src="{$AppTheme->images}/decorators/bkgOwner.gif" />
    		{elseif $CurrentGroup->getMembers()->isCohost($user->getId())} 
                {if $user->getGroups()->setMembersRole('host')->setTypes('simple')->setExcludeIds($CurrentGroup->getGroups()->setAssocValue('zgi.id')->returnAsAssoc()->getList())->getCount()>0}
                    <a href="{$currentGroup->getGroupPath('joinfamilygroup')}" class="znbJoinFamilyGroupButton">&nbsp;</a>
                {/if}        
                <img src="{$AppTheme->images}/decorators/bkgCoOwner.gif" />
    		{elseif $user->getGroups()->setMembersRole('host')->setTypes('simple')->setExcludeIds($CurrentGroup->getGroups()->setAssocValue('zgi.id')->returnAsAssoc()->getList())->getCount()>0}
    			<a href="{$currentGroup->getGroupPath('joinfamilygroup')}" class="znbJoinFamilyGroupButton">&nbsp;</a>
    		{/if}
    	{else}
    		{if $CurrentGroup->getHost()->getId() == $user->getId()}<img src="{$AppTheme->images}/decorators/bkgHost.gif" />
    		{elseif $CurrentGroup->getMembers()->isCohost($user->getId())} <img src="{$AppTheme->images}/decorators/bkgCoHost.gif" />
    		{elseif $CurrentGroup->getMembers()->isMemberExistsAndApproved($user->getId())}<img src="{$AppTheme->images}/decorators/bkgMember.gif" />
    		{else}{*<a href="{$currentGroup->getGroupPath('joingroup')}" class="znbJoinGroupButton">&nbsp;</a>*}
    		{/if}
    	{/if}
    	</div>
    	<span class="znHeaderButtons">
    		{if $CurrentGroup->getHost()->getId()}
    			{if !$disableEmail}
    				<a href="#null" onclick="xajax_sendMessage({$CurrentGroup->getHost()->getId()}); return false;" class="znEmail" title="send message to {if $CurrentGroup->getGroupType() == 'family'} owner{elseif $CurrentGroup->getGroupType() == 'simple'} host{/if}">&nbsp;</a>
                {/if}
    		{/if}
    		{if !$disablePrint}
    			<a href="#null" class="znPrint" title="print this page" onclick="window.print(); return false;">&nbsp;</a>
    		{/if}
    		{if !$disableBookmark}
    			<a href="#null" onclick="xajax_bookmarkit()" class="znBookmark" title="bookmark this page">&nbsp;</a> 
    		{/if}
    		{if !$disableRss}
    			<a href="/rss/{$module}/" class="znRSS">&nbsp;</a>
    		{/if}
    	</span>
    {/if}
{else}
	<div class="znHeaderLink znFloatLeft znHeaderLink">
        {if $user && $currentUser && $user->getId() && $currentUser->getId() && $currentUser->getId() != $user->getId() && $user->getPrivacy()->getSrViewAddToFriend()}
	        {if isset($friendsAssoc) }
			    {if !in_array($user->getId(), $friendsAssoc)}
                    {assign var="currentUserID" value=$currentUser->getId()}
					{t var='button_01'}+ Add to Friends{/t}
					{linkbutton name=$button_01 onclick="xajax_addToFriends($currentUserID); return false;" link="#null"}
	            {/if}
			{/if}        
        {else}
			{if $currentUser && $user && $currentUser->getId() == $user->getId() && isset($module) && "profile" == $module}
				{t var='button_02'}Open template editor{/t}
				{linkbutton name="button_02" link=$currentUser->getUserPath('compose')}
			{/if}        
        {/if}
	</div>
	<span class="znHeaderButtons">
	{if $user && $user->getId()}
		{if $user && $user->getId() && $currentUser && $currentUser->getId() && $currentUser->getId() != $user->getId()}
            {*displayemailicon user=$user currentUser=$currentUser*}
		{/if}
		{if !$disablePrint}
			<a href="#null" class="znPrint" title="print this page" onclick="window.print(); return false;">&nbsp;</a>
		{/if}
		{if !$disableBookmark}
			<a href="#null" onclick="xajax_bookmarkit()" class="znBookmark" title="bookmark this page">&nbsp;</a> 
		{/if}
		{if !$disableRss}
			<a href="/rss/{$module}/" class="znRSS">&nbsp;</a>
		{/if}
	{/if}
	</span>
{/if}    