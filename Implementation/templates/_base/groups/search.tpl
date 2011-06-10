
{if $rssUrl}
{assign var='thisrss' value=0}{/if}
<div class="prClr2 prInner">
	<div>

  	{if $searchTitle}<h2>{$searchTitle}</h2>{/if}


    {if $category && $categories && $categories.$category}
    <ul><li>{$categories.$category}</li></ul>
    {elseif $categories}
    <div class="prClr2">
        {if count($categories)>3}
            {foreach key=id item=c name='category' from=$categories}
                {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 1}<ul class="prFloatLeft">{/if}
                {if $id == $filter}
                    <li>{$c}</li>
                {else}
                    <li><a href="{$_actionUrl}/preset/category/id/{$id}/{if $order && $direction}order/{$order}/direction/{$direction}/{/if}">{$c}</a></li>
                {/if}
                {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 0}</ul>{/if}
            {/foreach}
        {else}
            {foreach key=id item=c name='category' from=$categories}
                <ul class="prFloatLeft">
                {if $id == $filter}
                    <li>{$c}</li>
                {else}
                    <li><a href="{$_actionUrl}/preset/category/id/{$id}/{if $order && $direction}order/{$order}/direction/{$direction}/{/if}">{$c}</a></li>
                {/if}
                </ul>
            {/foreach}
        {/if}
    </div>
    {/if}

	<h3>{t}Search Results{/t} {if $user->getId() && $order==proximityme && $direction==asc} &ndash; {t}Closest to you.{/t}{/if}</h3>
	<div class="prGrayBorder">
		<div class="prIndentBottomSmall">{$paging}</div>

    	<table cellpadding="0" cellspacing="0" border="0" class="prResult">
      	{if $groupsList}
      		<col width="12%" />
     		<col width="31%" />
      		<col width="21%" />
      		<col width="17%" />
      		<col width="19%" />
			<tr>
				<th>&nbsp;</th>
				<th>
				<div{if $order==name} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="{$_url}/order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Group Name{/t}</a></div></th>
				<th>{if $user->getId()}
				<div{if $order==proximityme} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="{$_url}/order/proximityme/direction/{if $order==proximityme && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Location{/t}</a>
				</div>
				{else}&nbsp;{/if}</th>
				<th>
				<div{if $order==founded} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="{$_url}/order/founded/direction/{if $order==founded && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Founded{/t}</a></div></th>
				<th>
				<div {if $order==members} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="{$_url}/order/members/direction/{if $order==members && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Members{/t}</a></div></th>
			</tr>
			{foreach item=group from=$groupsList}
			<tr>
				<td class="prVTop"><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title="" /></td>
				<td class="prTBold prVTop"><a href="{$group->getGroupPath('summary')}">{$group->getName()|escape|wordwrap:13:"\n":true}</a></td>
				<td class=" prVTop">
				<a href="{$_urlWithoutLocation}/city/{$group->getCity()->id}/">{$group->getCity()->name},</a> <a href="{$_urlWithoutLocation}/state/{$group->getState()->id}/">{$group->getState()->name}</a>
				</td>
				<td class=" prVTop">{$group->getCreateDate()|date_locale:'DATE_MEDIUM'}</td>
				<td class="prVTop">{$group->getMembers()->setMembersStatus('approved')->getCount()}</td>
			</tr>
			<tr>
				<td colspan="3"><p>{$group->getDescription()|escape|wordwrap:18:" ":true}</p>
			 	<a href="{$group->getGroupPath('summary')}">{t}More{/t} &raquo;</a>
				</td>
				{if $group->getMembers()->isMemberExistsAndApproved($user->getId())}
					{if $group->getMembers()->isHost($user->getId())}
					<td colspan="2" class="prTCenter">{t}Host{/t}</td>
					{elseif $group->getMembers()->isCohost($user->getId())}
					<td colspan="2" class="prTCenter">{t}Co-Host{/t}</td>
					{else}
					<td colspan="2" class="prTCenter">{t}Member{/t}</td>
					{/if}
			  	{else}
					{if $group->getJoinMode() == 0}
				  	<td class="prTCenter" colspan="2">
					<p class="prIndentBottomSmall">{t}Anyone may join{/t}</p>
					{t var="in_button"}Join this group{/t}
					{linkbutton name=$in_button link=$group->getGroupPath('joingroup')}
				  	</td>
					{elseif $group->getJoinMode() == 1}
				  	<td class="prTCenter " colspan="2">
					<p class="prIndentBottomSmall">{t}Contact Host to request membership{/t}</p>
					{t var="in_button_2"}Join this group{/t}
					{linkbutton name=$in_button_2 link=$group->getGroupPath('joingroup')}
				 	</td>
					{elseif $group->getJoinMode() == 2}
				  	<td class="prMarkRequired prTCenter" colspan="2">
					<p class="prIndentBottomSmall">{t}Join with Code{/t}</p>
					{t var="in_button_3"}Join this group{/t}
					{linkbutton name=$in_button_3 link=$group->getGroupPath('joingroup')}
				  	</td>
					{/if}
			  	{/if}
			</tr>
			{/foreach}
			{else}
			<tr>
				<td colspan="5">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}{tparam value=$BASE_URL}{tparam value=$LOCALE}
				There are no groups in search results<br />
				Use the right utility to search again. Or you can seize the day and<br />
				<a href="%s/%s/newgroup/">Start a Group</a><br />
				If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.</a>{/t}
				</td>
			</tr>
			{/if}
		</table>
    	<div class="prIndentTopSmall">{$paging}</div>
	</div>
	</div>

{include file="groups/search.form.tpl"}
</div>
