{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Groups About {/t} {$keywords|wordwrap:30:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $groupsList}
{$paging}
<table cellpadding="0" cellspacing="0" border="0" class="prResult">
	<col width="5%" />
	<col width="35%" />
	<col width="15%" />
	<col width="15%" />
	<col width="15%" />
	<col width="15%" />
	<tr>
		<th colspan="2"><div{if $order==name} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
		<a {if $order==name}{else}"{/if} href="{$_url}/order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Group Name{/t}</a></div></th>
		<th>{if $user->getId()}
		<div{if $order==proximityme} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
		<a {if $order==proximityme}{else}"{/if} href="{$_url}/order/proximityme/direction/{if $order==proximityme && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Location{/t}</a>
		</div>
		{else}&nbsp;{/if}</th>
		<th>
		<div{if $order==founded} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
		<a {if $order==founded}{else}"{/if} href="{$_url}/order/founded/direction/{if $order==founded && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Founded{/t}</a></div></th>
		<th>
		<div {if $order==members} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
		<a {if $order==members}{else}"{/if} href="{$_url}/order/members/direction/{if $order==members && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Members{/t}</a></div></th>
		<th>&#160;</th>
	</tr>
	{foreach item=group from=$groupsList name='groups'}
	<tr{if ($smarty.foreach.groups.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
		{view_factory entity=group view='globalsearch' object=$group}
	</tr>
	{/foreach}
</table>
<div class="prIndentTop">{$paging}</div>
{else}
	{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}{tparam value=$BASE_URL}{tparam value=$LOCALE}
    There are no groups in search results<br />
	Use the right utility to search again. Or you can seize the day and <a href="%s/%s/newgroup/">Start a Group</a><br />
	If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.</a>{/t}
{/if}