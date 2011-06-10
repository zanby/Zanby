<div class="themeA" id="aj_info_{$cloneId}"> {include file="content_objects/headline_block_view.tpl"}

{assign var="currkey" value="1"}
    <div class="prInnerTop">
<!-- -->
    {if $topvideosShowThreadsNumber && ($topvideosDisplayMostActive || $topvideosDisplayMostRecent || $topvideosDisplayMostUpped)}
        <!-- most active -->
        {if ($topvideosDisplayMostActive && $mostActiveVideos)}
        <div id="topvideo_tab_0_{$cloneId}_{$currkey}" style="display:{if !$currentTab || $currentTab=='active'}block{else}none{/if};" class="prSubNav prNoBorder">
            <!-- content object tabs -->
            <ul>
                <li class="active"><a class="prNoBorder" href="#null">{t}Most Active{/t}</a></li>
                {if $topvideosDisplayMostRecent} <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Recent{/t}</a>
                </li>
                {/if}
                {*if $topvideosDisplayMostUpped}
                <li><a{if !$topvideosDisplayMostRecent} class="prNoBorder"{/if} href="#null" onclick="document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Upped{/t}</a></li>
                {/if*}
            </ul>
            <div class="prClearer"></div>
            <!-- content object tabs -->
            <!-- content object tabs area -->
            <div class="prIndentTop"> {foreach from = $mostActiveVideos item=video name=nm}
                <table>
                    <tr>
                        <td valign="top" align="center" id="active_td_{$cloneId}_{$smarty.foreach.nm.iteration}"> {if $video->getSource() == 'nonvideo'}
                            <a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/"><img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" /></a>
                            {else} <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" /> {/if} </td>
                    </tr>
                	<tr>
                       <td valign="top" width="100%">
                            <h3><a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getTitle()|longwordsimp:15|escape:html}</a></h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                            {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                            {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                            {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td> {if $video->getUpDownRankByUser($user) != -1}
                            <div>
                                <div class="prClr">
                                    <div class="prFloatLeft prAutoWidth"> {* <a href="#">{$video->getCommentsCount()} {t}Comments{/t}</a> |  {$video->getViewsCount()} {t}views{/t}*} </div>
                                    </div>
                                    </div>
                            {/if} </td>
                    </tr>
                </table>
                <br />
                {/foreach} </div>
            <!-- /content object tabs area -->
        </div>
        {else}<div id="topvideo_tab_0_{$cloneId}_{$currkey}" style="display:none;"></div>
        {/if}
        <!-- /most active --> 
        <!-- most recent -->
        {if ($topvideosDisplayMostRecent && $mostRecentVideos)}
        <div id="topvideo_tab_1_{$cloneId}_{$currkey}" {if $topvideosDisplayMostActive && $currentTab!='recent'}style="display:none;"{else}style="display:block;"{/if} class="prSubNav prNoBorder">
            <!-- content object tabs -->
            <ul>
                {if $topvideosDisplayMostActive}
                <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Active{/t}</a></li>
                {/if}
                <li class="active"><a href="#null">{t}Most Recent{/t}</a></li>
                {*if $topvideosDisplayMostUpped}
                	<li><a href="#null" onclick="document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Upped{/t}</a></li>
                {/if*}
            </ul>
            <div class="prClearer"></div>
            <div class="prIndentTop"> {foreach from = $mostRecentVideos item=video name=nm}
                <table>
                    <tr>
                        <td valign="top" align="center" id="recent_td_{$cloneId}_{$smarty.foreach.nm.iteration}"> {if $video->getSource() == 'nonvideo'}
                            <a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/"><img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" /></a>
                            {else} <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" /> {/if} </td>
                    </tr>
                	<tr>
                       <td valign="top" width="100%">
                            <h3><a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getTitle()|longwordsimp:15|escape:html}</a></h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                            {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                            {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                            {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td> {if $video->getUpDownRankByUser($user) != -1}
                            <div>
                                <div class="prClr">
                                    <div class="prFloatLeft prAutoWidth"> {* <a href="#">{$video->getCommentsCount()} {t}Comments{/t}</a> |  {$video->getViewsCount()} {t}views{/t}*} </div>
                                    </div>
                                    </div>
                            {/if} </td>
                    </tr>
                </table>
                <br />
                {/foreach} </div>
            <!-- /content object tabs area -->
        </div>
        {else}<div id="topvideo_tab_1_{$cloneId}_{$currkey}" style="display:none;"></div>
        {/if}
        <!-- /most recent -->
        <!-- most upped -->
        {if ($topvideosDisplayMostUpped && $mostUppedVideos)}
        <div id="topvideo_tab_2_{$cloneId}_{$currkey}" { if (($topvideosDisplayMostRecent || $topvideosDisplayMostActive) && $currentTab!='upped') }style="display:none;"{else}style="display:block;"{/if} class="prSubNav prNoBorder">
            <!-- content object tabs -->
        <ul>
                {if $topvideosDisplayMostActive}
            <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Active{/t}</a></li>
                {/if}
            {if $topvideosDisplayMostRecent} <li{if !$topvideosDisplayMostActive}{/if}><a{if !$topvideosDisplayMostActive} class="prNoBorder"{/if} href="#null" onclick="document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Recent{/t}</a>
            </li>
                {/if}
            {*<li class="active"><a href="#null" class="prNoBorder">{t}Most Upped{/t}</a></li>*}
            </ul>
            <div class="prClearer"></div>
        <div class="prIndentTop"> {foreach from = $mostUppedVideos item=video name=nm}
            <table>
                <tr>
                    <td valign="top" align="center" id="upped_td_{$cloneId}_{$smarty.foreach.nm.iteration}"> {if $video->getSource() == 'nonvideo'}
                            <a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/"><img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" /></a>
                            {else} <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" /> {/if} </td>
                </tr>
                	<tr>
                       <td valign="top" width="100%">
                            <h3><a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getTitle()|longwordsimp:15|escape:html}</a></h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                        {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                        {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                        {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                    </td>
                </tr>
                <tr>
                    <td> {if $video->getUpDownRankByUser($user) != -1}
                            <div>
                                <div class="prClr">
                                <div class="prFloatLeft prAutoWidth"> {* <a href="#">{$video->getCommentsCount()} {t}Comments{/t}</a> |  {$video->getViewsCount()} {t}views{/t}*} </div>
                                </div>
                                    </div>
                        {/if} </td>
                    </tr>
                </table>
                <br />
            {/foreach} </div>
            <!-- /content object tabs area -->
        </div>
        {else}<div id="topvideo_tab_2_{$cloneId}_{$currkey}" style="display:none;"></div>
        {/if}
        <!-- /most upped -->
    {/if}
    <!-- / -->
</div>   
</div>