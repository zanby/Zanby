{literal}
<script src = "/js/yui/yahoo/yahoo.js" ></script>
<script src = "/js/yui/event/event.js" ></script>
<script src = "/js/yui/dom/dom.js" ></script>
<script src = "/js/yui/animation/animation.js" ></script>
<script src="/js/discussion/topic.js"></script>
<script src="/js/discussion/bbcode.js"></script>
<style>
		.menuListSizeOption {
			text-align:center;
			cursor:pointer;
			padding: 2px;
		}
		.menuListSizeOption:hover {
			background:#DCF1F6;
		}
		#menuListSize {
			padding : 1px;
			position:absolute;
			width: 30px;
			background: #FAFAFA;
			border: 1px solid #CFCFCF;
			font-size: 75%;
		}
	</style>
{/literal}

<div class="prText2">{t}Topics updated since your last visit{/t}</div>
<div class="prIndentTop">
	{if $totalPosts == 1}
		{t}{tparam value=$totalPosts }<span>%s</span> new message{/t}
	{else}
		{t}{tparam value=$totalPosts }<span>%s</span> new messages{/t}
	{/if}
</div>
	
<div class="prInnerTop prInnerBottom prClr3">
	<div class="prFloatLeft">{assign var="canMarkGroupTopicsRead" value=$CurrentGroup->getDiscussionAccessManager()->canMarkGroupTopicsRead($CurrentGroup->getId(), $user->getId())}
		{if $canMarkGroupTopicsRead}
		{t var="in_button"}Mark all topics read{/t}
		{linkbutton name=$in_button link=$CurrentGroup->getGroupPath('markalltopicsread')}		
		{/if} 
	</div>
	<div class="prFloatRight"><a href="javascript:return void();" onclick="showMenuListSize(); return false;">{$listSize}</a> <span>|</span> <a href="javascript:return void();"  onclick="showMenuListSize('imageMenuListSizeTop', 'bottom'); return false;"><img src="{$AppTheme->images}/decorators/bkgArrowDown.gif" border="0" alt="" id="imageMenuListSizeTop" /></a> <span class="prIndentLeftSmall">{t}messages per page{/t}</span> 
	</div>
</div>

<div class="ds-page"> {$paging} </div>

{if $recents}
		{foreach from=$recents item=discussion}
		{foreach from=$discussion item=topic key=topic_id}
<!-- group begin -->
<div class="prDiscussionBox prIndentBottom">
	<div class="prClr3">
		<h3 class="prFloatLeft">{$recentTopics[$topic_id]->getDiscussion()->getTitle()|escape:"html"|longwords:40} / <a href="{$CurrentGroup->getGroupPath('topic/topicid')|cat:$recentTopics[$topic_id]->getId()|cat:'/'}">{$recentTopics[$topic_id]->getShortSubject()|escape:"html"|longwords:40}</a></h3>
		<div class="prFloatRight prIndentTop">
			{assign var='newPosts' value=$recentTopics[$topic_id]->getPosts()->countRecentByTopicId($user->getId(), $topic_id)}{if $newPosts == 1}{t}{tparam value=$newPosts }%s new message{/t}{else}{t}{tparam value=$newPosts }%s new messages{/t}{/if}
		</div>
	</div>
	{foreach from=$topic item=post}
	{assign_adv var="author_id" value=$post->getAuthorId()}
	<div class="prDiscussionBox prIndentBottom">
		<div class="prClr3">
			<div class="prFloatLeft prDiscussionLeft">
				<h4><a href="{$post->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$post->getAuthor()->getAuthorName()|escape:html}</a></h4>
				<div class="prInnerTop"> <img src="{$post->getAuthor()->getAuthorAvatar()->getSmall()}" border="0" alt="" title="" /> </div>
				<div class="prInnerTop"><a>{$post->getAuthor()->getCity()->name}</a>, <a>{$post->getAuthor()->getState()->code}</a> </div>
				<span class="prInnerTop">{t}{tparam value=$recentTopics[$topic_id]->getPosts()->countByAuthorId($post->getAuthorId(), true)}Posts: %s{/t}</span> 
			</div>
			<div class="prFloatRight prDiscussionRight">
				<div class="prInnerTop prInnerLeft"> {$post->getPostContent()} &nbsp; </div>
			</div>
		</div>
		<div class="prInnerTop prTRight prText5"><span>1</span> <img src="{$AppTheme->images}/decorators/dt-list2.gif" border="0" alt="" /> {t}{tparam value=$post->getUserCreated($user->getTimezone())}Posted: <span>%s,</span>{/t} {t}{tparam value=$post->getViews()}Views: <span>%s</span>{/t} </div>
		<div class="prClr2 prInnerBottom prTRight"> {if $user->isAuthenticated()}
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
			{/if}
			&nbsp; 
		</div>
		</div>
	{/foreach} 
	
</div>
<!-- group end -->
{/foreach}
{/foreach}
{/if}

<div class="ds-page"> {$paging} </div>

<div class="prTRight"> <a href="javascript:return void();" onclick="showMenuListSize(); return false;">{$listSize}</a> <span>|</span> <a href="javascript:return void();"  onclick="showMenuListSize('imageMenuListSizeBottom', 'top'); return false;"><img src="{$AppTheme->images}/decorators/bkgArrowDown.gif" border="0" alt="" id="imageMenuListSizeBottom" /></a> <span class="prIndentLeftSmall">{t}messages per page{/t}</span>
</div>
<div id="menuListSize" style="display:none; background-color:##FFFFFF; border: solid 1px #ccc;">
	<div class="menuListSizeOption" onclick="selectMenuListSize(5, 'recenttopic', null); return false;">5</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(10, 'recenttopic', null); return false;">10</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(15, 'recenttopic', null); return false;">15</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(20, 'recenttopic', null); return false;">20</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(25, 'recenttopic', null); return false;">25</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(30, 'recenttopic', null); return false;">30</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(35, 'recenttopic', null); return false;">35</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(40, 'recenttopic', null); return false;">40</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(50, 'recenttopic', null); return false;">50</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(100, 'recenttopic', null); return false;">100</div>
</div>
