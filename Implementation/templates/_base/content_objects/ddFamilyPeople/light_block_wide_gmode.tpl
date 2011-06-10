<div class="themeA" id="light_{$cloneId}">

	{if $default_index_sort == 1}
		{foreach from=$membersSortedByCountry item = current key=country name="it"}
			{assign var=iter value=$smarty.foreach.it.iteration-1}
			{assign var=hid value=$current[0]->getCity()->getState()->getCountry()->id}
			<div id="gmdiv_{$iter}_{$cloneId}" style="display:{if $hide[$hid]}none{/if};">
				<div class="prCOSectionHeader">
					<h3 class="prFloatLeft">{$country|escape:'html'}</h3>
					<div class="prInner prFloatRight"><span>
							<a class="prCOHeaderClose"  onclick="group_members_element_hide({$iter},{$current[0]->getCity()->getState()->getCountry()->id},'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
					</span></div>
				</div><div class="prClearer"></div>
				<!-- -->
				<ul class="prClr3">
					{foreach from=$current item=item key=key}
						{if $display_type}
							<li class="prFloatLeft prIndentLeftSmall prIndentTop"><a id="gmembers_{$cloneId}_{$item->getId()}" href="{$item->getGroupPath('summary')}"><img src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" class="prInnerSmall" alt="" /></a></li>
						{else}
							<li class="prFloatLeft prPerWidth50 prClr3">
								<img id="gmembers_{$cloneId}_{$item->getId()}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" class="prFloatLeft prInnerSmall" />
								<div class="prFloatLeft">
									<h4>
										<a href="{$item->getGroupPath('summary')}">{$item->getName()|escape:html}</a>
									</h4>
									<div><a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/city:'{$item->getCity()->name|escape:htmlall}'/">{$item->getCity()->name|escape:htmlall}</a>,
                                        <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/state:'{$item->getCity()->getState()->name|escape:htmlall}'/">{$item->getCity()->getState()->name|escape:htmlall}</a></div>
								</div>
							</li>
						{/if}
					{/foreach}
				</ul><div class="prClearer"></div>
				<!-- / -->
			</div>
		{/foreach}
	{else}
		<!-- -->
		<ul class="prClr3">
			{assign var=gml value=$CurrentGroup->getGroups()->setTypes('simple')->setListSize($display_number_in_each_region)->setCurrentPage(1)->setOrder('creation_date DESC')}
			{foreach from=$gml->getList() item=item key=key}
				{if $display_type}
					<li class="prFloatLeft prIndentLeftSmall prIndentTop"><a href="{$item->getGroupPath('summary')}"><img id="gmembers_{$cloneId}_{$key}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" class="prInnerSmall" alt="" /></a></li>
				{else}
					<li class="prFloatLeft prPerWidth50 prClr3">
						<img id="gmembers_{$cloneId}_{$key}" src="{$item->getAvatar()->setWidth(35)->setHeight(35)->getImage()}" alt="" title="" class="prFloatLeft prInnerSmall" />
						<div class="prFloatLeft">
							<h4>
								<a href="{$item->getGroupPath('summary')}">{$item->getName()|escape:html}</a>
							</h4>
							<div><a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/city:'{$item->getCity()->name|escape:htmlall}'/">{$item->getCity()->name|escape:htmlall}</a>,
                                <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/state:'{$item->getCity()->getState()->name|escape:htmlall}'/">{$item->getCity()->getState()->name|escape:htmlall}</a></div>
						</div>
					</li>
				{/if}
			{/foreach}
		</ul><div class="prClearer"></div>
		<!-- / -->
	{/if}

	{*<div class="prInnerTop">
      <a class="prLink2" href="{$BASE_URL}/{$LOCALE}/users/">{t}Browse all members{/t} &raquo;</a>
    </div>*}

</div>