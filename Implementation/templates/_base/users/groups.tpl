   <!-- toggle section begin -->
<div class="prButtonPanel prClr3">   
    <h2 class="prFloatLeft">{t}Groups I Host{/t}</h2>
</div>
<table class="prResult" cellspacing="0" cellpadding="0">
    <col width="5%" />
    <col width="95%" />
    {foreach item=g name='hostgroup' from=$currentUser->getGroups()->setMembersRole('host')->setTypes('simple')->getList()}
    <tr class="prVTop">
        <td>
            <a href="{$g->getGroupPath('summary')}"><img src="{$g->getAvatar()->getSmall()}" /></a>
        </td>
        <td><div class="prClr3">
                <h4 class="prNoInner prFloatLeft">
                   <a href="{$g->getGroupPath('summary')}">{$g->getName()|escape:"html"}</a> 
                </h4>
                <a href="{$g->getGroupPath('settings')}" class="prIndentLeftSmall">{t}[ Manage ]{/t}</a>
            </div>
            <div>{$g->getCategory()->name|escape:html}</div>
            <div class="prInnerTop">
                <span class="prText2">{t}Founded:{/t}</span> {$g->getCreateDate()|date_locale:'DATE_LONG'}<br />
                <span class="prText2">{t}Size:{/t}</span> <a href="{$g->getGroupPath('members')}">{$g->getMembers()->getCount()}</a>
            </div>
            <div class="prInnerTop">
                {assign var='newPostsCount' value=$objPostsList->countUnreadByGroupId($user->getId(), $g->getId())}
                <a href="{$g->getGroupPath('recenttopic')}" class="prNoLinkDecoration">
                    {if $newPostsCount > 0}
                        <img src="{$AppTheme->images}/decorators/icons/icoHotDiscNew.gif" class="prNoMargin prVBottom" />
                        <span class="prUnderlineText">
                        {t}
                        {tparam value=$newPostsCount}
                        %s New Discussion Posts
                        {/t}
                        </span>
                    {else}
                        <img src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" class="prNoMargin prVBottom" />
                        <span class="prUnderlineText">
                        {t}
                        {tparam value=$newPostsCount}
                        %s New Discussion Post
                        {/t}
                        </span>
                    {/if}    
                </a><br/>
                {if $g->getJoinMode() == 1}<br/>
                    <a href={$g->getGroupPath('members/mode/pending')} class="prInnerSmallTop">
                        {$g->getMembers()->setMembersStatus($MEMBER_STATUS_PENDING)->getCount()} {t}New Member Requests{/t}
                    </a>
                {/if}
            </div>
        </td>
    </tr>
    {/foreach}                      
</table>

<h2>{t}Groups I belong to{/t}</h2>
<table class="prResult" cellpadding="0" cellspacing="0">
    <col width="5%" />
    <col width="45%" />
    <col width="50%" />
    {foreach item=g name='membergroup' from=$currentUser->getGroups()->setMembersRole('member;cohost')->setTypes('simple')->getList()}
    <tr class="prVTop">
        <td>
            <a href="{$g->getGroupPath('summary')}"><img src="{$g->getAvatar()->getSmall()}" /></a>
        </td>
        <td>
            <div class="prClr3">
                <h4 class="prNoInner prFloatLeft">
                    <a href="{$g->getGroupPath('summary')}">{$g->getName()|escape:"html"}</a> 
                </h4>
                {if IMPLEMENTATION_TYPE == 'ESA' || (IMPLEMENTATION_TYPE == 'EIA' && $g->getId() != IMPLEMENTATION_GROUP_UID)}
                    <a href="#null" onclick="xajax_resignFromGroup({$g->getId()}); return false;" class="prIndentLeftSmall">{t}[ Resign ]{/t}</a>
                {/if}
            </div>
            <div>{$g->getCategory()->name|escape:html}</div>
            <div class="prInnerTop">
                <span class="prText2">{t}Founded:{/t}</span> {$g->getCreateDate()|date_locale}<br />
                <span class="prText2">{t}Size:{/t}</span> <a href="{$g->getGroupPath('members')}">{$g->getMembers()->getCount()}</a>
            </div>
            <div class="prInnerTop">
               <span class="prText2">{t} Host:{/t}</span> <a href="{$g->getHost()->getUserPath('profile')}">{$g->getHost()->getLogin()|escape:"html"}</a>
            </div>
        </td>
        <td>
            {assign var='objNextEvent' value=$objEventList->findNextEventByObject($user, $g)}
            <span class="prText2">{t}Next Event:{/t}</span><br />
            {if $objNextEvent}
                {$objNextEvent->displayDate('profile.my.groups', $user, $user->getTimezone())} <br />
                <a href="{$objNextEvent->entityURL()}">{$objNextEvent->getTitle()|escape}</a>
            {else}
               {t} Next Meeting Not Scheduled{/t}
            {/if}
            <div class="prInnerTop">
                {assign var='newPostsCount' value=$objPostsList->countUnreadByGroupId($user->getId(), $g->getId())}
                <a href="{$g->getGroupPath('recenttopic')}" class="prNoLinkDecoration">
                    {if $newPostsCount > 0}
                        <img src="{$AppTheme->images}/decorators/icons/icoHotDiscNew.gif" class="prNoMargin prVBottom" />
                        <span class="prUnderlineText">
                        {t}
                        {tparam value=$newPostsCount}
                        %s New Discussion Posts
                        {/t}
                        </span>
                    {else}
                        <img src="{$AppTheme->images}/decorators/icons/icoDiscNoNew.gif" class="prNoMargin prVBottom" />
                        <span class="prUnderlineText">
                        {t}
                        {tparam value=$newPostsCount}
                        %s New Discussion Post
                        {/t}
                        </span>
                    {/if}
                </a><br/>
                <a href="{$g->getGroupPath('photos')}" class="prInnerSmallTop prNoLinkDecoration">
                    <img src="{$AppTheme->images}/decorators/icons/bkgNewMembersRequests.gif" width="16" height="16" class="prNoMargin" /> <span class="prUnderlineText">{$g->getGalleries()->getCountOfNewPhotosForUser($currentUser)} {t}New Photos{/t}</span>
                </a>
            </div>
        </td>
    </tr>
    {/foreach}                                                                       
</table>          