<script type="text/javascript">
{literal}
    function check_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='checkbox') {
                if (!form.elements[i].disabled && form.elements[i].value) form.elements[i].checked = true;
            }
        }
    }
    function clear_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='checkbox') {
                if (form.elements[i].value) form.elements[i].checked = false;
            }
        }
    }
{/literal}
</script>
{if $action == "findfriends"}
	{t var="title"}My Friends{/t}
{else}
	{t var="title"}My Messages{/t}
{/if}
    <h2>{t}{tparam value=$contactsCount}{tparam value=$SITE_NAME_AS_STRING}You have %s friends from your addressbook <br />
      that are NOT using %s{/t}</h2>
    <div class="prInnerSmallTop">
        {if $countFriended}
            {t}{tparam value=$countFriended}{tparam value=$SITE_NAME_AS_STRING}%s  Friends have been added to your %s Friends List.  Your contacts will receive your invitation soon.{/t}<br />
        {/if}
        {if $contactsCount}
        {t}{tparam value=$SITE_NAME_AS_STRING}Select contacts below and add them to your  addressbook.<br />
        We will send them an invitation to connect with you as a %s Friend if you wish.{/t}
        {/if}
    </div>
	{if $contactsCount}
    {form id="addContacts" from=$formAddContacts}
        <div class="prInnerLeft prInnerRight">
            <div class="prInnerSmallTop">
            <div> {t}Select:{/t} <a href="#" onclick="check_all_checkboxes(document.getElementById('addContacts')); return false;">{t}All{/t}</a> | <a href="#" onclick="clear_all_checkboxes(document.getElementById('addContacts')); return false;">{t}None{/t}</a> </div>
        </div>
        <div class="prBorderTop prClr3">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class='prResult'>
                <col width="6%" align="center" />
                <col width="54%" />
                <col width="40%" />
                {foreach from=$contacts item=contact}
                    {assign var='email' value=$contact->getEmail()}
                    <tr>
                        <td>
                            {if (in_array($contact->getEmail(), $addressbook))}
                                {form_checkbox name="items[contacts][$email]" id="items[contacts][$email]" disabled=1 checked='false'}
                            {else}
                                {form_checkbox name="items[contacts][$email]" id="items[contacts][$email]" checked='false'}
                            {/if}
                        </td>
                        <td>{$contact->getFirstName()|cat:" "|cat:$contact->getLastName()|escape:html}</td>
                        <td>{$email|escape:html}</td>
                    </tr>
                {/foreach}
            </table>
        </div>
        </div>
        <div class="prFloatRight prInner">
        <div class="prInnerTop prInnerBottom">
        {form_checkbox name="items[invite]" value=$items.invite id="checkboxInvite"} <label for="checkboxInvite">{t}{tparam value=$SITE_NAME_AS_STRING}Invite to connect as %s friend{/t}</label>
        </div>
		{t var='button_01'}Add to addressbook{/t}
        {form_submit name="items[add]" value=$button_01}
		{t var='button_02'}No Thanks{/t}
        {form_submit value=$button_02}
        </div>
    {/form}  
       {else}
       <div class="prInnerLeft prInnerRight">
	   {t var='button_03'}Continue{/t}
	   {linkbutton name=$button_03  onclick="document.location = '/en/importcontacts/import/5/value/1'"}</div>
       {/if}