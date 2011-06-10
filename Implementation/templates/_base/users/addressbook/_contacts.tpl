{* renders contacts table *}
{* all needed forms, pagers, letter selects must be out of here *}
<script type="text/javascript" src="/js/checkboxes_addressbook.js"></script>
<table cellspacing="0" cellpadding="0" class="prResult">
  <col width="4%" />
  <col width="35%" />
  <col width="20%" />
  <col width="25%" />
  <col width="16%" />
  <thead>
	  <tr>
		<th class="prNoRBorder">{form_checkbox name="check_uncheck" checked="0" onclick="check_uncheck_checkboxes(this, 'formAddressbook', 'contact_');"  class="prNoBorder" }</th>
		<th class="prNoLBorder">
			<a>{t}NAME{/t}&nbsp;(</a><a href="{$urls.for_sort}{$headers.firstname.url}" {if $headers.firstname.active} class="{if $headers.firstname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.firstname.output}</a><a>,&nbsp;</a><a href="{$urls.for_sort}{$headers.lastname.url}" {if $headers.lastname.active} class="{if $headers.lastname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.lastname.output}</a><a>)</a>
		</th>
		<th>
		  <a href="{$urls.for_sort}{$headers.email.url}" {if $headers.email.active} class="{if $headers.email.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.email.output}</a>
		</th>
		<th><div><a>{$headers.maillists.output}</a></div></th>
		<th><div><a>{t}Profile{/t}</a></div></th>
	  </tr>
	</thead>
	<tbody>
{foreach item=contact from=$contacts name='addressbook'}
	{assign var="cid" value=$contact->getContactId()}
		<tr id="contact_row_{$cid}" {if ($smarty.foreach.addressbook.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
			{view_factory entity='addressbook' view='default' object=$contact cid=$cid contacts=contacts[] currentUser=$currentUser}
		</tr>
		{foreachelse}
			<tr>
				<td colspan="5">{if $location == 'addressbook'}{t}your addressbook is empty{/t}{else}{t}maillist is empty{/t}{/if}</td>
			</tr>
{/foreach}
	</tbody>
</table>