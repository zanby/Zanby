<script type="text/javascript" src="/js/checkboxes_addressbook.js"></script>     
<a href="{$user->getUserPath('addressbook')}">{t}Back to Addressbook{/t}</a>
    <h2>{t}{tparam value=$maillist->getDisplayName()}Address Book - Mailing List: %s{/t}</h2>
	<!-- alf -->
	<div class="prClr2">
		<a href="{$urls.for_filter}">{t}All{/t}</a> -
		{foreach from=$letters  item=l}
			{if $l.count}<a href="{$urls.for_filter}filter/{$l.letter}/">{$l.letter}</a> {else}{$l.letter} {/if}
		{/foreach}
	</div>
	<!-- alf end--> 
	<div class="prAddressBookContent">
	<div class='prAddressBookLeft'>
{assign var='maillistId' value=$maillist->getContactListId()}    
	{form from=$formDel id="formDel"}
    {form_hidden id="listContacts" name="listContacts" value=$contactIds}
    {form_hidden id="newContacts" name="newContacts" value=""}
		{if $contacts}
					<div>
            			<div class="prFloatLeft prIndentRightSmall prIndentLeft">{form_checkbox class="prIndentLeft" name="check_uncheck" checked="false" onclick="check_uncheck_checkboxes(this, 'formDel', 'contact_');"}
						{t var='button_01'}Delete{/t}
                        {linkbutton name=$button_01 onclick="xajax_addressbookDeleteContact('$maillistId',document.getElementById('newContacts').value, 'notShowed');return false;"}
						{t var='button_02'}Delete MailList{/t}
            			{linkbutton name=$button_02 onclick="xajax_addressbookDeleteContact('$maillistId','$maillistId', 'notShowed','false', 'true'); return false;"}</div>
						<div class="prFloatLeft">{$infoPaging} {$linkPaging}</div>
					</div>
					{include file="users/addressbook/_contacts.tpl"}
					<div class="prIndentTop">
						<div class="prFloatLeft prIndentRightSmall">
						{t var='button_03'}Delete{/t}
						{linkbutton name=$button_03 onclick="xajax_addressbookDeleteContact('$maillistId',document.getElementById('newContacts').value, 'notShowed');return false;"}
						{t var='button_04'}Delete MailList{/t}
          				{linkbutton name=$button_04 onclick="xajax_addressbookDeleteContact('$maillistId','$maillistId', 'notShowed','false', 'true'); return false;"}</div>
						<div class="prFloatLeft">{$infoPaging} {$linkPaging}</div>
					</div>
	    {else}
			<div class="prFormMessage">
				{t}Mailing list is empty{/t}
			</div>
	    {/if}
		{/form}		
        {form from=$formAdd}
					<h3>{t}Add to Mailing List{/t}</h3>
        				{form_errors_summary width="225px" }
                        <label for="addContacts">{t}Type contact names to Add:{/t}</label>
						<div class="prIndentTopSmall">{form_text id="addContacts" name="addContacts" maxlength="30" style="width: 270px" value=$addContacts|escape:"html"}</div>
						<div class="prIndentTop prIndentBottom">
						{t var='button_05'}Add{/t}
						{form_submit name="add" value=$button_05}</div>
						<label>{t}OR{/t}</label>
                        <div class="prIndentTop">
						{t var='button_06'}Pick contacts from addressbook{/t}
						{linkbutton name=$button_06 onclick="xajax_addressbookAjaxUtilities('$maillistId',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'firstname','asc'); return false;"}</div>
                         
    	{/form}
		</div>
		<div class='prAddressBookRight'>
			{include file="users/addressbook/mailinglist.tpl"}
		</div>
	    <!-- right column end -->
    </div>   