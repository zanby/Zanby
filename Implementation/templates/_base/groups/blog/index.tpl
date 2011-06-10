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
		<h2 class="prFloatLeft">{t}{tparam value=$currentGroup->getName()}Blog on %s{/t}</h2>
        {if $CurrentGroup->getDiscussionAccessManager()->canCreateBlogPosts($discussion->getId(), $user->getId())}
            <div class="prFloatRight">
            	<a href="{$CurrentGroup->getGroupPath('blog.create')}">{t}+ Create Blog Post{/t}</a>
            </div>
        {/if}
    	{if $lstTopics}
        <div class="prInnerSmallTop prTRight">            
                {$paging}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeTop', 'bottom'); return false;" id="imageMenuListSizeTop" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}posts per page{/t}</span>            
        </div>
        {/if}
        {foreach from=$lstTopics item=topic}
            {assign var="TopicPost" value=$topic->getTopicPost()}
            {assign_adv var="author_id" value=$TopicPost->getAuthorId()}
            <!-- post begin -->
            <div class="prInnerSmallTop" id="Post{$TopicPost->getId()}">
                    <div class="prClr2">                       
                     <a href="{$currentGroup->getGroupPath('blog.details')}id/{$topic->getId()}/"><h2>{$topic->getSubject()|escape:"html"}</h2></a>                        
                    </div>
                    <div class="prInner prClr2">
                       
						{$TopicPost->setReadedForUser($user->getId())->getPostContent()}
                    </div>
					<div class="prClr2 prInnerTop">
                            <p>{t}Posted by{/t} <a href="{$TopicPost->setAuthor($user->createAuthorById($author_id))->getAuthor()->getAuthorHomePageLink()}">{$TopicPost->getAuthor()->getAuthorName()|escape:html}</a> : <span>{$TopicPost->getUserCreated($user->getTimezone())},</span>
                            {t}Views:{/t} <span>{$TopicPost->getViews()}</span></p>
				   </div>                     
                   <div>
                        {assign var="canManageTopic" value=$CurrentGroup->getDiscussionAccessManager()->canManageTopic($topic->getId(), $user->getId())}
                        <a href="{$currentGroup->getGroupPath('blog.details')}id/{$topic->getId()}/" onclick="reply_post({$TopicPost->getId()}, {$currentPage}, {$sortmode}); return false;">{t}{tparam value=$topic->getPostsCount()}%s Comments{/t}</a>{if $canManageTopic} | {/if}
                        {if $user->isAuthenticated()}
                            {if $canManageTopic}<a href="{$currentGroup->getGroupPath('blog.edit')}id/{$topic->getId()}/">{t}Edit{/t}</a>{/if}{if $canManageTopic} | {/if}
                            {if $canManageTopic}<a href="#null" onclick="xajax_remove_blog_post({$topic->getId()}); return false;">{t}Delete{/t}</a>{/if}
                        {/if}
                    </div>
           </div>
            <!-- post end -->
        {/foreach}
        {if $lstTopics}
        <div class="prInnerSmallTop prClr2">
            {$paging}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:return void();" onclick="showMenuListSize('imageMenuListSizeBottom', 'bottom'); return false;" id="imageMenuListSizeBottom" class="prArrow-down">{$listSize}</a> <span class="prIndentLeftSmall">{t}posts per page{/t}</span>
		</div>
        {/if}
        {if !$lstTopics}
        <div>
        <p>{t}This group has not created blog posts yet.{/t}</p>
        </div>
        {/if}

<div id="menuListSize" style="display:none; background-color:##FFFFFF; z-index:100000">
	<div class="menuListSizeOption" onclick="selectMenuListSize(5, 'blog', 0); return false;">5</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(10, 'blog', 0); return false;">10</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(15, 'blog', 0); return false;">15</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(20, 'blog', 0); return false;">20</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(25, 'blog', 0); return false;">25</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(30, 'blog', 0); return false;">30</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(35, 'blog', 0); return false;">35</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(40, 'blog', 0); return false;">40</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(50, 'blog', 0); return false;">50</div>
	<div class="menuListSizeOption" onclick="selectMenuListSize(100, 'blog', 0); return false;">100</div>
</div>