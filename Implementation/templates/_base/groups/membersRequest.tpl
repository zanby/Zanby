	<div class="prInnerTop">
		{*tab template="tabs1" active="pending"}
		  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' name="approve"}&nbsp;{t}Members{/t}{/tabitem}
		  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' name="pending"}{t}Pending Members{/t}&nbsp;{/tabitem}
		{/tab*}

		{assign var="member" value=$membersList->getList()}

		<div class="prInner prClr2">

					<div class="prInnerTop prInnerBottom prTRight">  
						{if $prevId}
							&laquo;
							{assign var="userAfterExclude" value=$prevId}
							<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$prevId}" class="prInnerRight">{t}Previous{/t}</a> 
						{else}
						{/if}
						{if $nextId}
						   {if !$userAfterExclude} 
							{assign var="userAfterExclude" value=$nextId}
						   {/if}
							<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$nextId}" class="prInnerRight">{t}Next{/t}</a> <span class="prInnerRight">&raquo;</span>
						{else}
						{/if}
						
						<a href="{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/" class="prInnerRight">{t}Back to Requests List{/t}</a> 
					</div>
				
					<!-- frame-dd -->
					<div class="prInner prGrayBorder">
					<!-- -->
						<div>
								<div class="prFloatLeft">
									<div class="prFloatLeft prInnerRight">
										<a href="{$member.0->getUserPath('profile')}"><img src="{$member.0->getAvatar()->getSmall()}" alt=""/></a>
									</div>
									<div>
										<a href="#">{$member.0->getLogin()|escape:"html"}</a><br />
										{if !$member.0->getIsBirthdayPrivate()}
											{t}{tparam value=$member.0->getAge()}%s Yr old {/t}
										{/if}                
										{if !$member.0->getIsGenderPrivate()}
											{if $member.0->getGender() eq 'male'}{t}Man{/t}{elseif $member.0->getGender() eq 'female'}{t}Women{/t}{/if}
										{/if}
										<br />
										{$member.0->getCity()->name|escape:"html"},&nbsp;{$member.0->getState()->name|escape:"html"}
									</div>
								</div>
								<div class="prFloatRight">
									 {t}Requested to join your group on{/t}<br />
									 {$membersList->getJoinDate($member.0)|date_locale:'DATE_MEDIUM'}
								</div>
						</div>
						<div class="prClr2 prInnerTop">
							{t}{tparam value=$member.0->getLogin()|escape:"html"}%s Note:{/t}
							<div class="prInnerTop prInnerBottom">
								{$CurrentGroup->getRequestRelation($member.0)->getBody()|wordwrap:20:' ':true|nl2br}
							</div>
						</div>
					</div>
					<div class="prInner prFloatRight">
						   
						{if $userAfterExclude}
								{t var="in_button"}Accept{/t}{linkbutton name=$in_button link=$CurrentGroup->getGroupPath('members')|cat:'mode/request'|cat:$paging_link|cat:'/accept/'|cat:$member.0->getId()|cat:'/id/'|cat:$userAfterExclude|cat:'/'} &#160;
								{t var="in_button_2"}Decline{/t}{linkbutton name=$in_button_2 link=$CurrentGroup->getGroupPath('members')|cat:'mode/request'|cat:$paging_link|cat:'/decline/'|cat:$member.0->getId()|cat:'/id/'|cat:$userAfterExclude|cat:'/'}
						  {else}
								{t var="in_button_3"}Accept{/t}{linkbutton name=$in_button_3 link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending'|cat:$paging_link|cat:'/accept/'|cat:$member.0->getId()|cat:'/'} &#160;
								{t var="in_button_4"}Decline{/t}{linkbutton name=$in_button_4 link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending'|cat:$paging_link|cat:'/decline/'|cat:$member.0->getId()|cat:'/'} 
						{/if} 
							
					</div>
			</div>
	</div>
