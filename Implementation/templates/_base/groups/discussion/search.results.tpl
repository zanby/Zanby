{literal}
<script src = "/js/yui/yahoo/yahoo.js" ></script>
<script src = "/js/yui/event/event.js" ></script>
<script src = "/js/yui/dom/dom.js" ></script>
<script src = "/js/yui/animation/animation.js" ></script>
<script src="/js/discussion/topic.js"></script>
<script src="/js/discussion/bbcode.js"></script>
{/literal}

	{if IS_GLOBAL_GROUP}
<h2 class="prInner">{t}{tparam value=$currentGroup->getName()}Discussions on %s{/t}</h2>
{/if}
<h2>{t}Search Results{/t}</h2>
{if $recents}
	{if $totalPosts == 1}
	{t}{tparam value=$totalPosts }<span class="prMarkRequired">%s</span> message {/t}
	{else}
	{t}{tparam value=$totalPosts }<span class="prMarkRequired">%s</span> messages {/t}
	{/if}
	{/if}
	<div class="prClr3"> {if $recents}
	<div class="prFloatRight prInnerTop prPaginatorRight">
		{$paging} 
		<div class="prClr prIndentBottomSmall"></div>
		<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeBottom', 'bottom'); return false;" id="imageMenuListSizeBottom" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}messages per page{/t}</span>
	</div>
	{/if} </div>
	{if $recents}
	{foreach from=$recents item=discussion}
	{foreach from=$discussion item=topic key=topic_id}
	<!-- group begin -->
	<div class="prDropBoxInner prClr3 prIndentTop">
	<div class="prDropHeader">
			<h2>{$recentTopics[$topic_id]->getDiscussion()->getTitle()|escape:"html"|longwords:40} / <a href="{$CurrentGroup->getGroupPath('topic/topicid')|cat:$recentTopics[$topic_id]->getId()|cat:'/'}">{$recentTopics[$topic_id]->getShortSubject()|escape:"html"|longwords:40}</a></h2>
		</div>
	
	{foreach from=$topic item=post}
	{assign_adv var="author_id" value=$post->getAuthorId()}
	<div class="prDropBoxInner">
			<div class="prClr3">
			<div class="prFloatLeft prDiscussionLeft">
					<div class="prDropHeader">
					<h3>{displaylogin href=$post->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink() user=$post->getAuthor()}</h3>
				</div>
					<div class="prInnerTop"> <img src="{$post->getAuthor()->getAuthorAvatar()->getSmall()}" border="0" alt="" title="" /> </div>
					<div class="prInnerTop"><a>{$post->getAuthor()->getCity()->name}</a>, <a>{$post->getAuthor()->getState()->code}</a><br />
					<span>{t}{tparam value=$recentTopics[$topic_id]->getPosts()->countByAuthorId($post->getAuthorId(), true)}Posts: %s{/t}</span> </div>
				</div>
			<div class="prFloatRight prDiscussionRight prInnerSmallBottom">
					<p> {$post->getPostContent()} </p>
				</div>
		</div>
			<div class="prInnerSmallTop prTRight"> <span>1</span> <img src="{$AppTheme->images}/decorators/dt-list2.gif" border="0" alt="" /> {t}{tparam value=$post->getUserCreated($user->getTimezone())}<span>Posted:</span> %s,{/t} {t}{tparam value=$post->getViews()}<span>Views:</span> %s{/t}
		</div>
			<div class="prClr3">
			<div class="prInnerSmallTop prTRight"> {if $user->isAuthenticated()}
					{assign var="canReplyPost" value=$post->getDiscussionAccessManager()->canReplyPost($post, $user->getId())}
					{assign var="canEmailAuthorPost" value=$post->getDiscussionAccessManager()->canEmailAuthorPost($post, $user->getId())}
					{assign var="canDeletePost" value=$post->getDiscussionAccessManager()->canDeletePost($post, $user->getId())}
					{assign var="canEditPost" value=$post->getDiscussionAccessManager()->canEditPost($post, $user->getId())}
					{assign var="canReportPost" value=$post->getDiscussionAccessManager()->canReportPost($post, $user->getId())}
					
					{if $canReplyPost}<a href="#null" onclick="reply_post({$post->getId()}); return false;">{t}Reply{/t}</a>{if $canEmailAuthorPost || $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
					{if $canEmailAuthorPost}<a href="#null" onclick="email_author({$post->getId()}); return false;">{t}Email Author{/t}</a>{if $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
					{if $canDeletePost}<a href="#null" onclick="delete_post({$post->getId()}); return false;">{t}Delete{/t}</a>{if $canEditPost || $canReportPost}  |  {/if}{/if}
					{if $canEditPost}<a href="#null" onclick="edit_post({$post->getId()}); return false;">{t}Edit{/t}</a>{if $canReportPost}  |  {/if}{/if}
					{if $canReportPost}<a href="#null" onclick="report_post({$post->getId()}); return false;">{t}Report this post{/t}</a>{/if}
					{/if} </div>
		</div>
		</div>
	{/foreach} </div>
	<!-- group end -->
{/foreach}
	{/foreach}
	{else}
<div class="prDropBoxInner">
	<div class="prText2">{t}We did not find any results{/t}</div>
	<div class="prInner"><span class="prText2">{t}Search tips:{/t}</span>
		<ul>
			<li>{t}Ensure words are spelled correctly.{/t}</li>
			<li>{t}Try rephrasing keywords or using synonyms.{/t}</li>
			<li>{t}Try less specific keywords.{/t}</li>
			<li>{t}Make your queries as concise as possible.{/t}</li>
		</ul>
	</div>
</div>
{/if}
	
	{if $recents}
<div class="prFloatRight prInnerTop prInnerBottom prPaginatorRight"> 
	{$paging}
	<div class="prClr prIndentBottomSmall"></div>
	<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeTop', 'top'); return false;" id="imageMenuListSizeTop" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}messages per page{/t}</span> </div>
{/if}
<!-- Vertical Spacer -->
<!-- tabs2 area end -->
<div id="menuListSize" style="display:none; background-color:#FFFFFF; border: solid 1px #ccc;">
	<div class="menuListSizeOption" onclick="selectMenuListSize(5, 'search', null); return false;">5</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(10, 'search', null); return false;">10</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(15, 'search', null); return false;">15</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(20, 'search', null); return false;">20</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(25, 'search', null); return false;">25</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(30, 'search', null); return false;">30</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(35, 'search', null); return false;">35</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(40, 'search', null); return false;">40</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(50, 'search', null); return false;">50</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(100, 'search', null); return false;">100</div>
</div>