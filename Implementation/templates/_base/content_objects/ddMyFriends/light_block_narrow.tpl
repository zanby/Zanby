<div class="themeA" id="light_{$cloneId}">
{if $default_index_sort == 1}
<!-- -->
{foreach from=$friendsSortedByCountry item = current key=country name="it"}
{assign var=iter value=$smarty.foreach.it.iteration-1}
{assign var=hid value=$current[0]->getFriend()->getCity()->getState()->getCountry()->id}
    <div id="mfdiv_{$iter}_{$cloneId}" style="display:{if $hide[$hid]}none{/if};">
    	<div class="prCOSectionHeader">
	        <h3 class="prFloatLeft">{$country|escape:'html'}</h3>
            <div class="prInner prFloatRight"><span>
							<a class="prCOHeaderClose"  onclick="my_friends_element_hide({$iter},{$current[0]->getFriend()->getCity()->getState()->getCountry()->id},'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
            </span></div>
        </div><div class="prClearer"></div>
        <!-- -->
        <ul class="prClr3">
                {foreach from=$current item=item key=key name=frn}
                {if $display_type}
                    <li class="prFloatLeft prIndentLeftSmall prIndentTop">
                    	<a id="friends_{$cloneId}_{$item->getFriend()->getId()}" href="{$item->getFriend()->getUserPath('profile')}">
                        	<img class="prGrayBorder" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
                    	</a>
                    </li>
                {else}
                    <li class="prIndentTop">
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
                    <div class="prClearer"></div>
                {/if}

                {/foreach}
            </ul><div class="prClearer"></div>
        <!-- / -->
	</div>
{/foreach}
<!-- / -->
{else}
<!-- content section inner -->
<ul class="prClr3">
        {foreach from=$currentUser->getFriendsList()->setListSize($display_number_in_each_region)->setCurrentPage(1)->setOrder('created DESC')->getList() item=item key=key name=frn}
        {if $display_type}
            <li class="prFloatLeft prIndentLeftSmall prIndentTop">
            	<a id="friends_{$cloneId}_{$item->getFriend()->getId()}" href="{$item->getFriend()->getUserPath('profile')}">
                	<img class="prGrayBorder" src="{$item->getFriend()->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" />
                </a>
            </li>
        {else}
            <li class="prIndentTop">
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
            <div class="prClearer"></div>
        {/if}

        {/foreach}
    </ul><div class="prClearer"></div>
		<!-- / -->
{/if}

    <div class="prInnerTop">
        <a class="prLink2" href="{$currentUser->getUserPath('friends')}">{t}All Friends{/t} &raquo;</a>
</div>
</div>