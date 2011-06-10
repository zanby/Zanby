<script type="text/javascript" src="/js/checkboxes_addressbook.js"></script>
<script type="text/javascript">
{literal}
    function remove_child(contactId)
    {
        var element = document.getElementById('newContacts');
        var cont_ids = element.value.split(',');
        if (cont_ids[0] =="") cont_ids.shift();
        var k=-1;
        for (var i=0; i <= cont_ids.length-1; i++)
           if (cont_ids[i] == contactId)
           {
               k=i; 
               break;
           }
        cont_ids.splice(k, 1);
        element.value = cont_ids.toString();
        document.getElementById('divId').removeChild(document.getElementById('li_' + contactId));
    }
{/literal}
</script>
<!-- menu end -->
<a href="{$user->getUserPath('addressbook')}">{t}Back to Addressbook{/t}</a>
<h2>{t}Address Book &ndash; Add New Mailing List{/t}</h2>
<div class="prAddressBookContent">
	<div class='prAddressBookLeft'> {form from=$form}
		<div>{form_errors_summary}</div>
		<div>
			<label for="nameList">{t}Name of this list:{/t}</label>
			<br />
			{form_text name="nameList" maxlength="30" value=$nameList|escape:"html"} </div>
		{form_hidden id="listContacts" name="listContacts" value=$contactIds}  
		{form_hidden id="newContacts" name="newContacts" value=""}
		<h3>{t}Add to Mailing List{/t}</h3>
		<label for="addContacts">{t}Type contact names to Add:{/t}</label>
		<div class="prIndentTop">{form_text id="addContacts" name="addContacts" maxlength="30" value=$addContacts|escape:"html"}</div>
		<label class="prIndentTop">{t}OR{/t}</label>
		<div class="prIndentTop">
		{t var='button_01'}Pick contacts from addressbook{/t}
		{linkbutton name=$button_01 onclick="xajax_addressbookAjaxUtilities('null',document.getElementById('listContacts').value,document.getElementById('newContacts').value,1,10,'firstname','asc'); return false;"}</div>
		<ul class="prIndentTop" id="divId">
			{foreach item=contact from=$contacts}
			<li id='li_{$contact->getContactId()}'>
				<input type="hidden" value="{$contact->getContactId()}" name="contacts[]" />
				{$contact->displayName} <a href="#null" onclick="document.getElementById('divId').removeChild(document.getElementById('li_{$contact->getContactId()}'));"><img src="{$AppTheme->images}/decorators/profile-marker.gif" /></a> </li>
			{/foreach}
		</ul>
		<div class="prInnerTop">
		{t var='button_02'}Add New List{/t}
		{form_submit value=$button_02 name="submit"}</div>
		{/form} </div>
	<div class='prAddressBookRight'> {include file="users/addressbook/mailinglist.tpl"} </div>
	<!-- right column end -->
</div>
