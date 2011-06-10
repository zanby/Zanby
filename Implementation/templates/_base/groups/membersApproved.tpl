{if $FACEBOOK_USED}
    {literal}
        <script type="text/javascript">//<![CDATA[ 
            {/literal}{assign_adv var="url_oninvite_friends_toevent" value="array('controller' => 'facebook', 'action' => 'invitefriendstoevent')"}{literal}
            FBCfg.url_oninvite_friends_toevent = '{/literal}{$Warecorp->getCrossDomainUrl($url_oninvite_friends_toevent)}{literal}';
            {/literal}{assign_adv var="url_onremove_from_eventinvite" value="array('controller' => 'facebook', 'action' => 'removefromeventinvite')"}{literal}
            FBCfg.url_onremove_from_eventinvite = '{/literal}{$Warecorp->getCrossDomainUrl($url_onremove_from_eventinvite)}{literal}';
        //]]></script>
    {/literal}
{/if}
{if $order}
	{assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
{else}
	{assign var="orderPath" value=''}
{/if}
{if $membersList->getCount() > 0}

	<!-- result begin -->
	<div class="prIndentBottomSmall">
		<div class="prIndentTopSmall">{$paging}</div>
	</div>
	<table cellspacing="0" cellpadding="0" class="prResult">
		<col width="10%" />
		<col width="20%" />
		<col width="20%" />
		<col width="30%" />
		<thead><tr>
			<th><div {if $order == 'name'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('members')}order/name/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Name{/t}</a></div></th>
			<th><div {if $order == 'joined'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('members')}order/joined/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Date Joined{/t}</a></div></th>
			<th><div {if $order == 'laston'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('members')}order/laston/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Last On{/t}</a></div></th>
			<th>&#160;</th>
            <th>&#160;</th>
		</tr></thead>
		<tbody>
		{foreach item=m name=members from=$membersList->getList()}
		<tr {if ($smarty.foreach.members.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
		{view_factory entity='group' view='members' object=$m show_host_label=1}
		</tr>
		{/foreach}
		</tbody>
	</table>
	<div class="prIndentTopSmall">
		<div class="prIndentTopSmall">{$paging}</div>
	</div>
{else}
	<div class="prFormMessage prMarkRequired">
		{t}No approved users{/t}
	</div>
{/if}
