{literal}
	<script type="text/javascript">
		function changeCurrentHierarchy(id, url) {
			document.location.replace(url + id + '/');
		}
	</script>
{/literal}

{*if $showPending}
	{tab template="tabs1" active="approve"}
	  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' name="approve"}&nbsp;{t}Members{/t}{/tabitem}
	  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' name="pending" last="last"}{t}Pending Members{/t}&nbsp;{/tabitem}
	{/tab}
{/if*}

<!-- OLD ************************************** -->
	<h3>
		{if $totalGroupsCount > 1}
			{t}{tparam value=$totalGroupsCount }{tparam value=$totalMembersCount }There are %s groups with %s people.{/t}
		{else}
			{t}{tparam value=$totalGroupsCount }{tparam value=$totalMembersCount }There is %s group with %s people.{/t}
		{/if}
	</h3>
	<div class="prInnerTop prInnerBottom prClr3">
			<a href="{$CurrentGroup->getGroupPath('members/mode/approved/hid')|cat:$curr_hid|cat:'/'}">{t}All{/t}</a> -
			{if !$allowed_letters.A}A{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/A/hid')|cat:$curr_hid|cat:'/'}">A</a>{/if}
			{if !$allowed_letters.B}B{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/B/hid')|cat:$curr_hid|cat:'/'}">B</a>{/if}
			{if !$allowed_letters.C}C{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/C/hid')|cat:$curr_hid|cat:'/'}">C</a>{/if}
			{if !$allowed_letters.D}D{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/D/hid')|cat:$curr_hid|cat:'/'}">D</a>{/if}
			{if !$allowed_letters.E}E{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/E/hid')|cat:$curr_hid|cat:'/'}">E</a>{/if}
			{if !$allowed_letters.F}F{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/F/hid')|cat:$curr_hid|cat:'/'}">F</a>{/if}
			{if !$allowed_letters.G}G{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/G/hid')|cat:$curr_hid|cat:'/'}">G</a>{/if}
			{if !$allowed_letters.H}H{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/H/hid')|cat:$curr_hid|cat:'/'}">H</a>{/if}
			{if !$allowed_letters.I}I{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/I/hid')|cat:$curr_hid|cat:'/'}">I</a>{/if}
			{if !$allowed_letters.J}J{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/J/hid')|cat:$curr_hid|cat:'/'}">J</a>{/if}
			{if !$allowed_letters.K}K{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/K/hid')|cat:$curr_hid|cat:'/'}">K</a>{/if}
			{if !$allowed_letters.L}L{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/L/hid')|cat:$curr_hid|cat:'/'}">L</a>{/if}
			{if !$allowed_letters.M}M{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/M/hid')|cat:$curr_hid|cat:'/'}">M</a>{/if}
			{if !$allowed_letters.N}N{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/N/hid')|cat:$curr_hid|cat:'/'}">N</a>{/if}
			{if !$allowed_letters.O}O{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/O/hid')|cat:$curr_hid|cat:'/'}">O</a>{/if}
			{if !$allowed_letters.P}P{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/P/hid')|cat:$curr_hid|cat:'/'}">P</a>{/if}
			{if !$allowed_letters.Q}Q{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/Q/hid')|cat:$curr_hid|cat:'/'}">Q</a>{/if}
			{if !$allowed_letters.R}R{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/R/hid')|cat:$curr_hid|cat:'/'}">R</a>{/if}
			{if !$allowed_letters.S}S{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/S/hid')|cat:$curr_hid|cat:'/'}">S</a>{/if}
			{if !$allowed_letters.T}T{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/T/hid')|cat:$curr_hid|cat:'/'}">T</a>{/if}
			{if !$allowed_letters.U}U{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/U/hid')|cat:$curr_hid|cat:'/'}">U</a>{/if}
			{if !$allowed_letters.V}V{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/V/hid')|cat:$curr_hid|cat:'/'}">V</a>{/if}
			{if !$allowed_letters.W}W{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/W/hid')|cat:$curr_hid|cat:'/'}">W</a>{/if}
			{if !$allowed_letters.X}X{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/X/hid')|cat:$curr_hid|cat:'/'}">X</a>{/if}
			{if !$allowed_letters.Y}Y{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/Y/hid')|cat:$curr_hid|cat:'/'}">Y</a>{/if}
			{if !$allowed_letters.Z}Z{else}<a href="{$CurrentGroup->getGroupPath('members/mode/approved/sort/Z/hid')|cat:$curr_hid|cat:'/'}">Z</a>{/if}
	</div>

	<select onchange="changeCurrentHierarchy(this.options[this.selectedIndex].value, '{$CurrentGroup->getGroupPath('members')}mode/approved/hid/')" style="width: 40%;">
		{foreach from=$hierarchyList item=h}
		<option value="{$h->getId()}" {if $h->getId() == $current_hierarchy->getId()}selected{/if}>{$h->getName()|escape:html}</option>
		{/foreach}
	</select>
	<!-- Hierarchy List begin -->
			{foreach from=$globalCategories item=main}
				{foreach from=$main item=level1}
					{foreach from=$level1.categories key=catId item=cat1}
							<h2>{$cat1.name|escape:html}</h2>
							<table cellpadding="0" cellspacing="0" border="0" class="prFullWidth">
								<col width="100%" />
								{foreach name='fCatLevel2' from=$cat1.categories item=cat2}
								<tr>
									<td>
										<ul>
											<li>
												<h3>{$cat2.name|escape:html}</h3>
												{foreach from=$cat2.categories item=cat3}
												<ul>
													<li>
														<h4 class="prInnerBottom">{$cat3.name|escape:html}</h4>
														<table cellspacing="0" cellpadding="0" border="0" class="prFullWidth">
															<col width="15%" />
															<col width="35%" />
															<col width="50%" />
															{foreach from=$cat3.groups item=group4}
															<tr>
																<td class="prInnerSmallTop"><img src="{$group4.group->getAvatar()->getSmall()}" /></td>
																<td class="prInnerSmallTop"><a href="{$group4.group->getGroupPath('summary')}">{$group4.name|escape:html}</a></td>
																<td class="prInnerSmallTop">
                                                                    {t}{tparam value=$group4.group->getMembers()->getCount()}%s Members  |  Joined&#160;{/t} 
                                                                    {$CurrentGroup->getGroups()->getMemberJoinDate($group4.group->getId())|date_locale:'DATE_MEDIUM'}
                                                                    {if ($isHostPrivileges && !$membersList->isCoowner($group4.group)) || ($currentGroup->getMembers()->isHost($currentUser))}
                                                                        &nbsp;|&nbsp;<a href="#null" onclick="xajax_removeMember('{$CurrentGroup->getGroupPath('members')}mode/approved/remove/{$group4.group->getId()}/hid/{$curr_hid}/'); return false;">{t}Remove{/t}</a>
                                                                    {/if}
                                                                </td>
															</tr>
															{/foreach}
														</table>
													</li>
												</ul>
												{/foreach}
												{if $cat2.groups}
												<table cellspacing="0" cellpadding="0" border="0" class="prFullWidth">
													<col width="15%" />
													<col width="35%" />
													<col width="50%" />
													{foreach from=$cat2.groups item=group3}
													<tr>
														<td class="prInnerSmallTop"><img src="{$group3.group->getAvatar()->getSmall()}" /></td>
														<td class="prInnerSmallTop"><a href="{$group3.group->getGroupPath('summary')}">{$group3.name|escape:html}</a></td>
														<td class="prInnerSmallTop">
                                                            {t}{tparam value=$group3.group->getMembers()->getCount()}%s Members  |  Joined&#160;{/t} 
                                                            {$CurrentGroup->getGroups()->getMemberJoinDate($group3.group->getId())|date_locale:'DATE_MEDIUM'}
                                                            {if ($isHostPrivileges && !$membersList->isCoowner($group3.group)) || ($currentGroup->getMembers()->isHost($currentUser))}
                                                                &nbsp;|&nbsp;<a href="#null" onclick="xajax_removeMember('{$CurrentGroup->getGroupPath('members')}mode/approved/remove/{$group3.group->getId()}/hid/{$curr_hid}/'); return false;">{t}Remove{/t}</a>
                                                            {/if}
                                                        </td>
													</tr>
													{/foreach}
												</table>
												{/if}
											</li>
										</ul>
									</td>
								</tr>
								{/foreach}
								{if $cat1.groups}
								<tr>
									<td>
										<table cellspacing="0" cellpadding="0" border="0" class="prFullWidth">
											<col width="15%" />
											<col width="35%" />
											<col width="50%" />
											{foreach name='fGroupLevel2' from=$cat1.groups item=group2}
											<tr>
												<td class="prInnerSmallTop"><img src="{$group2.group->getAvatar()->getSmall()}" /></td>
												<td class="prInnerSmallTop"><a href="{$group2.group->getGroupPath('summary')}">{$group2.name|escape:html}</a></td>
												<td class="prInnerSmallTop">
                                                    {t}{tparam value=$group2.group->getMembers()->getCount()}%s Members  |  Joined&#160;{/t}
                                                    {$CurrentGroup->getGroups()->getMemberJoinDate($group2.group->getId())|date_locale:'DATE_MEDIUM'}
                                                    {if ($isHostPrivileges && !$membersList->isCoowner($group2.group)) || ($currentGroup->getMembers()->isHost($currentUser))}
                                                        &nbsp;|&nbsp;<a href="#null" onclick="xajax_removeMember('{$CurrentGroup->getGroupPath('members')}mode/approved/remove/{$group2.group->getId()}/hid/{$curr_hid}/'); return false;">{t}Remove{/t}</a>
                                                    {/if}
                                                </td>
											</tr>
											{/foreach}
										</table>
									</td>
								</tr>
								{/if}
							</table>
					{foreachelse}
						<div>{t}There are no members{/t}</div>
					{/foreach}
				{/foreach}
			{/foreach}