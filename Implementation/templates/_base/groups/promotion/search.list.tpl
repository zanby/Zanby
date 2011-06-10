{* renders contacts table *}
{* all needed forms, pagers, letter selects must be out of here *}
<script type="text/javascript" src="/js/simple_checkboxes.js"></script>
{if $groupsList}
<h3>{t}Search Results{/t}</h3>
<div>{$paging}</div>
<!--<form id="add_selected_groups_form" method="POST" action="#">-->
{form from=$addSelectedGroupsForm id="add_selected_groups_form"}
<table class="prResult" cellspacing="0" cellpadding="0" border="0">
	<col width="5%" />
	<col width="50%" />
	<col width="30%" />
	<col width="15%" />
	<tr>
		<th>&nbsp;
			<input type="checkbox" id="check" onchange="check_all_checkboxes(document.getElementById('add_selected_groups_form'), this); return false;" /></th>
		<th> <div{if $order==name} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}> <a {if $order==name}class="freeColor1"{else}class="freeColor2"{/if} href="{$_url}/order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Group Name{/t}</a> </th>
		<th>{if $user->getId()} <div{if $order==proximityme} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}> <a {if $order==proximityme}class="freeColor1"{else}class="freeColor2"{/if} href="{$_url}/order/proximityme/direction/{if $order==proximityme && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Location{/t}</a>{else}&nbsp;{/if}
			</div>
		</th>
		<th> <div{if $order==members} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}> <a {if $order==members}class="freeColor1"{else}class="freeColor2"{/if} href="{$_url}/order/members/direction/{if $order==members && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Members{/t}</a>
			</div>
		</th>
	</tr>
	{foreach item=group from=$groupsList}
	<tr>
		<td>{form_checkbox name="selected_groups[]" value=$group->getId()}</td>
		<td><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" alt="" class="prFloatLeft" />
			<div class="prFloatLeft prInnerLeft"> <a href="{$group->getGroupPath('summary')}"> {$group->getName()|escape:html}</a> </div></td>
		<td><a href="#">{$group->getCity()->name}, {$group->getState()->name}</a> </td>
		<td>{$group->getMembers()->getCount()}</td>
	</tr>
	{/foreach}
</table>
<div class="prIndentTopSmall">{$paging}</div>
{/form}
{else}
<div class="prText2 prTCenter prInnerTop">{t}No groups found{/t}</div>
{/if}