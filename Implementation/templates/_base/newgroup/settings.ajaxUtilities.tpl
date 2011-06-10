{if $contacts}
{form from=$form id="addContactToListForm" name="addContactToListForm" onsubmit="xajax_addaddresses(xajax.getFormValues('addContactToListForm')); return false;"}
<div>
  <ul>
	<li><a href="#null" onclick="xajax_addressbookAjaxUtilities(xajax.getFormValues('addContactToListForm'),1,{$pageSize},'firstname','asc');return false;">{t}All{/t}</a></li>
	<li>-</li>
	{foreach from=$letters  item=l}
			{if $l.count}<li><a href="#null" onclick="xajax_addressbookAjaxUtilities(xajax.getFormValues('addContactToListForm'),1,{$pageSize},'{$orderBy}','{$direction}','{$l.letter}');return false;">{$l.letter}</a></li>{else}<li>{$l.letter}</li>{/if}
	{/foreach}
  </ul>
</div>

<div>
   
<div>
            <!--{form_checkbox name="check_uncheck" checked="0" onclick="check_uncheck_checkboxes(this, 'addContactToListForm', 'ajax_contact_');"}-->
	  	    <div>{$infoPaging} {$linkPaging}</div>

	<table cellpadding="0" cellspacing="0" border="0">
	   <col width="4%" />
	   <col width="62%" />
	   <col width="20%" />
	   <col width="14%" />
	<tr>
		<th colspan="2" nowrap="nowrap">
		   <a>{t}NAME {/t}</a>
		   <a style="padding-left: 0; padding-right: 0;">(</a>
		   <a href="#null" onclick="xajax_addressbookAjaxUtilities(xajax.getFormValues('addContactToListForm'),1,{$pageSize},'firstname','{$headers.firstname.direction}', '{$filter}');return false;" {if $headers.firstname.active} class="prRActive"{/if}>{$headers.firstname.output}</a>
		   <a style="padding-left: 0;">,</a>
		   <a href="#null" onclick="xajax_addressbookAjaxUtilities(xajax.getFormValues('addContactToListForm'),1,{$pageSize},'lastname','{$headers.lastname.direction}', '{$filter}');return false;" {if $headers.lastname.active} class="prRActive"{/if}>{$headers.lastname.output}</a>
		   <a style="padding-left: 0; padding-right: 0;">)</a>
		</th>
		<th nowrap="nowrap"  colspan="2"><!-- znbSortUp -->
			 <a href="#null" onclick="xajax_addressbookAjaxUtilities(xajax.getFormValues('addContactToListForm'),1,{$pageSize},'email','{$headers.email.direction}', '{$filter}');return false;" {if $headers.email.active} class="prRActive"{/if}>{$headers.email.output}</a>
		</th>
		<th>
			{t}Profile{/t}
		</th>
	</tr>
	{if $currentContacts}
	{foreach item=contact from=$contacts name='ngrp_adrbook'}
		{assign var="cid" value=$contact->getContactId()}
		<tr id="ajax_contact_row_{$cid}" {if ($smarty.foreach.ngrp_adrbook.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
			<td>
				{form_checkbox name="contacts[]" value="$cid" checked="$currentContacts.$cid"}
				{form_hidden name="contacts_emails_"|cat:$cid value=$contact->getEmail()|escape:"html"}
			</td>
			<td>
					<a href="{$contact->url}">{$contact->displayName|escape:"html"}</a>
			</td>        
			<td colspan="2">
				{$contact->getEmailsAsString()|escape:"html"}
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
	{/if}
	</table>
			  <div>
				  {$infoPaging} {$linkPaging}
			  </div>
	</div>
	<div class="prClr3" style="float:right; margin: 10px 5px 10px 0; display:inline; width: 200px;" >
			{t var='button'}Add contacts to the list{/t}
		   {form_submit name="add" value=$button}
	</div>      
</div>
{/form}
{else}
    <div>{t}You haven't contacts to add to this list{/t}</div>
{/if}
