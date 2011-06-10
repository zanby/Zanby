    {if $order}{assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
    {else}{assign var="orderPath" value=''}{/if}

    {*tab template="tabs1" active="pending" style="margin:0px 0 0 -1px;"}
      {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' name="approve"}&nbsp;{t}Members{/t}{/tabitem}
      {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' name="pending" last="last"}{t}Pending Members{/t}&nbsp;{/tabitem}
    {/tab*}
	<div class="prInner">
		<div class="prClr2 prIndentBottom">
       		<h2 class="prFloatLeft">{t}Membership Requests{/t}</h1>
        </div> 
        <div class=""> 
        	<table cellpadding="0" cellspacing="0" border="0" class="prResult"> 
        		<col width="40%" />
        		<col width="22%" />
        		<col width="20%" />
        		<thead>
					<tr>
						<th nowrap="nowrap">
							<div {if $order==name}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}>
								<a href="{$CurrentGroup->getGroupPath('members/mode/pending/order/name/direction')}{if $direction == 'asc'}desc{else}asc{/if}/">{t}Group Name/ Size / Date / Host{/t}</a>
							</div>
						</th>
						<th nowrap="nowrap">
							<div {if $order==request}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}>
								<a href="{$CurrentGroup->getGroupPath('members/mode/pending/order/request/direction')}{if $direction == 'asc'}desc{else}asc{/if}/">{t}Date/Message from Host{/t}</a>
							</div>
						</th>
						<th>
							<a href="#null">{t}Action{/t}</a>
						</th>
						<th colspan="2">&#160;</th>
					</tr>
				</thead>
				<tbody>  
				
					<!-- OLD ************************************** -->
				
        		{foreach item=m name=members from=$pendingMembersList}
        		<tr>
        			<td><img src="{$m->getAvatar()->getSmall()}" title="" /></td>
        			<td>
        				<a href="{$m->getGroupPath('summary')}">{$m->getName()|escape:"html"}</a>
        				<div>
        					{assign var='membersCount' value=$m->getMembers()->getCount()}
        					{if $membersCount == 1}{t}{tparam value=$membersCount}%s Member{/t}{else}{t}{tparam value=$membersCount}%s Members{/t}{/if} |  {$m->getCreateDate()|date_locale:'DATE_MEDIUM'}<br />
        					{t}Host:{/t} <a href="{$m->getHost()->getUserPath('profile')}">{$m->getHost()->getLogin()|escape:html|longwords:20}</a>
        				</div>
        			</td>
        			<td colspan="2"> 
        				{assign var='request' value=$CurrentGroup->getRequestRelation($m)}
        				{$request->requestDate|user_date_format:$user->getTimezone():'DATE_SHORT'}<br />
        				<a href="{$CurrentGroup->getGroupPath('members')}mode/request{$paging_link}/id/{$m->getId()}">{$request->getBody()|@strip_tags|escape:"html"|@nl2br}</a><br />
        			</td>
        			<td>
        				<a href="{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/accept/{$m->getId()}"><img src="{$AppTheme->images}/decorators/ff-ok.gif" alt=""/></a>
        				<div><span /></div>
        				<a href="#null" onclick="xajax_declineMember('{$CurrentGroup->getGroupPath('members')}mode/pending{$paging_link}/decline/{$m->getId()}'); return false;"><img src="{$AppTheme->images}/decorators/ff-close.gif" alt=""/></a>
        			</td>
        		</tr>
        		{foreachelse}
        		<tr>
        			<td colspan="5">
        				<div style="border: 1px solid #fff;">
        					<div>{t}There are no pending members{/t}</div>
        				</div>
        			</td>
        		</tr>
        		{/foreach}
				</tbody>
        	</table>
        </div>
        <!-- right column end -->
        {if $pendingMembersList}
        <div>
        	<table class="fl-right">
        		<tr>
        			<td><div class="co-button" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"> <a href="{$CurrentGroup->getGroupPath('members')|cat:'mode/pending/acceptall/1/'}">{t}Approve All{/t}</a> </div></td>
        			<td><div class="co-button" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"> <a href="#null" onclick="xajax_declineMember('{$CurrentGroup->getGroupPath('members')|cat:'mode/pending/declineall/1/'}', true); return false;">{t}Decline All{/t}</a> </div></td>
        		</tr>
        	</table>
        </div>
        {/if}

 
    <!-- /OLD ************************************* -->
    </div>      
