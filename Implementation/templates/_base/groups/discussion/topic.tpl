{if $discussion_mode == 'html'}<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
{else}<script src="/js/discussion/bbcode.js"></script>{/if}
{literal}
	<script src="/js/discussion/topic.js"></script>
	<script src="/js/discussion/search.js"></script>
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
{if $discussion_mode == 'html'}
    {literal}
    <!-- tinyMCE -->
    <script language="javascript" type="text/javascript">
    function initTinyMCE( content ) {
        // Notice: The simple theme does not use all options some of them are limited to the advanced theme
        tinyMCE.init({
            // General options
            mode : "textareas",
            theme : "advanced",
            plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
    
            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,sub,sup,|,forecolor,backcolor,|,formatselect,fontselect,fontsizeselect",         
            theme_advanced_buttons2 : "link,unlink,anchor,|,charmap,hr,|,cleanup,removeformat,|,undo,redo",
            theme_advanced_buttons3 : '',           
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resize_horizontal : false,
            theme_advanced_resizing : true,
            width : "99%",
            // Example word content CSS (should be your site CSS) this one removes paragraph margins
            content_css : "css/word.css",
    
            // Drop lists for link/image/media/template dialogs
            template_external_list_url : "lists/template_list.js",
            external_link_list_url : "lists/link_list.js",
            external_image_list_url : "lists/image_list.js",
            media_external_list_url : "lists/media_list.js"
    
        });
        if ( content ) document.getElementById('content').value = content;
        tinyMCE.updateContent('content');
        
    }
    </script>
    <!-- /tinyMCE -->
    {/literal}
{/if}

		<h3>{$topic->getSubject()|escape:"html"|longwords:30}</h3>
		<form id="searchForm" action="{$CurrentGroup->getGroupPath('discussionsearch')}" method="post">
			<input type="hidden" name="_wf_" value="1" />
			<div class="prInnerSmallTop prClr3 prButtonPanel">
				<div class="prFloatLeft">
					{if $CurrentGroup->getDiscussionAccessManager()->canReplyTopic($topic->getId(), $user->getId())}
						{t var="in_button"}Reply{/t}{linkbutton name=$in_button link=$CurrentGroup->getGroupPath('replytopic')|cat:'topicid/'|cat:$topic->getId()|cat:'/page/'|cat:$currentPage|cat:'/sortmode/'|cat:$sortmode|cat:'/'}&nbsp;
					{/if}
				</div>
				<div class="prFloatRight">
					<input type="text" name="keywords" id="keywordsStr" value="{t}Keyword Search{/t}" onfocus="initSearch(); return false;" class="prIEFixInput prDisSearchField" /> {t var="in_button_2"}Go{/t}{linkbutton name=$in_button_2 onclick="searchSubmit(); return false;"}
				</div>
			</div>
			<div class="prInnerSmallTop">
				<select id="SortMode" onchange="changeSortMode('{$CurrentGroup->getGroupPath('topic')}', {$topic->getId()}, this.options[this.selectedIndex].value);">
					<option value='1' {if $topic->getPosts()->getOrder() == "zdp.created DESC"} selected{/if}>{t}Latest First{/t}</option>
					<option value='2' {if $topic->getPosts()->getOrder() == "zdp.created ASC"} selected{/if}>{t}Oldest First{/t}</option>
				</select>
				{$paging}
			</div>
		</form>
		{if $showTopicPartOnTop}
			{assign_adv var="author_id" value=$TopicPost->getAuthorId()}
			<!-- post begin -->
			<div class="prDiscussionBox prIndentBottom" id="Post{$TopicPost->getId()}">
				<div class="prClr3">
					<div class="prFloatLeft prDiscussionLeft">
						<h4>
                        {if $TopicPost->getAuthorId() != 1}<a href="{$TopicPost->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$TopicPost->getAuthor()->getAuthorName()|escape:html}</a>
                        {else}{$TopicPost->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorName()|escape:html}
                        {/if}
                        </h4>
						<div class="prInnerTop"><img src="{$TopicPost->getAuthor()->getAuthorAvatar()->getSmall()}" alt="" /></div>
						<div class="prInnerTop">
							{if $TopicPost->getAuthorId() != 1}<a>{$TopicPost->getAuthor()->getCity()->name}</a>, <a>{$TopicPost->getAuthor()->getState()->code}</a><br />
							{/if}
                            <span class="prText5">{t}Posts{/t}: {$topic->getPosts()->countByAuthorId($TopicPost->getAuthorId(), true)}</span>
						</div>
					</div>
					<div class="prFloatRight prDiscussionRight prInnerSmallBottom">
						<div class="prInnerTop prInnerLeft">{$TopicPost->setReadedForUser($user->getId())->getPostContent()}</div>
					</div>
					</div>
					<div class="prInnerTop prTRight prText5">	<span>{$TopicPost->getPosition()}</span> <img src="{$AppTheme->images}/decorators/dt-list2.gif" border="0" alt="" /> 
							{t}{tparam value=$TopicPost->getUserCreated($user->getTimezone())}{tparam value=$TopicPost->getViews()}Posted: <span>%s,</span>
							   Views: <span>%s</span>{/t}</div>
		<div class="prClr2 prInnerBottom prTRight">
			{if $user->isAuthenticated()}
				{assign var="canReplyPost" value=$TopicPost->getDiscussionAccessManager()->canReplyPost($TopicPost, $user->getId())}
				{assign var="canEmailAuthorPost" value=$TopicPost->getDiscussionAccessManager()->canEmailAuthorPost($TopicPost, $user->getId())}
				{assign var="canDeletePost" value=$TopicPost->getDiscussionAccessManager()->canDeletePost($TopicPost, $user->getId())}
				{assign var="canEditPost" value=$TopicPost->getDiscussionAccessManager()->canEditPost($TopicPost, $user->getId())}
				{assign var="canReportPost" value=$TopicPost->getDiscussionAccessManager()->canReportPost($TopicPost, $user->getId())}
				{if $canReplyPost}<a href="#null" onclick="reply_post({$TopicPost->getId()}, {$currentPage}, {$sortmode}); return false;">{t}Reply{/t}</a>{if $canEmailAuthorPost || $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
				{if $canEmailAuthorPost}<a href="#null" onclick="email_author({$TopicPost->getId()}); return false;">{t}Email Author{/t}</a>{if $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
				{if $canDeletePost}<a href="#null" onclick="delete_post({$TopicPost->getId()}); return false;">{t}Delete{/t}</a>{if $canEditPost || $canReportPost}  |  {/if}{/if}
				{if $canEditPost}<a href="#null" onclick="edit_post({$TopicPost->getId()}); return false;">{t}Edit{/t}</a>{if $canReportPost}  |  {/if}{/if}
				{if $canReportPost}<a href="#null" onclick="report_post({$TopicPost->getId()}); return false;">{t}Report this post{/t}</a>{/if}
			{/if}
		</div>
	</div>
<!-- post end -->
{/if}
{foreach from=$topic->getPosts()->findByTopicId($topic->getId()) item=post}
{assign_adv var="author_id" value=$post->getAuthorId()}
<!-- post begin -->
<div class="prDiscussionBox prIndentBottom" id="Post{$post->getId()}">
	<div class="prClr3">
		<div class="prFloatLeft prDiscussionLeft">
			<h4>
			{if $post->getAuthorId() != 1}<a href="{$post->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$post->getAuthor()->getAuthorName()|escape:html}</a>
			{else}{$post->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorName()|escape:html}
			{/if}
			</h4>
			<div class="prInnerTop"><img src="{$post->getAuthor()->getAuthorAvatar()->getSmall()}" alt="" /></div>
			<div class="prInnerTop">
				{if $post->getAuthorId() != 1}<a>{$post->getAuthor()->getCity()->name}</a>, <a>{$post->getAuthor()->getState()->name}</a><br />
				{/if}
				<span class="prText5">{t}Posts{/t}: {$topic->getPosts()->countByAuthorId($post->getAuthorId(), true)}</span>
			</div>
		</div>
		<div class="prFloatRight prDiscussionRight prInnerSmallBottom">
			<div class="prInnerTop prInnerLeft">{$post->setReadedForUser($user->getId())->getPostContent()}</div>
		</div>
	</div>
	<div class="prInnerTop prTRight prText5">
		<span>{$post->getPosition()}</span> <img src="{$AppTheme->images}/decorators/dt-list2.gif" border="0" alt="" /> 
		{t}{tparam value=$post->getUserCreated($user->getTimezone())}{tparam value=$post->getViews()}Posted: <span>%s,</span>
		    Views: <span>%s</span>{/t}
	</div>
	<div class="prInnerSmall prTRight">
		{if $user->isAuthenticated()}
			{assign var="canReplyPost" value=$post->getDiscussionAccessManager()->canReplyPost($post, $user->getId())}
			{assign var="canEmailAuthorPost" value=$post->getDiscussionAccessManager()->canEmailAuthorPost($post, $user->getId())}
			{assign var="canDeletePost" value=$post->getDiscussionAccessManager()->canDeletePost($post, $user->getId())}
			{assign var="canEditPost" value=$post->getDiscussionAccessManager()->canEditPost($post, $user->getId())}
			{assign var="canReportPost" value=$post->getDiscussionAccessManager()->canReportPost($post, $user->getId())}
			
			{if $canReplyPost}<a href="#null" onclick="reply_post({$post->getId()}, {$currentPage}, {$sortmode}); return false;">{t}Reply{/t}</a>{if $canEmailAuthorPost || $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
			{if $canEmailAuthorPost}<a href="#null" onclick="email_author({$post->getId()}); return false;">{t}Email Author{/t}</a>{if $canDeletePost || $canEditPost || $canReportPost}  |  {/if}{/if}
			{if $canDeletePost}<a href="#null" onclick="delete_post({$post->getId()}); return false;">{t}Delete{/t}</a>{if $canEditPost || $canReportPost}  |  {/if}{/if}
			{if $canEditPost}<a href="#null" onclick="edit_post({$post->getId()}); return false;">{t}Edit{/t}</a>{if $canReportPost}  |  {/if}{/if}
			{if $canReportPost}<a href="#null" onclick="report_post({$post->getId()}); return false;">{t}Report this post{/t}</a>{/if}
		{/if}
	</div>
</div>
<!-- post end -->
{/foreach}
	<div class="prIndentTopSmall">
		{if $CurrentGroup->getDiscussionAccessManager()->canReplyTopic($topic->getId(), $user->getId())}
		{t var="in_button_3"}Reply{/t}{linkbutton name=$in_button_3 link=$CurrentGroup->getGroupPath('replytopic')|cat:'topicid/'|cat:$topic->getId()|cat:'/page/'|cat:$currentPage|cat:'/sortmode/'|cat:$sortmode|cat:'/'}
	{/if}
		<span>
		{if $topic->getDiscussionAccessManager()->canManageTopic($topic->getId(), $user->getId())}
			{if isset($countDiscussion) && $countDiscussion>1}
			<a href="#" id="notify_topic" onclick="move_topic({$topic->getId()}); return false;" class="prButton"><span>{t}Move Topic{/t}</span></a>&nbsp;
			{/if}
			{if $topic->isClosed()}
				<a href="#" id="notify_topic" onclick="reopen_topic({$topic->getId()}); return false;" class="prButton"><span>{t}Reopen Topic{/t}</span></a>&nbsp;
			{else}
				<a href="#" id="notify_topic" onclick="close_topic({$topic->getId()}); return false;" class="prButton"><span>{t}Close Topic{/t}</span></a>&nbsp;
			{/if}
			<a href="#" id="notify_topic" onclick="remove_topic({$topic->getId()}); return false;" class="prButton"><span>{t}Remove Topic{/t}</span></a>
		{/if}
		</span>
	</div>
{$paging}