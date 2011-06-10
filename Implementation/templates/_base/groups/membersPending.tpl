<div>
	{*if $showPending}
		{tab template="tabs1" active="pending"}
		  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' name="approve"}{t}Members{/t}{/tabitem}
		  {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' name="pending" last="last"}{t}Pending Members{/t}{/tabitem}
		{/tab}
	{/if*}

	{if $order}
		{assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
	{else}
		{assign var="orderPath" value=''}
	{/if}
	{if $membersList->getCount() > 0}
		<div class="prInner prClr3">
			<table cellspacing="0" cellpadding="0" class="prResult">
				<col width="10%" />
				<col width="20%" />
				<col width="30%" />
				<col width="20%" />
				<col width="10%" />    
				<thead><tr>
					<th colspan="2"><div{if $order == 'name'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('members')}order/name/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Name{/t}</a></div></th>
					<th colspan="2">{t}Date/Message{/t}</th>
					<th>&#160;</th>
				</tr></thead>
				<tbody>	
				{foreach item=m name=members from=$membersList->getList()}
				<tr>
					<td class="prVTop" colspan="2">
						<a href="#"><img src="{$m->getAvatar()->getSmall()}" alt="" class="prVTop" /></a>&nbsp;
						<a href="{$m->getUserPath('profile')}">{$m->getLogin()|escape:html|wordwrap:12:"\n":true}</a>
						<br/>
						{if !$m->getIsBirthdayPrivate()}  
							{t}{tparam value=$m->getAge()}%s Yr old{/t}&nbsp; 	      		      	
						{/if}
						{if !$m->getIsGenderPrivate()}
							{if $m->getGender() eq 'male'}{t}Man{/t}{elseif $m->getGender() eq 'female'}{t}Women{/t}{/if}
						{/if}
						<div><a href="#null"  >{$m->getCity()->name|escape:"html"},&nbsp;{$m->getState()->name|escape:"html"}</a></div>
					</td>
					<td class="prVTop" colspan="2"> 
						{$membersList->getJoinDate($m)|date_locale:'DATE_MEDIUM'}<br />
						<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$m->getId()}">{$CurrentGroup->getRequestRelation($m)->getBody()|strip_tags|escape:"html"}</a><br />
					</td>
					<td class="prVTop">
						<div class="prInnerLeft">
							<a href="{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/accept/{$m->getId()}" title="Accept"><img src="{$AppTheme->images}/decorators/ff-ok.gif" alt=""/></a><br/>
							<a href="#null" onclick="xajax_declineMember('{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/decline/{$m->getId()}'); return false;" title="Decline"><img src="{$AppTheme->images}/decorators/ff-close.gif" alt=""/></a>
						</div>
					</td>
				</tr>
				{/foreach}
				</tbody>
			</table>

			<div class="prInner prTRight">
				{t var="in_button"}Accept all{/t}
				{linkbutton name=$in_button link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/acceptall/1/'}&nbsp;
				{t var="in_button_2"}Decline all{/t}
				{linkbutton name=$in_button_2 onclick="xajax_declineMember('"|cat:$CurrentGroup->getGroupPath("members")|cat:"mode/pending/declineall/1/', true); return false;"}
			</div>
		
		</div>

	{else}
		<div>
			{t}No pending users.{/t}
		</div>	
	{/if} 
</div>      