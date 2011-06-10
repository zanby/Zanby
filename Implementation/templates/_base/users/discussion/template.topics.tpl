<div id="Discussion{$discussion->getId()}Content">
{if $discussion->hasTopics()}
    <div id="Discussion{$discussion->getId()}TopicList" class="prDiscussionTopicList">                            
        <table cellpadding="0" cellspacing="0" border="0" class="prDiscussionTopics" id="Discussion{$discussion->getId()}TopicListDiv">
            <col width="8%" />
            <col width="72%" />
            <col width="20%" />
            {foreach name=FTopics from=$discussion->getTopics()->findByDiscussionId($discussion->getId()) item=topic}
                <tr>
                    <td class="prInnerSmall" align="center">
                        <a href="{$group->getGroupPath('topic/topicid')|cat:$topic->getId()}">
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
                        <a id="TooltipLink{$topic->getId()}" href="{$group->getGroupPath('topic/topicid')|cat:$topic->getId()}" onmouseover="show_topic_tooltip({$topic->getId()}, {$discussion->getId()}, this);" onmouseout="hide_topic_tooltip({$topic->getId()}, {$discussion->getId()}, this);">{$topic->getSubject()|escape:"html"}</a>
                        <div  id="TooltipLinkHiddenContent{$topic->getId()}"style="display:none">
                            <b>{$topic->getSubject()|escape:"html"}</b> <br/>
                            {assign var='lastPosts' value=$postList->findByTopicId($topic->getId())}
                            {assign var='postsCount' value=$topic->getPostsCount()}
                            {assign var='authorsCount' value=$topic->getAuthorsCount()}
                            <div style="float:right;"><i style="font-size:11px;">{t}{tparam value=$lastPosts[0]->getPosition()}{tparam value=$lastPosts[0]->getCreated()|user_date_format:$user->getTimezone()}Post #%s - %s{/t}</i></div>
                            <div style="clear:both">
                            <span class="prText2">{$lastPosts[0]->createAuthor()->getAuthor()->getLogin()|escape:html}</span> :
                            "{$lastPosts[0]->getPostContent()}"
                            <br/><div style="float:right;"><a href="{$discussion->getGroup()->getGroupPath('topic')|cat:'topicid/'|cat:$topic->getId()|cat:'/'}" style="text-decoration:none;">
							{if $postsCount != 1}
								{t}{tparam value=$postsCount}See all %s messages{/t}
							{else}
								{t}{tparam value=$postsCount}See all %s message{/t}
							{/if}
							{if $authorsCount != 1}
								{t}{tparam value=$authorsCount}(%s authors){/t}
							{else}
								{t}{tparam value=$authorsCount}(%s author){/t}
							{/if}&raquo;</a></div>
                        </div>

                    </td>
                    <td class="prTRight">
                        {$topic->getPostsCount()} posts, {$topic->getAuthorsCount()} authors
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div class="prInner"> 
        <a href="{$group->getGroupPath('createtopic/discussion')|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a>
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
        <a href="{$group->getGroupPath('createtopic/discussion')|cat:$discussion->getId()|cat:'/'}">{t}Start New Topic{/t}</a>
    </div>
{/if}
</div>