<script type="text/javascript" src="/js/discussion/my_discussions.js"></script>
	{if $mode != 'commented'}
		{foreach from=$groupsList item=group}
		{assign var=id value=$group->getId()}
		{TitlePane id='DiscussionGroup'|cat:$id}
            {TitlePane_Title}<a href="#null" class="toggel-link">{$group->getName()|escape:html}</a> <small>[<a href="{$group->getGroupPath('discussionsettings')}">{t}discussion settings{/t}</a>]</small>{/TitlePane_Title}
            {TitlePane_Note}				
				<div class="prClearer prFloatRight prText5">
					{if $group->getDiscussionsCount() != 1}
						{t}{tparam value=$group->getDiscussionsCount()} %s discussions{/t}
					{else}
						{t}{tparam value=$group->getDiscussionsCount()} %s discussion{/t}
					{/if}, 
					{if $group->getTopicsCount() != 1}
						{t}{tparam value=$group->getTopicsCount()} %s topics{/t}
					{else}
						{t}{tparam value=$group->getTopicsCount()} %s topic{/t}
					{/if}, 
					{if $group->getTopicsCount() != 1}
						{t}{tparam value=$group->getPostsCount()} %s posts{/t}
					{else}
						{t}{tparam value=$group->getPostsCount()} %s post{/t}
					{/if}
				</div>
			{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_Content}
				{foreach name=discussionsList from=$group->discussionsList item=discussion}
					{TitlePane id="Discussion_"|cat:$discussion->getId()}
						{if $discussion->hasTopics()}
							{TitlePane_Title}							
								<span class="{if $discussion->isHot()}  
										{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
												prHotDiscussionNew  
											{else}
												prHotDiscussionNoNew  
										{/if}
									{else}
										{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
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

                            {if $group->getDiscussionGroupSettings()->getDiscussionStyle() eq 1}
                                <div class="prHHmailBx">{$discussion->getFullEmail()|escape}</div>
                            {elseif $group->getDiscussionGroupSettings()->getDiscussionStyle() eq 2}
                                <div class="prHHmailBx">{t}Web only. Email not enabled for this discussion.{/t}</div>
                            {/if}

							<div class="prClr prInnerLeft prInnerRight">
								<h3 class="prFloatLeft">{$discussion->getDescription()|escape:"html"|longwords:45}</h3>
								<div class="prFloatRight prText5">{t}{tparam value=$discussion->getTopicsCount()}{tparam value=$discussion->getPostsCount()} %s topics, %s posts{/t}</div>
							</div>		 
						{/TitlePane_Note}
						{TitlePane_Content}<div id="Discussion{$discussion->getId()}PreContent"></div>{/TitlePane_Content}
					{/TitlePane}				
					
			   		{*<div class="{if $smarty.foreach.discussionsList.first}freeClass{/if}">
							<div class="prClr">
								{if $discussion->hasTopics()}
									<h2 class="prFloatLeft 
											{if $discussion->isHot()}  
													{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
															prHotDiscussionNew  
														{else}
															prHotDiscussionNoNew  
													{/if}
												{else}
													{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
															prDiscussionNew  
														{else}
															prDiscussionNoNew  
												{/if}
											{/if}">
											<a href="#null" onclick="show_hideDiscussionContent({$discussion->getId()}); return false;">{$discussion->getTitle()|escape:html}</a></h2>
										<div class="prHeaderTools prIndentTop">
										<a href="#null" id="Discussion{$discussion->getId()}ContentLink" onclick="show_hideDiscussionContent({$discussion->getId()}); return false;" class="prArrow">{t}Show{/t}</a>
										</div>
								{else}
									<h2 class="prFloatLeft"><img class="prIndentRight" src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif">{$discussion->getTitle()|escape:html}</h2>
								{/if}
							</div>
							<div class="prClr prInnerLeft prInnerRight">
								<h3 class="prFloatLeft">{$discussion->getDescription()|escape:"html"|longwords:45}</h3>
								<div class="prFloatRight prText5">{t}{tparam value=$discussion->getTopicsCount()}{tparam value=$discussion->getPostsCount()} %s topics, %s posts{/t}</div>
							</div>		 
							<div id="Discussion{$discussion->getId()}PreContent">
							</div>	
					</div>*}
				{/foreach}

            {/TitlePane_Content}
        {/TitlePane}
			{*
			<div class="prDiscussionBox">
					<div class="prInnerTop prInnerBottom prClr">
						<h2 class="prFloatLeft prWithoutInnerBottom"><a href="#null" onclick="show_hideGroupContent({$group->getId()}); return false;">{$group->getName()|escape:html}</a>&nbsp;<small>[<a href="{$group->getGroupPath('discussionsettings')}">{t}discussion settings{/t}</a>]</small></h2>
						<div class="prHeaderTools prIndentTop">
							<a href="#null" id="GroupDiscussions{$group->getId()}ContentLink" onclick="show_hideGroupContent({$group->getId()}); return false;" class="prArrow">Show</a>
						</div>
						<div class="prClearer prFloatRight prText5">
								{if $group->discussionsListCount != 1}
									{t}{tparam value=$group->discussionsListCount} %s discussions{/t}
								{else}
									{t}{tparam value=$group->discussionsListCount} %s discussion{/t}
								{/if}, 
								{if $group->topicsCount != 1}
									{t}{tparam value=$group->topicsCount} %s topics{/t}
								{else}
									{t}{tparam value=$group->topicsCount} %s topic{/t}
								{/if}, 
								{if $group->postCount != 1}
									{t}{tparam value=$group->postCount} %s posts{/t}
								{else}
									{t}{tparam value=$group->postCount} %s post{/t}
								{/if}
						</div>
			   		</div>
                <div style="display:none;" id="GroupDiscussions{$group->getId()}Content" class="prDiscussionBox prIndentBottom">
				{foreach name=discussionsList from=$group->discussionsList item=discussion}
			   		<div class="{if $smarty.foreach.discussionsList.first}freeClass{/if}">
							<div class="prClr">
								{if $discussion->hasTopics()}
									<h2 class="prFloatLeft 
											{if $discussion->isHot()}  
													{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
															prHotDiscussionNew  
														{else}
															prHotDiscussionNoNew  
													{/if}
												{else}
													{if $postListObj->countUnreadByDiscussionId($user->getId(), $discussion->getId())}
															prDiscussionNew  
														{else}
															prDiscussionNoNew  
												{/if}
											{/if}">
											<a href="#null" onclick="show_hideDiscussionContent({$discussion->getId()}); return false;">{$discussion->getTitle()|escape:html}</a></h2>
											<div class="prHeaderTools prIndentTop">
											<a href="#null" id="Discussion{$discussion->getId()}ContentLink" onclick="show_hideDiscussionContent({$discussion->getId()}); return false;" class="prArrow">{t}Show{/t}</a>
											</div>
								{else}
									<h2 class="prFloatLeft"><img class="prIndentRight" src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif">{$discussion->getTitle()|escape:html}</h2>
								{/if}
							</div>
							<div class="prClr prInnerLeft prInnerRight">
								<h3 class="prFloatLeft">{$discussion->getDescription()|escape:"html"|longwords:45}</h3>
								<div class="prFloatRight prInnerTop prText5">{t}{tparam value=$discussion->getTopicsCount()}{tparam value=$discussion->getPostsCount()} %s topics, %s posts{/t}</div>
							</div>		 
							<div id="Discussion{$discussion->getId()}PreContent">
							</div>	
					</div>
				{/foreach}
                </div>	
			</div>
			*}
		{foreachelse}
			<div>
				{t}There are no groups{/t}
			</div>
		{/foreach}
		<table cellpadding="0" cellspacing="0" border="0" class="prInner">
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
	{else}
		{if $commentedTopics}
			<div class="prClr3">
				<h2 class="prFloatLeft">{t}My Comments{/t}</h2>
				<div class="prFloatRight prInner">
					<label>{t}Sort Comments: {/t}</label>
						<select name="sortMode" id="sortMode" onchange="changeSortMode(this.options[this.selectedIndex].value, '{$currentUser->getUserPath('discussion/mode/commented')}');">
							<option value="1" {if $sortmode != 2}selected{/if}>{t}Newest Comment First{/t}</option>
							<option value="2" {if $sortmode == 2}selected{/if}>{t}Oldest Comment First{/t}</option>
						</select>
				</div>
			</div>
				<div class="prInnerTop" id="DiscussionCommentedTopicList">
				<table cellpadding="0" cellspacing="0" border="0" class="prDiscussionTopics">
					<col width="8%" />
					<col width="62%" />
					<col width="20%" />
					<col width="10%" />
					{foreach from=$commentedTopics item=topic}
					<tr id="tr_Topic_Content{$topic->getId()}">
						<td class="prTCenter">
							<a href="{$topic->getDiscussion()->getGroup()->getGroupPath('topic/topicid')|cat:$topic->getId()}">
								{if $topic->isClosed()}<img src="{$AppTheme->images}/decorators/icons/icoDiscussionClosed.gif" />
								{else}
									{if $topic->isHot()}
										{if $topic->hasUnreadPosts($user->getId())}<img src="{$AppTheme->images}/decorators/icons/icoHotDiscNew.gif" />
										{else}<img src="{$AppTheme->images}/decorators/icons/icoHotDiscNoNew.gif" />{/if}
									{else}
										{if $topic->hasUnreadPosts($user->getId())}<img src="{$AppTheme->images}/decorators/icons/icoDiscNew.gif" />
										{else}<img src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" />{/if}				
									{/if}
								{/if}
							</a>
						</td>
						<td class="prInnerTop">
							<a href="{$topic->getDiscussion()->getGroup()->getGroupPath('summary')}">{$topic->getDiscussion()->getGroup()->getName()|escape:html}</a> / 
							<a href="{$topic->getDiscussion()->getGroup()->getGroupPath('discussion')}">{$topic->getDiscussion()->getShortTitle()|escape:"html"}</a>
							<h4><a href="{$topic->getDiscussion()->getGroup()->getGroupPath('topic/topicid')|cat:$topic->getId()}" onmouseover="show_topic_tooltip({$topic->getId()}, {$topic->getDiscussion()->getId()}, this);" onmouseout="hide_topic_tooltip({$topic->getId()}, {$topic->getDiscussion()->getId()}, this);">{$topic->getSubject()|escape:"html"}</a></h4>                        
                            <div id="TooltipLinkHiddenContent{$topic->getId()}" style="display:none">
                                <b>{$topic->getSubject()|escape:"html"}</b> <br/>
                                {assign var='lastPosts' value=$postList->findByTopicId($topic->getId())}
                                {assign var='postsCount' value=$topic->getPostsCount()}
                                {assign var='authorsCount' value=$topic->getAuthorsCount()}
                                <div style="float:right;"><i style="font-size:11px;">{t}{tparam value=$lastPosts[0]->getPosition()}{tparam value=$lastPosts[0]->getCreated()|user_date_format:$user->getTimezone()}Post #%s - %s{/t}</i></div>
                                <div style="clear:both">
                                <span class="prText2">{$lastPosts[0]->createAuthor()->getAuthor()->getLogin()|escape:html}</span> :
                                "{$lastPosts[0]->getTextContent()|escape:html}"
                                <br/><div style="float:right;"><a href="{$topic->getDiscussion()->getGroup()->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" style="text-decoration:none;">
								{if $postsCount != 1}
									{t}{tparam value=$postsCount}See all %s messages{/t}
								{else}
									{t}{tparam value=$postsCount}See all %s message{/t}
								{/if}
								{if $authorsCount != 1}
									{t}{tparam value=$authorsCount}(%s authors){/t}
								{else}
									{t}{tparam value=$authorsCount}(%s author){/t}
								{/if}
								&raquo;</a></div>
                            </div>
						</td>
						<td>{t}{tparam value=$topic->getPostsCount()}{tparam value=$topic->getAuthorsCount()}%s posts, <br>%s authors{/t}</td>
						<td>
							{if $user->getId() != null && $currentUser->getId() == $user->getId()}
							<a href="#null" onclick="exclude_topic({$topic->getId()}); return false;" class="co-kill" title="Remove Topic">&nbsp;</a>
							{/if}
						</td>
					</tr>                                    
					{/foreach}                                                                                         
				</table>
				</div>
		{else}
		<div>{t}There are no comments{/t}</div>
		{/if}
		<table cellpadding="0" cellspacing="0" border="0" class="prInner">
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
	{/if}
<div id="TopicTooltipContent" class="TooltipContent" style="position:absolute; display:none; width: 400px; padding: 5px; font-size:12px; margin-left:45px;" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>
