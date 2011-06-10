{if $action == "findfriends"}
	{t var="title"}My Friends{/t}
{else}
	{t var="title"}My Messages{/t}
{/if}
	
    {form from=$formViewContacts}

                    <h3>{t}{tparam value=$contactsCount}You have %s contacts <strong>in your address book</strong>{/t} </h3>
                    <div>
						{t}If encoding doesn't match your addressbook's encoding, please, select one from drop-down list{/t}
					</div>	
						{form_select name="items[encoding]" options=$optionsEncodings selected=$optionsEncodingSelected onchange="this.form.submit()"}
                    

                    <!-- table -->
                    <table class="prResult prIndentTop">
                          <col width="50%"/>
                          <col width="50%"/>
                          {foreach from=$contacts item="c"}
                          <tr>
                              <td class="prBorderTop">
                                  <strong>{$c->getFirstName()|cat:" "|cat:$c->getLastName()|escape:html}</strong>
                              </td>
                              <td class="prBorderTop">
                                  {$c->getEmail()}
                              </td>
                          </tr>
                          {/foreach}
                    </table>
                    
                    <div>
						{t var='button'}Next{/t}
                        {form_submit name="items[continue]" value=$button}
                     </div>
                     {/form}	