<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}
    
    {foreach from=$discussionsThreads item=current name=threads key=currkey}
    {assign var=iter value=$smarty.foreach.threads.iteration-1}
    {foreach from = $discussionList item=discussion}
    {if $current[0] == $discussion->getId() && $current[1]}
    <div class="prInnerTop">
        <div class="prClr3">
            <h3 class="prFloatLeft">{$discussion->getTitle()|escape:"html"}</h3>
        </div>
        {foreach from=$discussion->getTopics()->findByDiscussionId($current[0]) item=topic}
        {if $topic->getId() == $current[1]}
        <div class="prInner">
            <h4>
                <a href="{$discussion->getGroup()->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {$topic->getSubject()|escape:"html"} </a>
            </h4>
            {if $discussionsShowThreadSummaries && $discussion->getId()}
            <p class="prInnerSmall">
                {$topic->getTopicPost()->getTextContent()|escape:"html"}
                <a href="{$discussion->getGroup()->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {t}More{/t}&nbsp;&raquo;
                </a>
            </p>
            {/if}
            {assign_adv var="author_id" value=$topic->getAuthorId()}
            <p>
                {$topic->getPostsCount()} {t}posts{/t}  | <span>{t}Posted by{/t}</span>
                <a href="{$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getUserPath('profile')}"> {$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getLogin()|escape:"html"} </a>
            </p>
            <p>{t}Last post{/t}: {$topic->getLastPostCreated()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</p>
        </div>
        {/if}
        {/foreach} </div>
    {/if}
    {/foreach}
    {foreachelse}
    {if !$discussionsShowThreadsNumber}
    <p class="prInner">
        {t}You have not selected any discussions to display.{/t}
    </p>
    {/if}
    {/foreach}
    {assign var="currkey" value="1"}
    <div class="prInner"> {if $discussionsShowThreadsNumber && ($discussionsDisplayMostActive || $discussionsDisplayMostRecent)}
        <!-- most active -->
        {if $discussionsDisplayMostActive}
        <div id="discussion_tab_0_{$cloneId}_{$currkey}" style="display:block;" class="prSubNav prNoBorder">
            <!-- tabs -->
            <ul class="prClr3">
                <li class="active">
                    <a class="prNoBorder" href="#">{t}Most Active{/t}</a>
                </li>
                {if $discussionsDisplayMostRecent}
                <li>
                    <a class="prNoBorder" href="#null" onclick="document.getElementById('discussion_tab_1_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('discussion_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Recent{/t}</a>
                </li>
                {/if}
            </ul>
            <div class="prClearer"></div>
            <div class="prClearer"></div>
            <!-- /tabs -->
            <!-- tabs area -->
            <div class="prGrayBorder prInner"> 
                {foreach from = $topicsList->setListSize($discussionsShowThreadsNumber)->setCurrentPage(1)->findMostActiveByGroupId($fGroupsList) item=topic}
                <div>
                    <h4>
                        <a href="{$gFactory->loadById($topic->getDiscussion()->getGroupId())->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {$topic->getSubject()|escape:"html"} </a>
                    </h4>
                    {if $discussionsShowThreadSummaries2}
                    <p class="prInnerSmall">
                        {$topic->getTopicPost()->getTextContent()|escape:"html"}
                        <a href="{$gFactory->loadById($topic->getDiscussion()->getGroupId())->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {t}More{/t}&nbsp;&raquo;
                        </a>
                    </p>
                    {/if}
                    {assign_adv var="author_id" value=$topic->getAuthorId()}
                    <p>
                        {$topic->getPostsCount()} {t}posts{/t}  | <span>{t}Posted by{/t}</span>
                        <a href="{$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getUserPath('profile')}"> {$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getLogin()|escape:"html"} </a>
                    </p>
                    <p>{t}Last post{/t}: {$topic->getLastPostCreated()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</p>
                </div>
                {foreachelse}
                <p>{t}There are no active threads.{/t}</p>
                {/foreach}
            </div>
            <!-- /tabs area -->
        </div>
        {/if}
        <!-- /most active -->
        <!-- most recent -->
        {if $discussionsDisplayMostRecent}
        <div id="discussion_tab_1_{$cloneId}_{$currkey}" {if $discussionsDisplayMostActive}style="display:none;"{else}style="display:block;"{/if} class="prSubNav prNoBorder">
            <!-- tabs -->
            <ul class="prClr3">
                {if $discussionsDisplayMostActive}
                <li>
                    <a class="prNoBorder" href="#null" onclick="document.getElementById('discussion_tab_0_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('discussion_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Active{/t}</a>
                </li>
                {/if}
                <li class="active">
                    <a href="#null">{t}Most Recent{/t}</a>
                </li>
            </ul>
            <div class="prClearer"></div>
            <div class="prClearer"></div>
            <!-- /tabs -->
            <!-- tabs area -->
            <div class="prGrayBorder prInner"> 
                {foreach from = $topicsList->setListSize($discussionsShowThreadsNumber)->setCurrentPage(1)->findMostRecentByGroupId($fGroupsList) item=topic}
                <div>
                    <h4>
                        <a href="{$gFactory->loadById($topic->getDiscussion()->getGroupId())->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {$topic->getSubject()|escape:"html"} </a>
                    </h4>
                    {if $discussionsShowThreadSummaries2}
                    <p class="prInnerSmall">
                        {$topic->getTopicPost()->getTextContent()|escape:"html"}
                        <a href="{$gFactory->loadById($topic->getDiscussion()->getGroupId())->getGroupPath('topic/topicid')|cat:$topic->getId()}/"> {t}More{/t}&nbsp;&raquo;
                        </a>
                    </p>
                    {/if}
                    {assign_adv var="author_id" value=$topic->getAuthorId()}
                    <p>
                        {$topic->getPostsCount()} {t}posts{/t}  | <span>{t}Posted by{/t}</span>
                        <a href="{$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getUserPath('profile')}"> {$topic->setAuthor($user->createAuthorById($author_id))->getAuthor()->getLogin()|escape:"html"} </a>
                    </p>
                    <p>{t}Last post{/t}: {$topic->getLastPostCreated()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</p>
                </div>
                {foreachelse}
                <p>{t}There are no active threads.{/t}</p>
                {/foreach}
            </div>
            <!-- /tabs area -->
        </div>
        {/if}
        <!-- /most recent -->
        {/if} </div>
    <div class="prInnerTop">
      <a class="prLink2" href="{$currentUser->getUserPath('discussion')}">{t}All Discussions{/t} &raquo;</a>
    </div>
</div>