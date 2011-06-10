{if !$hideContent}
	<tr>
		<td class="prInnerSmall" align="center">
			<a href="{$group->getGroupPath('topic/topicid')|cat:$topic->getId()|cat:'/'}">
				{if $topic->isClosed()}
					<img src="{$AppTheme->images}/decorators/icons/icoDiscussionClosed.gif" />
				{else} 
					{if $topic->isHot()}
						{if $topic->hasUnreadPosts($user->getId())}
							<img src="{$AppTheme->images}/decorators/icons/icoHotDiscNew.gif" />
						{else}
							<img src="{$AppTheme->images}/decorators/icons/icoHotDiscNoNew.gif" />
						{/if}
					{else}
						{if $topic->hasUnreadPosts($user->getId())}
							<img src="{$AppTheme->images}/decorators/icons/icoDiscNew.gif" />
						{else}
							<img src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" />
						{/if}                
					{/if}
				{/if}
			</a>
		</td>
		<td>
			<a id="TooltipLink{$topic->getId()}" href="{$CurrentGroup->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" onmouseover="show_topic_tooltip({$topic->getId()}, {$discussion->getId()}, this);" onmouseout="hide_topic_tooltip({$topic->getId()}, {$discussion->getId()}, this);">{$topic->getSubject()|escape:"html"}</a>
			<div class="prToolTipContent" id="TooltipLinkHiddenContent{$topic->getId()}"style="display:none;">
				<h4 class="prWithoutInnerTop">{$topic->getSubject()|escape:"html"}</h4>
				{assign var='lastPosts' value=$postList->findByTopicId($topic->getId())}
				{assign var='postsCount' value=$topic->getPostsCount()}
				{assign var='authorsCount' value=$topic->getAuthorsCount()}
				<div class="prFloatRight"><span>{t}{tparam value=$lastPosts[0]->getPosition()}{tparam value=$lastPosts[0]->getCreated()|user_date_format:$user->getTimezone()}Post #%s - %s{/t}</span></div>
				<div class="prClearer">
					<span class="prText2">{$lastPosts[0]->createAuthor()->getAuthor()->getLogin()|escape:html}</span> :
					"{$lastPosts[0]->getPostContent()}"
					<div class="prFloatRight"><a href="{$CurrentGroup->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" style="text-decoration:none;">{if $postsCount != 1}{t}{tparam value=$postsCount}See all %s messages{/t}{else}{t}{tparam value=$postsCount }See all %s message{/t}{/if} ({if $authorsCount != 1}{t}{tparam value=$authorsCount }%s authors{/t}{else}{t}{tparam value=$authorsCount }%s author{/t}{/if}) &raquo;</a></div>
				</div>
			</div>	
		</td>
		<td class="prTRight prInnerLeft prInnerRight">{if $postsCount != 1}{t}{tparam value=$postsCount }%s posts{/t}
			{else}{t}{tparam value=$postsCount }%s post{/t}{/if},  {if $authorsCount != 1}{t}{tparam value=$authorsCount }%s authors{/t}{else}{t}{tparam value=$authorsCount }%s author{/t}{/if}
		</td>
	</tr>
{/if}

{if $currentTD == 'td1'}
		<a href="{$CurrentGroup->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}">
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
{/if}
{if $currentTD == 'td2'}
<a id="TooltipLink{$topic->getId()}" href="{$CurrentGroup->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" onmouseover="show_topic_tooltip({$topic->getId()}, {$discussion_id}, this);" onmouseout="hide_topic_tooltip({$topic->getId()}, {$discussion_id}, this);">{$topic->getSubject()|escape:"html"}</a>
<div id="TooltipLinkHiddenContent{$topic->getId()}" style="display:none;">
	<h4 class="prWithoutInnerTop">{$topic->getSubject()|escape:"html"}</h4>
	{assign var='lastPosts' value=$postList->findByTopicId($topic->getId())}
	{assign var='postsCount' value=$topic->getPostsCount()}
	{assign var='authorsCount' value=$topic->getAuthorsCount()}
	<div class="prFloatRight"><span>{t}Post{/t} #{$lastPosts[0]->getPosition()} - {$lastPosts[0]->getCreated()|user_date_format:$user->getTimezone()}</span></div>
	<div class="prClearer">
	<span class="prText2">{$lastPosts[0]->createAuthor()->getAuthor()->getLogin()|escape:html}</span> :
	"{$lastPosts[0]->getPostContent()}"
	<div class="prFloatRight"><a href="{$CurrentGroup->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" style="text-decoration:none;">{if $postsCount != 1}{t}{tparam value=$postsCount }See all %s messages{/t}{else}{t}{tparam value=$postsCount }See all %s message{/t}{/if} ({if $authorsCount != 1}{t}{tparam value=$authorsCount }%s authors{/t}{else}{t}{tparam value=$authorsCount }%s author{/t}{/if}) &raquo;</a></div>
</div>
{/if}
{if $currentTD == 'td3'}
{assign var='postsCount' value=$topic->getPostsCount()}
{assign var='authorsCount' value=$topic->getAuthorsCount()}
{if $postsCount != 1}{t}{tparam value=$postsCount }%s posts{/t}
{else}{t}{tparam value=$postsCount }%s post{/t}{/if},  {if $authorsCount != 1}{t}{tparam value=$authorsCount }%s authors{/t}{else}{t}{tparam value=$authorsCount }%s author{/t}{/if}
{/if}