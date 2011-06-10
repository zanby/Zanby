{* PAGE CONTENT START *}

<div class="prInner prClr2">
	<!-- left -->
	<div class="prEventList-left">

	{tab template="tabs1" active=member}
		{tabitem link=$CurrentGroup->getGroupPath('calendar.list.view') name="family" first="first"}{t}Family-Wide Events{/t}{/tabitem}
		{tabitem link=$CurrentGroup->getGroupPath('calendar.hierarchy.view') name="member" last="last"}{t}Member Group Events{/t}{/tabitem}
	{/tab}

	<div class="prInner">
        {if $countOfEvents >0 }
			{foreach from=$globalCategories item=main}
				{foreach from=$main item=level1}
					{foreach from=$level1.categories item=cat1}
                    {if $cat1.countOfEvents>0}
						<!-- Hierarchy List begin -->

						<div>

								<h2><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/category/uid/{$cat1.id}/">{$cat1.name|escape:html} ({$cat1.countOfEvents})</a></h2>
								<table cellpadding="0" cellspacing="0" border="0" class="prFullWidth">
									<col width="50%" />
									<col width="50%" />
									{if $cat1.categories}
									<tr>
										<td>
											{foreach name='fCatLevel2' from=$cat1.categories item=cat2}
												{if $smarty.foreach.fCatLevel2.iteration <= ceil($cat1.countOfCategories/2) && $cat2.countOfEvents>0}
												<ul class="prInnerTop">
													<li class="prInnerSmallTop">
														<h3><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/category/uid/{$cat2.id}/">{$cat2.name|escape:html} ({$cat2.countOfEvents})</a></h3>
														{foreach from=$cat2.categories item=cat3}
                                                        {if $cat3.countOfEvents > 0}
															<ul class="prInnerTop">
																<li class="prInnerSmallTop"><h4><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/category/uid/{$cat3.id}/">{$cat3.name|escape:html} ({$cat3.countOfEvents})</a></h4></li>
																{foreach from=$cat3.groups item=group4}
                                                                {if $group4.countOfEvents > 0}
																	{if $Warecorp_ICal_AccessManager->canViewEvents($group4.group, $user)}
																	<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group4.catid}/">{$group4.name|escape:html} ({$group4.countOfEvents})</a></li>
																	{else}{$group4.name|escape:html} ({$group4.countOfEvents}){/if}
                                                                {/if}
																{/foreach}
															</ul>
                                                        {/if}
														{/foreach}
														{foreach from=$cat2.groups item=group3}
                                                        {if $cat3.countOfEvents > 0}
															{if $Warecorp_ICal_AccessManager->canViewEvents($group3.group, $user)}
															<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group3.catid}/">{$group3.name|escape:html} ({$group3.countOfEvents})</a></li>
															{else}{$group3.name|escape:html} ({$group3.countOfEvents}){/if}
                                                        {/if}
														{/foreach}
													</li>
												</ul>
												{/if}
											{/foreach}
										</td>
										<td>
											{foreach name='fCatLevel2' from=$cat1.categories item=cat2}
												{if $smarty.foreach.fCatLevel2.iteration > ceil($cat1.countOfCategories/2) && $cat2.countOfEvents>0}
												<ul class="prInnerTop">
													<li class="prInnerSmallTop">
														<h3><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/category/uid/{$cat2.id}/">{$cat2.name|escape:html} ({$cat2.countOfEvents})</a></h3>
														{foreach from=$cat2.categories item=cat3}
                                                        {if $cat3.countOfEvents > 0}
															<ul class="prInnerTop">
																<li class="prInnerSmallTop"><h4><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/category/uid/{$cat3.id}/">{$cat3.name|escape:html} ({$cat3.countOfEvents})</a></h4></li>
																{foreach from=$cat3.groups item=group4}
                                                                {if $group4.countOfEvents > 0}
																	{if $Warecorp_ICal_AccessManager->canViewEvents($group4.group, $user)}
																	<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group4.catid}/">{$group4.name|escape:html} ({$group4.countOfEvents})</a></li>
																	{else}{$group4.name|escape:html} ({$group4.countOfEvents}){/if}
                                                                {/if}
																{/foreach}
															</ul>
                                                        {/if}
														{/foreach}
														{foreach from=$cat2.groups item=group3}
                                                        {if $cat3.countOfEvents > 0}
															{if $Warecorp_ICal_AccessManager->canViewEvents($group3.group, $user)}
															<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group3.catid}/">{$group3.name|escape:html} ({$group3.countOfEvents})</a></li>
															{else}{$group3.name|escape:html} ({$group3.countOfEvents}){/if}
                                                        {/if}
														{/foreach}
													</li>
												</ul>
												{/if}
											{/foreach}
										</td>
									</tr>
									{/if}
									{if $cat1.groups}
									<tr>
										<td>
											<ul class="prInnerTop">
											{foreach name='fGroupLevel2' from=$cat1.groups item=group2}													
												{if $smarty.foreach.fGroupLevel2.iteration <= ceil($cat1.countOfGroups/2) && $group2.countOfEvents>0}
												{if $Warecorp_ICal_AccessManager->canViewEvents($group2.group, $user)}
												<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group2.catid}/">{$group2.name|escape:html} ({$group2.countOfEvents})</a></li>
												{else}{$group2.name|escape:html} ({$group2.countOfEvents}){/if}
												{/if}													
											{/foreach}
											</ul>
										</td>
										<td>
											<ul class="prInnerTop">
											{foreach name='fGroupLevel2' from=$cat1.groups item=group2}													
												{if $smarty.foreach.fGroupLevel2.iteration > ceil($cat1.countOfGroups/2) && $group2.countOfEvents>0}
												{if $Warecorp_ICal_AccessManager->canViewEvents($group2.group, $user)}
												<li class="prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('calendar.member.view')}level/group/uid/{$group2.catid}/">{$group2.name|escape:html} ({$group2.countOfEvents})</a></li>
												{else}{$group2.name|escape:html} ({$group2.countOfEvents}){/if}
												{/if}
											{/foreach}
											</ul>
										</td>
									</tr>
									{/if}
								</table>
								
								
						   </div>
						
						<!-- Hierarchy List end -->
                        {/if}
					{/foreach}
				{/foreach}
			{/foreach}
        {else}
        {t}No Events{/t}
        {/if}
		</div>    
	</div>

	<!-- right -->
	<div class="prEventList-right">
		<h3>{t}All events tags:{/t}</h3>
		{foreach from=$lstTags->getAllList() item=t}
			<a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$t->name}/">({$t->currentCnt}) {$t->name|escape:html}</a><br />
		{foreachelse}
			{t}No Tags{/t}
		{/foreach}
	</div>
	<!-- right -->
</div>

{* PAGE CONTENT END *}
<div id="TopicTooltipContent" class="TooltipContent" style="position:absolute; display:none; width: 400px; padding: 5px; font-size:12px" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>