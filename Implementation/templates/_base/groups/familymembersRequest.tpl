    {if $order}{assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
    {else}{assign var="orderPath" value=''}{/if}

    {*tab template="tabs1" active="pending"}
      {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' name="approve"}&nbsp;{t}Members{/t}{/tabitem}
      {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' name="pending" last="last"}{t}Pending Members{/t}&nbsp;{/tabitem}
    {/tab*}
    
	<div class="prInner prClr2">	
        <h2 class="prFloatLeft">{t}Membership Request{/t}</h2>

        {assign var="member" value=$membersList->getList()}
        <div class="prTRight prInnerTop prInnerBottom prClr2">  
        	{if $prevId}
        		{assign var="userAfterExclude" value=$prevId}
        		&laquo;<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$prevId}">{t}Previous{/t}</a> 
        	{else}
        	{/if}
        	{if $nextId}
        		{if !$userAfterExclude} 
        			{assign var="userAfterExclude" value=$nextId}
        		{/if}
        		<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$nextId}">{t}Next{/t}</a> &raquo; 
        	{else}
        	{/if}
        <a href="{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/">{t}Back to Requests List{/t}</a> 
        </div>

        <div class="prGrayBorder prInner">
      
        	{assign var='request' value=$CurrentGroup->getRequestRelation($member.0)}
            <div>
                <div class="prFloatLeft">
                    <div class="prFloatLeft prInnerRight">
                        <a href="{$member.0->getGroupPath('summary')}"><img src="{$member.0->getAvatar()->getSmall()}" alt=""/></a>
                    </div>
                    <div>
                        <a href="{$member.0->getGroupPath('summary')}">{$member.0->getName()|escape:"html"}</a><br />
                        {$member.0->getCity()->name|escape:"html"},&nbsp;{$member.0->getState()->name|escape:"html"}
                    </div>
                </div>
                <div class="prFloatRight">
                     {t}Requested to join group on{/t}<br />
                     {$request->requestDate|date_locale:'DATE_MEDIUM'}
                </div>
            </div>
            <div class="prInnerTop prInnerBottom prClr2">
                {t}Note:{/t}
                <div class="prInnerSmallTop">{$request->getBody()|wordwrap:20:' ':true|nl2br}</div>
            </div>
        </div>

        <div class="prInnerTop prInnerBottom prTRight">
		{if $userAfterExclude}
			{t var="in_button"}Accept{/t}{linkbutton name=$in_button link=$CurrentGroup->getGroupPath('members')|cat:'mode/request'|cat:$paging_link|cat:'/accept/'|cat:$member.0->getId()|cat:'/id/'|cat:$userAfterExclude|cat:'/'} &#160;
			{t var="in_button_2"}Decline{/t}{linkbutton name=$in_button_2 link=$CurrentGroup->getGroupPath('members')|cat:'mode/request'|cat:$paging_link|cat:'/decline/'|cat:$member.0->getId()|cat:'/id/'|cat:$userAfterExclude|cat:'/'} 
		{else}
			{t var="in_button_3"}Accept{/t}{linkbutton name=$in_button_3 link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending'|cat:$paging_link|cat:'/accept/'|cat:$member.0->getId()|cat:'/'} &#160;
			{t var="in_button_4"}Decline{/t}{linkbutton name=$in_button_4 link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending'|cat:$paging_link|cat:'/decline/'|cat:$member.0->getId()|cat:'/'}      
		{/if} 
        </div>
    </div>
