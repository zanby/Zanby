<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}

{assign var="currkey" value="1"}
    <div class="prInner">
<!-- -->
    {if $topvideosShowThreadsNumber && ($topvideosDisplayMostActive || $topvideosDisplayMostRecent || $topvideosDisplayMostUpped)}
        <!-- most active -->
        {if ($topvideosDisplayMostActive && $mostActiveVideos)}
        <div id="topvideo_tab_0_{$cloneId}_{$currkey}" style="display:block;" class="prSubNav prNoBorder">
            <!-- content object tabs -->
            <ul>
                <li class="active"><a class="prNoBorder" href="#null">{t}Most Active{/t}</a></li>
                {if $topvideosDisplayMostRecent} <li{if !$topvideosDisplayMostUpped}{/if}><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Recent{/t}</a>
                </li>
                {/if}
                {*if $topvideosDisplayMostUpped}
                <li><a{if !$topvideosDisplayMostRecent} class="prNoBorder"{/if} href="#null" onclick="document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Upped{/t}</a></li>
                {/if*}
            </ul>
            <!-- content object tabs -->
            <!-- content object tabs area -->
            <div class="prGrayBorder prInner"> {foreach from = $mostActiveVideos item=video name=nm}
                <table>
                	<tr>
                        <td valign="top" align="center"> <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" /> </td>
                       <td valign="top" width="100%">
                            <div class="prClr">
                            <h3>{$video->getTitle()|longwordsimp:25|escape:html}</h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                            {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                            {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                            {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                            {if $video->getUpDownRankByUser($user) != -1}
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
        {/if}
        <!-- /most active --> 
        <!-- most recent -->
        {if ($topvideosDisplayMostRecent && $mostRecentVideos)}
        <div id="topvideo_tab_1_{$cloneId}_{$currkey}" {if $topvideosDisplayMostActive}style="display:none;"{else}style="display:block;"{/if}>
            <!-- content object tabs -->
            <ul class="prClr3">
                {if $topvideosDisplayMostActive}
                <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Active{/t}</a></li>
                {/if}
                <li><a href="#null">{t}Most Recent{/t}</a></li>
                {*if $topvideosDisplayMostUpped}
                <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Upped{/t}</a></li>
                {/if*}
            </ul>
            <div class="prGrayBorder prInner"> {foreach from = $mostRecentVideos item=video name=nm}
                <table>
                	<tr>
                        <td valign="top"> <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" /> </td>
                        <td valign="top" width="100%">
                            <div class="prClr">
                            <h3>{$video->getTitle()|longwordsimp:25|escape:html}</h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                            {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                            {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                            {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                            {if $video->getUpDownRankByUser($user) != -1}
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
        {/if}
        <!-- /most recent -->
        <!-- most upped -->
        {if ($topvideosDisplayMostUpped && $mostUppedVideos)}
        <div id="topvideo_tab_2_{$cloneId}_{$currkey}" {if $topvideosDisplayMostRecent || $topvideosDisplayMostActive}style="display:none;"{else}style="display:block;"{/if}>
            <!-- content object tabs -->
            <ul class="prClr3">
                {if $topvideosDisplayMostActive}
                <li><a class="prNoBorder" href="#null" onclick="document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Active{/t}</a></li>
                {/if}
                {if $topvideosDisplayMostRecent} <li{if !$topvideosDisplayMostActive}{/if}><a{if !$topvideosDisplayMostActive} class="prNoBorder"{/if} href="#null" onclick="document.getElementById('topvideo_tab_1_{$cloneId}_{$currkey}').style.display = 'block'; document.getElementById('topvideo_tab_0_{$cloneId}_{$currkey}').style.display = 'none'; document.getElementById('topvideo_tab_2_{$cloneId}_{$currkey}').style.display = 'none'; return false;">{t}Most Recent{/t}</a>
                </li>
                {/if}
                <li><a href="#null">{t}Most Upped{/t}</a></li>
            </ul>
            <div class="prGrayBorder prInner"> {foreach from = $mostUppedVideos item=video name=nm}
                <table>
                	<tr>
                    	<td valign="top">
                       <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" />
                            <div id="myAlternativeContent_{$cloneId}_{$video->getId()}_2"> <a href="http://www.adobe.com/go/getflashplayer"> <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /> </a> </div>
                        </td>
                        <td valign="top" width="100%">
                            <div class="prClr">
                            <h3>{$video->getTitle()|longwordsimp:25|escape:html}</h3>
                            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
                            
                            {*{t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>*} <br />
                            {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />
                            {/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a><br />
                            <br />
                            {if $video->getUpDownRankByUser($user) != -1}
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
        {/if}
        <!-- /most upped -->
    {/if}
    <!-- / -->
    </div>
</div>