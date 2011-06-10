{if $contacts}
    {form from=$form id="addContactToListForm" name="addContactToListForm" onsubmit="xajax_addAddressToField(xajax.getFormValues('addContactToListForm'),document.getElementById('inv_emails').value, CreateEventApp.getInviteListObjects(), CreateEventApp.getInviteGroupObjects()); return false;"}

	<div class="prClr2 prInnerTop">
        <ul>
            <li class="prFloatLeft prIndentLeftSmall"><a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'firstname','asc');return false;">{t}All{/t}</a></li>
            <li class="prFloatLeft prIndentLeftSmall">-</li>
            {foreach from=$letters  item=l}
                    {if $l.count}<li class="prFloatLeft prIndentLeftSmall"><a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'{$orderBy}','{$direction}','{$l.letter}');return false;">{$l.letter}</a></li>{else}<li class="prFloatLeft prIndentLeftSmall">{$l.letter}</li>{/if}
            {/foreach}
            <li class="prFloatLeft prIndentLeftSmall"><a href="#null" onclick ="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'contact_name','asc','lists');return false;">{t}Lists{/t}</a></li>
        </ul>
    </div>

    <div class="prPopupHeight">   
        <div class="prInnerTop prClr2">{$infoPaging} {$linkPaging}</div>
			<div class="prInner">
			<table class="prResult" cellpadding="0" cellspacing="0" border="0">
			   <col width="4%" />
			   <col width="52%" />
			   <col width="30%" />
			   <col width="14%" />
				<thead>	
					<tr>
						{if !$Lists}
							<th colspan="2" nowrap="nowrap">
								<a>{t}NAME{/t}&nbsp;(</a>																																											
								<a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'firstname','{$headers.firstname.direction}', '{$filter}');return false;" {if $headers.firstname.active} class="{if $headers.firstname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.firstname.output}</a><a>,&nbsp;</a>																																											
								<a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'lastname','{$headers.lastname.direction}', '{$filter}');return false;" {if $headers.lastname.active} class="{if $headers.lastname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.lastname.output}</a><a>)</a>
							</th>
							<th nowrap="nowrap"  colspan="2"> 
								 <a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'email','{$headers.email.direction}', '{$filter}');return false;" {if $headers.email.active} class="{if $headers.email.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.email.output}</a>
							</th>
							<th>
								{t}Profile{/t}
							</th>
						{else}
							<th colspan="4" nowrap="nowrap">
								<a href="#null" onclick="xajax_addFromAddressbook(xajax.getFormValues('addContactToListForm'),1,'contact_name','{$headers.contact_name.direction}', 'lists');return false;" {if $headers.contact_name.active} class="{if $headers.contact_name.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.contact_name.output}</a>
							</th>
						{/if}
					</tr>  
				</thead>
				{foreach item=contact from=$contacts name='event_adrb'}
					{assign var="cid" value=$contact->getContactId()}
					{if !$Lists}
					<tr id="ajax_contact_row_{$cid}" {if ($smarty.foreach.event_adrb.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
						<td class="prBorderBottom">
							{form_checkbox name="contacts[]" value=$cid checked=$currentContacts.$cid}
							{form_hidden name="contacts_emails_"|cat:$cid value="$cid"}
						</td>
						<td class="prBorderBottom">
							<a href="{$contact->url}">{$contact->displayName|escape:"html"}</a>
						</td>        
						<td class="prBorderBottom" colspan="2"> 
							{$contact->getEmailsAsString()|escape:"html"}
						</td>
						<td class="prBorderBottom">
							<a href="{$contact->profile}">
								{if $contact->avatar}
									<img src="{$contact->avatar->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" border="0" />
								{/if}
							</a>
						</td>
					</tr>
					{else}
					{assign var="cid" value=$contact->getContactId()}    
					<tr id="ajax_contact_row_{$cid}" {if ($smarty.foreach.event_adrb.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
						<td class="prBorderBottom">
							{form_checkbox name="contacts[]" value=$cid checked=$currentContacts.$cid}
							{form_hidden name="contacts_emails_"|cat:$cid value="$cid"}
						</td>
						<td class="prBorderBottom" colspan="3"><a href="{$contact->url}">{$contact->displayName|escape:"html"}</a></td>
					</tr>
					{/if}
				{/foreach}
			</table>
         <div class="prInnerTop">{$infoPaging} {$linkPaging}</div>
    	</div>
    </div>
        <div class="prInnerTop prTCenter prClr">
		{t var='button'}Add contacts to the list{/t}
               {form_submit name="add" value=$button}
        </div>      
 
    </div>
    {/form}
{else}
        <div>{t}You haven't contacts to add to this list{/t}</div>

{/if}