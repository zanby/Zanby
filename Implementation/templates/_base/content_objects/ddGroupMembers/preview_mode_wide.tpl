<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}
    
    {if $default_index_sort == 1}
    <!-- -->
    {foreach from=$membersSortedByCountry item = current key=country name="it"}
    {assign var=iter value=$smarty.foreach.it.iteration-1}
    {assign var=hid value=$current[0]->getCity()->getState()->getCountry()->id}
    <div id="gmdiv_{$iter}_{$cloneId}" style="display:{if $hide[$hid]}none{/if};">
        <div class="prCOSectionHeader">
            <h3 class="prFloatLeft">{$country|escape:'html'}</h3>
        </div><div class="prClearer"></div>
        <ul class="prClr3">
            {foreach from=$current item=item key=key name=frn}
            {if $display_type}
            <li class="prFloatLeft prIndentLeftSmall prIndentTop">
                <a id="gmembers_{$cloneId}_{$item->getId()}" href="{$item->getUserPath('profile')}"><img class="prGrayBorder" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" /></a>
            </li>
            {else}
            <li class="prFloatLeft prPerWidth50 prClr3">
                <img class="prFloatLeft prInnerSmall" id="gmembers_{$cloneId}_{$key}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
                <div class="prFloatLeft">
                    <h4>
                        <a href="{$item->getUserPath('profile')}">{$item->getLogin()}</a>
                        {if $gml->isHost($item)} <span class="prMembershipInline">&nbsp;{t}HOST{/t}</span>{/if}
                        {if $gml->isCoHost($item)} <span class="prMembershipInline">&nbsp;{t}CO-HOST{/t}</span>{/if} </h4>
                    <div>
                        <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/city:'{$item->getCity()->name|escape:html}'/">{$item->getCity()->name|escape:html}</a>,
                        <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/state:'{$item->getCity()->getState()->name|escape:html}'/">{$item->getCity()->getState()->name|escape:html}</a>
                    </div>
                </div>
            </li>
            {/if}
            <script type="text/javascript">
				YAHOO.example.container.ttdocs_{$cloneId}_{$item->getId()} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$item->getId()}", {$smarty.ldelim} hidedelay:100, width:"220px", context:"gmembers_{$cloneId}_{$item->getId()}", text:"<b>{$item->getLogin()|escape:'html'}{if $gml->isHost($item)}&nbsp;{t}HOST{/t}{/if}{if $gml->isCoHost($item)}&nbsp;{t}CO-HOST{/t}{/if}</b><br>{$item->getCity()->name|escape:'html'}&nbsp;{$item->getCity()->getState()->name|escape:'html'}<br>Member since {$item->getRegisterDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
  </script>
            {/foreach}
        </ul><div class="prClearer"></div>
        <!-- / -->
    </div>
    {/foreach}
    <!-- / -->
    {else}
    <!-- content section inner -->
    <ul class="prClr3">
        {assign var=gml value=$CurrentGroup->getMembers()->setListSize($display_number_in_each_region)->setCurrentPage(1)->setOrder('zgm.status, zgm.creation_date DESC')}
        
        {foreach from=$gml->getList() item=item key=key}
        {if $display_type}
        <li class="prFloatLeft prIndentLeftSmall prIndentTop">
            <a href="{$item->getUserPath('profile')}"><img id="gmembers_{$cloneId}_{$key}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" /></a>
        </li>
        {else}
        <li class="prFloatLeft prPerWidth50 prClr3">
            <img class="prFloatLeft prInnerSmall" id="gmembers_{$cloneId}_{$key}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
            <div class="prFloatLeft">
                <h4>
                    <a href="{$item->getUserPath('profile')}" >{$item->getLogin()}</a>
                    {if $gml->isHost($item)} <span class="prMembershipInline">&nbsp;{t}HOST{/t}</span>{/if}
                    {if $gml->isCoHost($item)} <span class="prMembershipInline">&nbsp;{t}CO-HOST{/t}</span>{/if} </h4>
                <div>
                    <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/city:'{$item->getCity()->name|escape:html}'/">{$item->getCity()->name|escape:html}</a>,
                    <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/state:'{$item->getCity()->getState()->name|escape:html}'/">{$item->getCity()->getState()->name|escape:html}</a>
                </div>
            </div>
        </li>
        {/if}
        <script type="text/javascript">
				YAHOO.example.container.ttdocs_{$cloneId}_{$key} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$key}", {$smarty.ldelim} hidedelay:100, context:"gmembers_{$cloneId}_{$key}", width:"220px", text:"<b>{$item->getLogin()|escape:'html'}{if $gml->isHost($item)}&nbsp;{t}HOST{/t}{/if}{if $gml->isCoHost($item)}&nbsp;{t}CO-HOST{/t}{/if}</b><br>{$item->getCity()->name|escape:'html'}&nbsp;{$item->getCity()->getState()->name|escape:'html'}<br>Member since {$item->getRegisterDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
</script>
        {/foreach}
    </ul><div class="prClearer"></div>
    <!-- / -->
    {/if}
	<div class="prInnerTop">
        <a class="prLink2" href="{$CurrentGroup->getGroupPath('members')}">{t}See all members{/t} &raquo;</a>
	</div>
</div>
