{literal}
<script src="/js/discussion/topic.js"></script>
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
	<h2>{t}Blog{/t}</h2>
<div class="prInner">
	<a href="{$currentGroup->getGroupPath('blog')}">&laquo; {t}Return to blog main page{/t}</a>
	{if $showTopicPartOnTop}
		{assign_adv var="author_id" value=$TopicPost->getAuthorId()}
		<!-- post begin -->
		<div class="prInnerSmallTop" id="Post{$TopicPost->getId()}">
			<div class="prClr2">
				<div class="prFloatLeft">
					<h4>
						<a href="#null" style="cursor:default;"><h2>{$topic->getSubject()|escape:"html"}</h2></a>
					</h4>
				</div>
				<div class="prInner prClr2">
					{$TopicPost->setReadedForUser($user->getId())->getPostContent()|strip_script}
				</div>
				<div class="prClr2">
						{t}Posted by{/t} <a href="{$TopicPost->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$TopicPost->getAuthor()->getAuthorName()|escape:html}</a> : <span>{$TopicPost->getUserCreated($user->getTimezone())},</span>
						{t}Views:{/t} <span>{$TopicPost->getViews()}</span>
				</div>
				<div class="prInnerSmall prTRight">
					{assign var="canManageTopic" value=$CurrentGroup->getDiscussionAccessManager()->canManageTopic($topic->getId(), $user->getId())}
					<a href="{$currentGroup->getGroupPath('blog.details')}id/{$topic->getId()}/" onclick="reply_post({$TopicPost->getId()}, {$currentPage}, {$sortmode}); return false;">{t}{tparam value=$topic->getPostsCount()}%s Comments{/t}</a>{if $canManageTopic} | {/if}
					{if $user->isAuthenticated()}
						{if $canManageTopic}<a href="{$currentGroup->getGroupPath('blog.edit')}id/{$topic->getId()}/">{t}Edit{/t}</a>{/if}{if $canManageTopic} | {/if}
						{if $canManageTopic}<a href="#null" onclick="xajax_remove_blog_post({$topic->getId()}); return false;">{t}Delete{/t}</a>{/if}
					{/if}
				</div>
			</div>
		</div>
		<!-- post end -->
	{/if}
	{if $topicPostsList}
	<div class="prInnerTop prClr2">            
			{$paging}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeTop', 'bottom'); return false;" id="imageMenuListSizeTop" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}comments per page{/t}</span>
	</div>
	{/if}
	{foreach from=$topicPostsList item=post}
		{assign_adv var="author_id" value=$post->getAuthorId()}
		<!-- post begin -->
		<div class="prInnerSmallTop" id="Post{$post->getId()}">
				<div class="prFloatLeft">
					<h4><a href="{$post->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$post->getAuthor()->getAuthorName()|escape:html}</a></h4>
					<div class="prInnerTop"><img src="{$post->getAuthor()->getAuthorAvatar()->getSmall()}" alt="" /></div>
					<div class="prInnerTop">
						<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}//users/search/preset/city/id/{$post->getAuthor()->getCity()->id}/">{$post->getAuthor()->getCity()->name}</a>, <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}//users/search/preset/state/id/{$post->getAuthor()->getState()->id}/">{$post->getAuthor()->getState()->name}</a><br />
					</div>
				</div>
				<div class="prInnerSmall prClr2" id="PostInnerHTML{$post->getId()}">
					 {$post->setReadedForUser($user->getId())->getPostContent()|strip_script}
				</div>
				<div class="prClr2">
						{t}Posted:{/t} <span>{$post->getUserCreated($user->getTimezone())}</span>
				</div>
				<div class="prClr2">
					{if $user->isAuthenticated()}
						{assign var="canDeletePost" value=$post->getDiscussionAccessManager()->canDeletePost($post, $user->getId())}
						{assign var="canEditPost" value=$post->getDiscussionAccessManager()->canEditPost($post, $user->getId())}							
						{if $canEditPost}<a href="#null" onclick="xajax_edit_blog_comment({$post->getId()}); return false;">{t}Edit Comment{/t}</a>{if $canDeletePost}  |  {/if}{/if}
						{if $canDeletePost}<a href="#null" onclick="xajax_remove_blog_comment({$post->getId()}); return false;">{t}Delete Comment{/t}</a>{/if}
						{if !$canEditPost && !$canDeletePost}&nbsp;{/if}
					{/if}
					<a id="p{$post->getId()}"></a>
				</div>
		</div>
		<!-- post end -->
	{/foreach}
	{if $topicPostsList}
	<div class="prInnerSmallTop prClr2">
			{$paging}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeBottom', 'bottom'); return false;" id="imageMenuListSizeBottom" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}comments per page{/t}</span>
	</div>
	{/if}
	{*if !$topicPostsList*}
		{if $user->isAuthenticated()}
					 {form from=$form}
					 <table class="prForm">
						<thead>
							<tr><th colspan="3">
								{form_errors_summary}
							</th></tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2">
									<h2>{t}Post a comment{/t}</h2><a id="add"></a>                                
								</td>
							</tr>
							<tr>
								<td width="80" class="prTRight"><strong>{t}From :{/t} </strong></td>
								<td>
									{$user->getLogin()|escape:html}
								</td>
							</tr>
							<tr>
								<td width="80" class="prTRight"><strong>{t}Comments :{/t} </strong></td>
								<td>
									{form_textarea name="comment"}
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="prFloatRight">
									{t var="in_submit"}Add new comment{/t}
									{form_submit name="form_save" value=$in_submit}
									</div>
								</td>
							</tr>
						</tbody>
					 </table>
					 <a id="addButton"></a>
					 {/form}
		{/if}
	{*/if*}
</div>

<div id="menuListSize" style="display:none; background-color:##FFFFFF; z-index:100000">
<div class="menuListSizeOption" onclick="selectMenuListSize(5, 'blogdetails', {$topic->getId()}); return false;">5</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(10, 'blogdetails', {$topic->getId()}); return false;">10</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(15, 'blogdetails', {$topic->getId()}); return false;">15</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(20, 'blogdetails', {$topic->getId()}); return false;">20</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(25, 'blogdetails', {$topic->getId()}); return false;">25</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(30, 'blogdetails', {$topic->getId()}); return false;">30</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(35, 'blogdetails', {$topic->getId()}); return false;">35</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(40, 'blogdetails', {$topic->getId()}); return false;">40</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(50, 'blogdetails', {$topic->getId()}); return false;">50</div>
<div class="menuListSizeOption" onclick="selectMenuListSize(100, 'blogdetails', {$topic->getId()}); return false;">100</div>
</div>

{if ($form->isPostback() && !$form->isValid()) || $scrollToBottom}
{literal}
<script type="application/javascript">
function showErrors() {document.getElementById('addButton').focus();};
YAHOO.util.Event.onDOMReady(showErrors);
</script>
{/literal}
{/if}