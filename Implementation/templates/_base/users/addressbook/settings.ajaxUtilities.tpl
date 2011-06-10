{if $contacts}
{if $maillistId != 'null'}
{assign var='func' value="xajax_addressbookAjaxUtilitiesDoMaillist(xajax.getFormValues('addContactToListForm'))"}

{else}
{assign var='func' value="xajax_addressbookAjaxUtilitiesDo(document.getElementById('newContacts').value)"}
{/if}
{form from=$form id="addContactToListForm" name="addContactToListForm" onsubmit="$func; return false;"}

{form_hidden id="maillistId" name="maillistId" value=$maillistId}
<!-- alf -->
	<div class="prInnerBottom">
		<a href="#null" onclick="xajax_addressbookAjaxUtilities('{$maillistId}',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'firstname','asc');return false;">{t}All{/t}</a> -
		{foreach from=$letters  item=l}
			{if $l.count}<a href="#null" onclick="xajax_addressbookAjaxUtilities('{$maillistId}',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'{$orderBy}','{$direction}','{$l.letter}');return false;">{$l.letter}</a> {else}{$l.letter} {/if}
		{/foreach}
	</div>

<!-- alf end-->
 
	<div>
		{$infoPaging} {$linkPaging}
	</div>
		<table class="prResult prIndentTop" cellpadding="0" cellspacing="0" border="0">
			<col width="4%" />
			<col width="40%" />
			<col width="18%" />
			<col width="26%" />
			<col width="12%" />
		<tr>
			<th><div>{form_checkbox name="check_uncheck" checked="0" onclick="check_uncheck_checkboxes(this, 'addContactToListForm', 'ajax_contact_');"}</div></th>
			<th  nowrap="nowrap">
			   {t} NAME {/t}(<a href="#null" onclick="xajax_addressbookAjaxUtilities('{$maillistId}',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'firstname','{$headers.firstname.direction}', '{$filter}');return false;">{$headers.firstname.output}</a> , <a href="#null" onclick="xajax_addressbookAjaxUtilities('{$maillistId}',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'lastname','{$headers.lastname.direction}', '{$filter}');return false;">{$headers.lastname.output}</a> )
			</th>
			<th><div class="{if $headers.email.direction == 'asc'}prRActive-top{else}prRActive-bottom{/if}"><a href="#null" onclick="xajax_addressbookAjaxUtilities('{$maillistId}',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'email','{$headers.email.direction}', '{$filter}');return false;">{$headers.email.output}</a></div></th>
			<th nowrap="nowrap"><a>{$headers.maillists.output}</a></th>
			<th nowrap="nowrap"><a>{t}Profile{/t}</a></th>
		</tr>
		{foreach item=contact from=$contacts name='adrbook_pop'}
			{assign var="cid" value=$contact->getContactId()}
			<tr id="ajax_contact_row_{$cid}" {if ($smarty.foreach.adrbook_pop.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
				<td>
					{form_checkbox name="contacts[]" id="ajax_contact_`$cid`" value="`$cid`" checked='false' onchange="checkActive(this, 'ajax_contact_row_', '$cid', '');"}
					{form_hidden id="$cid" name="0"}
				</td>
				<td>
					<a href="{$contact->url}">{$contact->displayName|escape:"html"}</a>
				</td>        
				<td>
					{$contact->getEmailsAsString()|escape:"html"}
				</td>
				<td>
				{foreach item=contactList from=$contact->getParentContactLists()}
					<a href="{$currentUser->getUserPath('addressbookmaillist')}id/{$contactList->getContactId()}/">
					{$contactList->getDisplayName()|escape:"html"};
					</a>
				{/foreach}
				&nbsp;
				</td>
				<td>
					<a href="{$contact->profile}">
						{if $contact->avatar}
							<img src="{$contact->avatar->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" border="0" />
						{/if}
					</a>
				</td>
			</tr>
		{/foreach}
		</table>
		<div>
				 {$infoPaging} {$linkPaging}
		</div>
	<div class="prInnerTop prTCenter prClr3">
			{t var='button'}Add contacts to the list{/t}
		   {form_submit id="addContactToList" name="add" value=$button}
	</div>      

{/form}
{else}

    <div>{t}You haven't contacts to add to this list{/t}</div>

{/if}