<script type="text/javascript">
{literal}
    function check_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='checkbox') {
                if (!form.elements[i].disabled) form.elements[i].checked = true;
            }
        }
    }
    function clear_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='checkbox') {
                form.elements[i].checked = false;
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
     {form id="addFriends" from=$formAddFriends }
        {form_hidden id="checked_mes_ids" name="checked_mes_ids" value=""}
							<h2>{t}{tparam value=$usersCount}{tparam value=$SITE_NAME_AS_STRING}You have %s friends from your addressbook <br />
						  that are already using %s{/t}</h2>
					<div class="prInnerSmallTop">
						{t}Select contacts below and add them to your friends list and addressbook.{/t}
					</div> 
          	<div class="prInnerLeft prInnerRight">
				<div class="prInnerSmall">
				   {t}Select:{/t} <a href="#" onclick="check_all_checkboxes(document.getElementById('addFriends')); return false;">{t}All{/t}</a> | <a href="#" onclick="clear_all_checkboxes(document.getElementById('addFriends')); return false;">{t}None{/t}</a>
				</div>
				<div class="prBorderTop prClr3">
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="prResult">
						<col width="6%" align="center" />
						<col width="14%" />
						<col width="40%" />
						<col width="40%" />
							{foreach from=$users item="u"}
							{assign var='email' value=$u->getEmail()}
									{assign var='userId' value=$u->getId()}
									<tr>
									  <td>
							{if (in_array($email, $addressbook) and (in_array($u->getId(), $friends)))}
							 {form_checkbox name="items[contacts][$email]" id="items[contacts][$email]" value="$userId" disabled=1 checked='false'}
								{else}
							 {form_checkbox name="items[contacts][$email]" id="items[contacts][$email]" value="$userId"}
							{/if}
						  </td>
						  <td><a href="{$u->getUserPath('profile')}"><img src="{$u->getAvatar()->getSmall()}" alt="{$u->getFirstname()} {$u->getLastname()}"/></div></td>
						  <td>{$u->getFirstname()|cat:" "|cat:$u->getLastName()|escape:html}<br />
							  {t}{tparam value=$SITE_NAME_AS_STRING}%s Username:{/t}
							  <label>{$u->getLogin()}</label>
							</td>
						  <td>{$u->getEmail()}</td>
						</tr>
						{/foreach}                      
					 </table> 
				</div>	
			</div>
			<div class="prFloatRight prInner">
				{t var='button_01'}Add friends{/t}
			  	{form_submit name="items[add]" value=$button_01}
				{t var='button_02'}No Thanks{/t}
			  	{form_submit value=$button_02}
			</div>
		    
{/form} 
