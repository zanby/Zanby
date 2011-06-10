{literal}
	<script language="javascript">
		function changeHost() {
			var newhost_value = document.getElementById("newhost").value;
			xajax_privileges_resign_change_host(newhost_value);
			return false;
		}
		function handleResign() {
			var subject = document.getElementById("resign_send_message_subject").value;
			var sbody = document.getElementById("resign_send_message_body").value;
			xajax_privileges_resign_handle(subject, sbody);
			return false;
		}
	</script>
{/literal}
{if $visibility_details == "resign"}<script>xajax_privileges_resign_show('{$groupId}');</script>
{else}
	{if $visibility == true}	
        {if $hostIsResidned}
            <div style="prClr3">
                {contentblock}
                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                        <tr>
                            <td>                              
                                    {t}{tparam value=$CurrentGroup->getName()|escape:"html"}You have permanently resigned your position as host of group %s.{/t}                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>
                                            {t}{tparam value="#"}A copy of your message can be viewed in your <a href="%s">Messages</a>{/t}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>
                                            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>
                                            {t}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}We will help you in any way we can. Please feel free to
                                            <a href="http://%s/%s/info/contactus/">contact us</a> and we will do our best to help or explain.
                                            We love <a href="http://%s/%s/info/feedback/">feedback</a>!{/t}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                {/contentblock}
            </div>
        {/if}

        {if $confirm_change_host}           
                {contentblock}
                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                        <tr>
                            <td>
                                {t}{tparam value=$newhost|escape:"html"}{tparam value=$CurrentGroup->getName()|escape:"html"}
                                    We have sent a message to '%s' asking to replace
                                    you as host of %s{/t}                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>
                                            <a href="{$CurrentGroup->getGroupPath('settings')}">{t}Go back to group settings.{/t}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>{t}{tparam value=#}
                                            A copy of your message can be viewed in your <a href="%s">Messages</a>{/t}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>
                                            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                        <td>{t}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}
                                            We will help you in any way we can. Please feel free to
                                            <a href="http://%s/%s/info/contactus/">contact us</a> and we will do our best to help or explain.
                                            We love <a href="http://%s/%s/info/feedback/">feedback</a>!{/t}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                {/contentblock}           
        {/if}


        {if $resign_send_message_form}
        <p class="prIndentTop">{t}                
           <span class="prText2">Send a message to your group.</span>
            <br />
           The following message will be sent to all group members.	Instructions for appointing a new host will be added on to your message.{/t}
        </p>
            <table class="prForm">
                <col width="25%" />
                <col width="75%" />			
                <tbody>
                    <tr>					
                        <td class="prTRight"><label for="resign_send_message_subject">{t}Subject{/t}</label></td>
                       	<td><input type="text" id="resign_send_message_subject" name="resign_send_message_subject" value='{$resign_send_message_subject|escape:"html"}' size="50" autocomplete="off"/>					
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="resign_send_message_body">{t}Message{/t}</label></td>
                        <td><textarea id="resign_send_message_body" name="resign_send_message_body"></textarea></td>
                    </tr>									
                </tbody>
            </table>           
            <p class="prIndentTop">{t}{tparam value=$group->title}<span class="prMarkRequired prText2">WARNING!</span><br />
						Clicking "Permanently Resign as Host of this group" below will remove all of your access privileges to %s.{/t}</p>        	  
            <div class="prTRight prIndentTop">
                    {t var="in_button"}Permanently Resign as Host of this group{/t}{linkbutton name=$in_button link="#" onclick="handleResign(); return false;"}&nbsp;			
                    {t var="in_button_2"}Go Back{/t}{linkbutton name=$in_button_2 link="#" onclick="xajax_privileges_resign_show(); return false;"}
            </div>        
        {/if}

        {if !$hostIsResidned && !$confirm_change_host && !$resign_send_message_form}
                    
           <p class="prIndentTop prText2">{t}You may resign as host and appoint a new host.{/t}</p>	             
           <p class="prIndentTop">
           {t}Please enter the username of the group member who will become the host of this group.{/t}
            </p>
            <p class="prIndentTop">
            {if $errors}
                {include file="_design/form/form_errors_summary.tpl"}
            {/if}
            </p>		
            <p class="prIndentTop">		
                <div class=" yui-skin-sam">
                    <div class="yui-ac">
                        <input type="text" id="newhost" name="newhost" value="" size="30" />
                        <div id="acMembers"></div>
                    </div>
                </div>		
            </p>
            <p class="prIndentTop">{t}{tparam value=$SITE_NAME_AS_STRING}
            Please discuss the proposed change with the person who will be the host of this group before you send this email. %s will send a message that explains the proposed transition.{/t}
            </p>		             
            <p class="prIndentTop prText2">{t}For the change to become effective the recipient must:{/t}</p>		
            <ul class="prUnorderedList">
                <li>{t}Be a member of the group{/t}</li>
                <li>{t}{tparam value=$SITE_NAME_AS_STRING}Confirm that they will become the host by clicking a link supplied by %s in the email.{/t}</li>
            </ul>		      
            <p class="prIndentTop">{t}
                If there is no response to the email, you will continue to be the host of this group until you permanently resign your position as host.{/t}
            </p>
            <div class="prTRight">
                    {t var="in_button_3"}Change Host{/t}{linkbutton name=$in_button_3 onclick="changeHost(); return false;"}		
            </div>				     			
            <p class="prIndentTop prText2 prTCenter">{t}You may permanently resign as facilitator without appointing a replacement.{/t}</p>
            <p class="prIndentTop">{t}{tparam value=$SITE_NAME_AS_STRING}
                %s does not recommend this step. If for some reason you find it necessary to resign as the host of your group before a new host is appointed, please discuss this with your group. You will have an opportunity to send a message to your group after you click "resign."{/t}
            </p>		
            <div class="prTRight prIndentTop">
                {t var="in_button_4"}Resign as Host of this group{/t}{linkbutton name=$in_button_4 link="#" onclick="xajax_privileges_resign_send_form_show(); return false;"}							
            </div>			
        {/if}
    {/if}
{/if}
