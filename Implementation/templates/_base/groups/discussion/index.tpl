{literal}
<script src="/js/discussion/index.js"></script>
<script src="/js/discussion/search.js"></script>
{/literal}

	{assign var="canMarkGroupTopicsRead" value=$CurrentGroup->getDiscussionAccessManager()->canMarkGroupTopicsRead($CurrentGroup->getId(), $user->getId())}
	{assign var="canViewRecentMessages" value=$CurrentGroup->getDiscussionAccessManager()->canViewRecentMessages($CurrentGroup->getId(), $user->getId())}
	{assign var="canGroupRSSFeed" value=$CurrentGroup->getDiscussionAccessManager()->canGroupRSSFeed($CurrentGroup->getId(), $user->getId())}
	<div class="prClr3 prButtonPanel prIndentBottom">
		<form id="searchForm" action="{$CurrentGroup->getGroupPath('discussionsearch')}" method="post" class="prInner">
			<div class="prFloatLeft">
				{if $canMarkGroupTopicsRead}
					<a href="{$CurrentGroup->getGroupPath('markalltopicsread')}">{t}Mark all topics read{/t}</a> 
					{if $canViewRecentMessages || $canGroupRSSFeed}|{/if}
				{/if}
				{if $canViewRecentMessages}
					{if $recentMessages}
						<a href="{$CurrentGroup->getGroupPath('recenttopic')}">{t}{tparam value=$recentMessages}Recent Messages (%s){/t}</a>
					{else}
						<span>{t}{tparam value=$recentMessages}Recent Messages (%s){/t}</span>
					{/if}
				{/if} 
			</div>
			<div class="prFloatRight">
				<input type="hidden" name="_wf_" value="1" class="prIEFixInput" />
				<input type="text" name="keywords" id="keywordsStr" value="{t}Keyword Search{/t}" onfocus="initSearch(); return false;" class="prIEFixInput prDisSearchField" /> {t var="in_button"}Go{/t}{linkbutton name=$in_button onclick="searchSubmit(); return false;"}
			</div>
		</form>
	</div>
		{foreach from=$groups item=group}
				{if $CurrentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($group->getId(), $user->getId())}
				{foreach from=$group->getDiscussionGroupDiscussions()->findByGroupId($group->getDiscussionGroupId()) item=discussion}
					{assign var="discussionID" value=$discussion->getId()}
					{if $discussion->getDiscussionAccessManager()->canViewDiscussion($discussion, $user->getId()) }
<!-- START GROUP BLOCK -->			
					{TitlePane id="Discussion_"|cat:$discussion->getId() showContent=1}
						{if $discussion->hasTopics()}
							{TitlePane_Title}							
								<span class="{if $discussion->isHot()}  
                                                {if $postList->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
                                                        prHotDiscussionNew  
                                                    {else}
                                                        prHotDiscussionNoNew  
                                                {/if}
                                            {else}
                                                {if $postList->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
                                                        prDiscussionNew  
                                                    {else}
                                                        prDiscussionNoNew  
                                            {/if}
                                        {/if}">
								<a href="#null" class="toggel-link">{$discussion->getTitle()|escape:html}</a>
								</span>
							{/TitlePane_Title}
						{else}
							{TitlePane_Title}		
								<img class="prIndentRight" src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif">{$discussion->getTitle()|escape:html}
							{/TitlePane_Title}
						{/if}
						{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
						{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
						{TitlePane_ToggleCallback type='show' request_type='ajax'}show_hideDiscussionContent({$discussion->getId()});{/TitlePane_ToggleCallback}
						{TitlePane_ToggleCallback type='hide' request_type='ajax'}show_hideDiscussionContent({$discussion->getId()});{/TitlePane_ToggleCallback}						
						{TitlePane_Note}

                            {if $currentGroup->getDiscussionGroupSettings()->getDiscussionStyle() eq 1}
                                <div class="prHHmailBx">{$discussion->getFullEmail()|escape}</div>
                            {elseif $currentGroup->getDiscussionGroupSettings()->getDiscussionStyle() eq 2}
                                <div class="prHHmailBx">{t}Web only. Email not enabled for this discussion.{/t}</div>
                            {/if}

							<div class="prClr prInnerLeft prInnerRight prIndentBottom">
							{assign var="canCreateDiscussionTopic" value=$discussion->getDiscussionAccessManager()->canCreateDiscussionTopic($discussion, $user->getId())}
							{assign var="canDiscussionRSSFeed" value=$discussion->getDiscussionAccessManager()->canDiscussionRSSFeed($discussion, $user->getId())}
							{assign var="canMarkDiscussionTopicsRead" value=$discussion->getDiscussionAccessManager()->canMarkDiscussionTopicsRead($discussion, $user->getId())}
								<div class="prFloatLeft"> 
									{if $canCreateDiscussionTopic}<a href="{$CurrentGroup->getGroupPath('createtopic')|cat:'discussion/'|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a> {if $canDiscussionRSSFeed}<span>&nbsp;|&nbsp;</span>{/if}{/if}
									{if $canMarkDiscussionTopicsRead}<a href="{$CurrentGroup->getGroupPath('markalltopicsread')|cat:'discussionid/'|cat:$discussion->getId()|cat:'/'}">{t}Mark all topics read{/t}</a>{/if} 
									{if $canDiscussionRSSFeed}<a style="text-decoration:none;display:none;" href="/rss/discussion/id/{$discussion->getId()}/">&nbsp;</a>{/if}
								</div>                                
								<div class="prFloatRight prText5">{t}{tparam value=$discussion->getTopicsCount()}{tparam value=$discussion->getPostsCount()}%s topics, %s posts{/t}
								</div>
							</div>
							<h3>{$discussion->getDescription()|escape:"html"|longwords:40}</h3>
						{/TitlePane_Note}
						{TitlePane_Content}
							<div id="Discussion{$discussion->getId()}Content">
								{if $discussion->hasTopics()}
									{if $discussion->getTopicsCount() > 10}
										<script language="javascript">DynamicContentDivsId[DynamicContentDivsId.length] = {$discussion->getId()};</script>
									{/if}
									
									<div id="Discussion{$discussion->getId()}TopicList" class="prDiscussionTopicList">                          
										<table cellpadding="0" cellspacing="0" border="0" class="prDiscussionTopics" id="Discussion{$discussion->getId()}TopicListDiv">
											<col width="8%" />
											<col width="72%" />
											<col width="20%" />
											{foreach name=FTopics from=$discussion->getTopics()->setCurrentPage(0)->setListSize(10)->findByDiscussionId($discussion->getId()) item=topic}    
												{include file="groups/discussion/index.topic.tr.template.tpl" topic=$topic discussion=$discussion}
											{/foreach}
										</table>
									</div>                                
									<div class="prInner"> 
										{if $canCreateDiscussionTopic}<a href="{$CurrentGroup->getGroupPath('createtopic')|cat:'discussion/'|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a> {if $canMarkDiscussionTopicsRead}<span>&nbsp;|&nbsp;</span>{/if}{/if} 
										{if $canMarkDiscussionTopicsRead}<a href="{$CurrentGroup->getGroupPath('markalltopicsread')|cat:'discussionid/'|cat:$discussion->getId()|cat:'/'}">{t}Mark all topics read{/t}</a>{/if} 
									</div>
								{else}
									<div id="Discussion{$discussion->getId()}TopicList" class="prDiscussionTopicList">                          
										<table cellpadding="0" cellspacing="0" border="0" class="prDiscussionTopics" id="Discussion{$discussion->getId()}TopicListDiv">
											<col width="8%" />
											<col width="72%" />
											<col width="20%" />
											<tr>
											<td>&nbsp;</td>
											<td colspan="2">{t}There are no topics{/t}</td>
											</tr>
										</table>
									</div>                                
									<div class="prInner"> 
										{if $canCreateDiscussionTopic}<a href="{$CurrentGroup->getGroupPath('createtopic')|cat:'discussion/'|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a>{/if} 
									</div>
								{/if}
							</div>
						{/TitlePane_Content}
					{/TitlePane}						
 <!-- END GROUP BLOCK -->

					{/if}
				{/foreach}
				{/if}
		{/foreach}
		
		{foreach from=$subGroups item=group}
			{if $group->getDiscussionGroupType() == 'simple'}
			{if $CurrentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($group->getId(), $user->getId()) && $CurrentGroup->getDiscussionAccessManager()->canPublishToFamily($group->getId(), $CurrentGroup->getId())}
				
				<!-- START SUB_GROUPS BLOCK -->

                <div class="prDiscussionBox">
                        <div class="prClr3">
							<h3 class="prFloatLeft"><a href="#null" onclick="show_hideGroupContent({$group->getId()});return false;">{$group->getName()|escape:"html"}</a></h3>
                            <div class="prFloatRight prInnerSmall">
                                <div class="prText5">
                                    {if $group->getDiscussionsCount() != 1}{t}{tparam value=$group->getDiscussionsCount() }%s discussions{/t}{else}{t}{tparam value=$group->getDiscussionsCount() }%s discussion{/t}{/if},
                                    {if $group->getTopicsCount() != 1}{t}{tparam value=$group->getTopicsCount() }%s topics{/t}{else}{t}{tparam value=$group->getTopicsCount() }%s topic{/t}{/if},
                                    {if $group->getPostsCount() != 1}{t}{tparam value=$group->getPostsCount() }%s posts{/t}{else}{t}{tparam value=$group->getPostsCount() }%s post{/t}{/if}
                                </div>
                            </div>
						</div>
						
						<div class="prInnerSmall" {if !$openSubgroups[$groupID]}style="display:none;"{/if} id="SUBGROUP{$group->getId()}">
                            {foreach from=$group->getDiscussionGroupDiscussions()->findByGroupId($group->getDiscussionGroupId()) item=discussion}	   
                                {assign var="discussionID" value=$discussion->getId()}
                                {if $discussion->getDiscussionAccessManager()->canViewDiscussion($discussion, $user->getId()) }
        
                                <!-- START SUB_GROUP DISCUSSION BLOCK -->   
								{TitlePane id="SubGroup_"|cat:$discussion->getId() showContent=1}						
								{if $discussion->hasTopics()}
									{TitlePane_Title}							
											<span class="{if $discussion->isHot()}  
												{if $postList->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
														prHotDiscussionNew  
													{else}
														prHotDiscussionNoNew  
												{/if}
												{else}
													{if $postList->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
															prDiscussionNew  
														{else}
															prDiscussionNoNew  
												{/if}
											{/if}">
											<a href="#null" class="toggel-link">{$discussion->getTitle()|escape:html}</a>
											</span>
									{/TitlePane_Title}
								{else}
									{TitlePane_Title}		
										<img src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" class="prIndentRightSmall">{$discussion->getTitle()|escape:html}
									{/TitlePane_Title}
								{/if}
									{TitlePane_Note}
									{assign var="canCreateDiscussionTopic" value=$discussion->getDiscussionAccessManager()->canCreateDiscussionTopic($discussion, $user->getId())}
									{assign var="canDiscussionRSSFeed" value=$discussion->getDiscussionAccessManager()->canDiscussionRSSFeed($discussion, $user->getId())}
									{assign var="canMarkDiscussionTopicsRead" value=$discussion->getDiscussionAccessManager()->canMarkDiscussionTopicsRead($discussion, $user->getId())}
									<div class="prClr">
										<div class="prFloatLeft prInnerLeft prInnerRight">&nbsp; 
										{if $canCreateDiscussionTopic}<a href="{$CurrentGroup->getGroupPath('createtopic')|cat:'discussion/'|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a> {/if}
										{if $canDiscussionRSSFeed}{if $canCreateDiscussionTopic}<span>&nbsp;|&nbsp;</span>{/if}<a style="text-decoration:none;display:none;" href="/rss/discussion/id/{$discussion->getId()}/">&nbsp;</a>{/if}
										</div>                                
										<div class="prFloatRight prInnerLeft prInnerRight prText5">{t}{tparam value=$discussion->getTopicsCount()}{tparam value=$discussion->getPostsCount()}%s topics, %s posts{/t}
										</div>
									</div>
									<h3 class="prIndentTop">{$discussion->getDescription()|escape:"html"|longwords:40}</h3>
									{/TitlePane_Note}
									{TitlePane_Content}
										<div id="Discussion{$discussion->getId()}Content"><!-- CHANGES -->
                                    {if $discussion->hasTopics()}
                                        {if $discussion->getTopicsCount() > 10}
                                            <script language="javascript">DynamicContentDivsId[DynamicContentDivsId.length] = {$discussion->getId()};</script>
                                        {/if}
                                        
                                        <div id="Discussion{$discussion->getId()}TopicList" class="prDiscussionTopicList">                            
                                            <table cellpadding="0" cellspacing="0" border="0" class="prDiscussionTopics" id="Discussion{$discussion->getId()}TopicListDiv">
                                                <col width="8%" />
                                                <col width="62%" />
                                                <col width="30%" />
                                                {foreach name=FTopics from=$discussion->getTopics()->setCurrentPage(0)->setListSize(10)->findByDiscussionId($discussion->getId()) item=topic}    
                                                    {include file="groups/discussion/index.topic.tr.template.tpl" topic=$topic discussion=$discussion}
                                                {/foreach}
                                            </table>
                                        </div>                                
                                        
                                        <div class="prInner"> 
                                            {if $canCreateDiscussionTopic}<a href="{$CurrentGroup->getGroupPath('createtopic')|cat:'discussion/'|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a> {if $canMarkDiscussionTopicsRead}<span>&nbsp;|&nbsp;</span>{/if}{/if} 
                                            {if $canMarkDiscussionTopicsRead}<a href="{$CurrentGroup->getGroupPath('markalltopicsread')|cat:'discussionid/'|cat:$discussion->getId()|cat:'/'}">{t}Mark all topics read{/t}</a>{/if} 
                                        </div>
                                    {/if}
                                    </div>
									{/TitlePane_Content}
								{/TitlePane}
								
                                <!-- END SUB_GROUP DISCUSSION BLOCK -->
                                {/if}
                            {/foreach}                        
                        </div>
                </div>                    
				<!-- END SUB_GROUPS BLOCK -->
			{/if}
			{/if}
		{/foreach}  

        <table cellpadding="0" cellspacing="0" border="0" class="prInner ">
            <tr>
                <td class="prInnerTop"><img class="prVBottom" src="{$AppTheme->images}/decorators/icons/icoHotDiscNew.gif" /> &ndash; {t}hot discussion with new posts{/t}</td>
                <td class="prInnerTop"><img class="prVBottom" src="{$AppTheme->images}/decorators/icons/icoDiscNew.gif" /> &ndash; {t}new posts{/t}</td>
                <td class="prInnerTop"><img class="prVBottom" src="{$AppTheme->images}/decorators/icons/icoDiscussionClosed.gif" /> &ndash; {t}discussion is closed{/t}</td>
            </tr>
            <tr>
                <td class="prInnerTop"><img class="prVBottom" src="{$AppTheme->images}/decorators/icons/icoHotDiscNoNew.gif" /> &ndash; {t}hot discussion with no new posts{/t}</td>
                <td class="prInnerTop" colspan="2"><img class="prVBottom" src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" /> &ndash; {t}no new posts{/t}</td>
            </tr>
        </table>

<div id="TopicTooltipContent" class="TooltipContent" style="position:absolute; display:none; width: 400px; padding: 5px; font-size:12px; margin-left:45px;" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>