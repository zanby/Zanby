    <script type="text/javascript" src="/js/checkboxes.js"></script>
	<h2>{t}Address Book &ndash; All Contacts{/t}</h2>
	
	<!-- alf -->
		<a href="{$urls.for_filter}">{t}All{/t}</a> -
		{foreach from=$letters  item=l}
			{if $l.count}<a href="{$urls.for_filter}filter/{$l.letter}/">{$l.letter}</a> {else}{$l.letter} {/if}
		{/foreach}
	<!-- alf end-->	
	<div class="prAddressBookContent">
	    {if $contacts}
			<div class='prAddressBookLeft'>
				{form from=$formAddressbook id="formAddressbook"}
					{form_hidden id="newContacts" name="newContacts" value=""}
					<div class="prClr3">
					<div class="prFloatLeft prIndentTopSmall prButtonPanel">
					{t var='button_01'}Delete{/t}
					{linkbutton name=$button_01 onclick="xajax_addressbookDeleteContact('$addressbookId',document.getElementById('newContacts').value, 'notShowed','false');return false;"}</div>
					<div class="prFloatRight prPaginatorRight">{$infoPaging} {$linkPaging}</div>
					</div>
					{include file="users/addressbook/_contacts.tpl"}
					<div class="prIndentTop prClr3">
					    <div class="prFloatLeft prInnerSmallBottom prInnerSmallTop">
						{t var='button_02'}Delete{/t}
						{linkbutton name=$button_02 onclick="xajax_addressbookDeleteContact('$addressbookId',document.getElementById('newContacts').value, 'notShowed','false');return false;"}</div>
						<div class="prFloatRight prPaginatorRight">{$infoPaging} {$linkPaging}</div>
					</div>
				  {/form}
			</div>
	    {else}
				{t}Your addressbook is empty{/t}
	    {/if}
		<div class='prAddressBookRight'>
			{include file="users/addressbook/mailinglist.tpl"}
		</div>
	    <!-- right column end -->
    </div>
