<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}
<div class="prTCenter">
        
        <img src="{$video->getCover()->setWidth(340)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" /> 
</div>

{*        

<div id="VM_tinyMCE_{$cloneId}" style="width:407px; overflow-x:auto; font-size:12px;" class="li-cru">
	{$Content|strip_script}
</div>
*}


{if $video->getId()}
<div class="prClr">
        
        {*<div class="prFloatLeft prAutoWidth" style="padding-right:6px; height:60px;">
            <div class="znUpCounter" style="color:#FFFFFF;">
                {$video->getUpDownRank()}
            </div>
            
            {if $video->getUpDownRankByUser($user) == 0}
                <a href="#null" class="znUpButton"></a>
            {elseif $video->getUpDownRankByUser($user) == 1}
                <div class="znUppedButton"></div>
            {elseif $video->getUpDownRankByUser($user) == -1}
                <div class="znDownedButton"></div>
            {/if}
            
        </div>*}
                                                        
        <h3 style="font-size:16px;">{$video->getTitle()|escape:html}</h3>
          
        <div class="prFloatLeft">
            {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'} 
            {t}by{/t} <a href="{$video->getCreator()->getUserPath('profile')}"><strong>{$video->getCreator()->getLogin()|escape:html}</strong></a></div>
    </div>   
	<p class="prInnerTop">
        {$video->getDescription()|strip_tags|escape:html}{if $video->getDescription()}<br />{/if}<a href="{$currentUser->getUserPath()}videogalleryView/id/{$video->getId()}/">{t}Full Story{/t}</a>
    </p> 
 	{*<div class="znContentTools znContentTools-small znGrayBG" style="border-top:1px solid #CCCCCC">
        <div class="prClr">
            <div class="prFloatLeft prAutoWidth">																			
                <a href="{$currentUser->getUserPath()}videogalleryView/id/{$video->getId()}/">{$video->getCommentsCount()} {t}Comments{/t}</a>  |  {$video->getViewsCount()} {t}views{/t}
            </div>
            {if $video->getUpDownRankByUser($user) != -1}
                <a class="prFloatRight znDownButton" href="#null"></a>
            {/if}
        </div>
    </div>     *}
{/if}


</div>