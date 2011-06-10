<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

{if $default_index_sort == 1}
<!-- -->
{foreach from=$friendsSortedByCountry item = current key=country name="it"}
{assign var=iter value=$smarty.foreach.it.iteration-1}
{assign var=hid value=$current[0]->getFriend()->getCity()->getState()->getCountry()->id}
    <div id="mfdiv_{$iter}_{$cloneId}" style="display:{if $hide[$hid]}none{/if};">
    	<div class="prCOSectionHeader">
	        <h3 class="prFloatLeft">{$country|escape:'html'}</h3>
        </div><div class="prClearer"></div>
        <!-- -->
        <ul class="prClr3">
                {foreach from=$current item=item key=key name=frn}
                {if $display_type}
                    <li class="prFloatLeft prIndentLeftSmall prIndentTop"><a id="friends_{$cloneId}_{$item->getFriend()->getId()}" href="{$item->getFriend()->getUserPath('profile')}"><img class="prGrayBorder" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" /></a></li>
                {else}
                    <li class="prFloatLeft prClr3">
                        <img class="prFloatLeft prInnerSmall" id="friends_{$cloneId}_{$item->getFriend()->getId()}" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
                        <div class="prFloatLeft">
                            <h4>
                                <a href="{$item->getFriend()->getUserPath('profile')}">{$item->getFriend()->getLogin()}</a>
                            </h4>
                            <div>
                                <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/city:'{$item->getFriend()->getCity()->name|escape:html}'/">{$item->getFriend()->getCity()->name|escape:html}</a>,
                                <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/state:'{$item->getFriend()->getCity()->getState()->name|escape:html}'/">{$item->getFriend()->getCity()->getState()->name|escape:html}</a>
                            </div>
                        </div>
                    </li>
                {/if}
               
                <script type="text/javascript">
    				YAHOO.example.container.ttdocs_{$cloneId}_{$item->id} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$item->getFriend()->getId()}", {$smarty.ldelim} hidedelay:100, width:"220px", context:"friends_{$cloneId}_{$item->getFriend()->getId()}", text:"<b>{$item->getFriend()->getLogin()|escape:'html'}</b><br>{$item->getFriend()->getCity()->name|escape:'html'}&nbsp;{$item->getFriend()->getCity()->getState()->name|escape:'html'}<br>Member since {$item->getFriend()->getRegisterDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
                </script>
                {/foreach}
            </ul> <div class="prClearer"></div>
        <!-- / -->
    </div>
{/foreach}
<!-- / -->
{else}
<!-- content section inner -->
<ul class="prClr3">
        {foreach from=$currentUser->getFriendsList()->setListSize($display_number_in_each_region)->setCurrentPage(1)->setOrder('created DESC')->getList() item=item key=key name=frn}
        {if $display_type}
            <li class="prFloatLeft prIndentLeftSmall prIndentTop"><a id="friends_{$cloneId}_{$item->getFriend()->getId()}" href="{$item->getFriend()->getUserPath('profile')}"><img class="prGrayBorder" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" /></a></li>
        {else}
            <li class="prFloatLeft prClr3">
            	<img class="prFloatLeft prInnerSmall" id="friends_{$cloneId}_{$item->getFriend()->getId()}" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
                <div class="prFloatLeft">
                    <h4>
                        <a href="{$item->getFriend()->getUserPath('profile')}">{$item->getFriend()->getLogin()}</a>
                    </h4>
                    <div>
                       <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/city:'{$item->getFriend()->getCity()->name|escape:html}'/">{$item->getFriend()->getCity()->name|escape:html}</a>,
                       <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/state:'{$item->getFriend()->getCity()->getState()->name|escape:html}'/">{$item->getFriend()->getCity()->getState()->name|escape:html}</a>
                    </div>
                </div>
            </li>
        {/if}
        
        <script type="text/javascript">
				YAHOO.example.container.ttdocs_{$cloneId}_{$item->getFriend()->getId()} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$item->getFriend()->getId()}", {$smarty.ldelim} hidedelay:100, context:"friends_{$cloneId}_{$item->getFriend()->getId()}", width:"220px", text:"<b>{$item->getFriend()->getLogin()|escape:'html'}</b><br>{$item->getFriend()->getCity()->name|escape:'html'}&nbsp;{$item->getFriend()->getCity()->getState()->name|escape:'html'}<br>Member since {$item->getFriend()->getRegisterDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
        </script>
        {/foreach}
    </ul><div class="prClearer"></div>
<!-- / -->
{/if}

    <div class="prInnerTop">
        <a class="prLink2" href="{$currentUser->getUserPath('friends')}">{t}All Friends{/t} &raquo;</a>
</div>

</div>
