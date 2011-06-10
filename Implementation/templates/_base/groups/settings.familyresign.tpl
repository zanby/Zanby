{literal}
	<script language="javascript">
		function GroupSettingsResign_over() {
			document.getElementById("GroupSettingsResignTitle").style.textDecoration = "underline";
			document.getElementById("GroupSettingsResignImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";

		}
		function GroupSettingsResign_out() {
			document.getElementById("GroupSettingsResignTitle").style.textDecoration = "none";
			document.getElementById("GroupSettingsResignImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
		}
		function changeHost() {
			var newhost_value = document.getElementById("newhost").value;
			xajax_privileges_resign_change_host(newhost_value);
			return false;			
		}
		function handleResign(gid) {
			var subject = document.getElementById("resign_send_message_subject").value;
			var sbody = document.getElementById("resign_send_message_body").value;
			xajax_privileges_resign_handle(subject, sbody);
			return false;
		}
	</script>
{/literal}
{if $visibility_details == "resign"}
	<script>xajax_privileges_resign_show('{$groupId}');</script>
	{else}
   		{if $visibility == true}
    	{if $hostIsResidned}
       		 <div class="prClr3">
            	{contentblock}
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <font class="black_title">{t}{tparam value=$CurrentGroup->getName()|escape:"html"}
                                We have sent a message to [USERNAME] or email@host.com asking to replace you as the Owner/Facilitator of the %s.{/t}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                <tr>
                                    <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                    <td>{t}
                                       A copy of your message can be viewed in the sent messages folder of your Group Family Email{/t}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                    <td>
                                        <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/">{t}Back to Group Family Tools{/t}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25" valign="top" align="center"><img src="{$AppTheme->images}/decorators/groups/li_triangule.gif" border="0"></td>
                                    <td>{t}
                                        If [Username] accepts the invitation, you will no longer be able to access this account.<br>
										If you have a special question, please email <a>Contact Us</a>.{/t}
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
        <div class="prClr3">
            {contentblock}
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <font class="black_title">{t}{tparam value=$newhost|escape:"html"}{tparam value=$CurrentGroup->getName()|escape:"html"}
    							We have sent a message to '%s' asking to replace
    							you as host of %s{/t}
                            </font>
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
                                    <td>{t}{tparam value="#"}
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
        </div>
    {/if}


    {if $resign_send_message_form}
    	<table width="100%" cellpadding="0" cellspacing="0" border="0">
    		<tr>
    			<td colspan="4">
    				<br />
    				<table width="100%" cellpadding="4" cellspacing="0" border="0">
    					<tr>
    						<td>{t}Send a message to your group{/t}</td>
    					</tr>
    					<tr>
    						<td>{t}
    							The following message will be sent to all group members.
    							Instructions for appointing a new host will be added on to your
    							message.{/t}
    						</td>
    					</tr>
    					{if $ErrorString}
    					<tr>
    						<td>{$ErrorString}</td>
    					</tr>
    					{/if}
    					<tr>
    						<td>
    							<input type="text" id="resign_send_message_subject" name="resign_send_message_subject" value='{$resign_send_message_subject|escape:"html"}' size="50"/>
    						</td>
    					</tr>
    					<tr>
    						<td>
    							<textarea id="resign_send_message_body" name="resign_send_message_body" style="width:100%;height:100;"></textarea>
    						</td>
    					</tr>
    					<tr>
    						<td>{t}
    							The following message will be sent to all group members.
    							Instructions for appointing a new host will be added on to your
    							message.{/t}
    						</td>
    					</tr>
    					<tr>
    						<td>
    							<table width="100%" cellpadding="2" cellspacing="0" border="0">
    								<tr>
    									<td width="30%">&nbsp;</td>
    									<td>
    									  <h3>{t}WARNING!{/t}</h3>
    									  <span>{t}{tparam value=$group->title}Clicking "Permanently Resign as Host of this group" below will remove all of your access privileges to %s.{/t}</span>
    									</td>
    								</tr>
    								<tr>
    									<td class="prTLeft">
											{t var="in_button"}Go Back{/t}
    										{linkbutton name=$in_button link="#" onclick="xajax_privileges_resign_show(`$gid`); return false;"}
    									</td>
    									<td class="prTLeft">
											{t var="in_button_2"}Permanently Resign as Host of this group{/t}
    										{linkbutton name=$in_button_2 link="#" onclick="handleResign(`$gid`); return false;"}
    									</td>
    								</tr>
    							</table>
    						</td>
    					</tr>
    				</table>
    			</td>
    		</tr>
    	</table>
    {/if}


{if $resign_step2}
		<p class="prIndentTop">{t}The following message will be sent to all group members. {/t}</p>
    	<h3>{t}Resign as Group Facilitator{/t}</h3>
    	<p>{t}Send a message to the group family giving a brief explanation about why the family is being disbanded. Instructions for appointing a new host will be added on to your message.{/t}</p>
		{if $ErrorString}
    		<div class="prFormErrors">{$ErrorString}</div>
		{/if}
		<table class="prForm">
		 	<col width="25%" />
			<col width="75%" />	
			<tr>
				<td><label for="resign_send_message_subject">{t}Subject:{/t}</label></td>
				<td><input type="text" id="resign_send_message_subject" name="resign_send_message_subject" value='{$resign_send_message_subject|escape:"html"}' size="50"/>
				</td>
			</tr>
			<tr>
				<td><label for="resign_send_message_body">{t}Message:{/t}</label></td>
				<td><textarea id="resign_send_message_body" name="resign_send_message_body"></textarea>
				</td>
			</tr>
		</table>
    	<p class="prIndentTop prFormMessage">{t}WARNING!{/t}<br />
		{t}Clicking "Resign as Group family Facilitator" below will permanently remove your relationship to the family. You will not be able to access the family admin tools.{/t}</p>
    	 <div class="prTRight prIndentTop">
		 {t var="in_button_3"}Go Back{/t}
		 {linkbutton name=$in_button_3 link="#" onclick="xajax_privileges_resign_show(`$gid`); return false;"}&nbsp;
		 {t var="in_button_4"}Resign as facilitator{/t}
		 {linkbutton name=$in_button_4 link="#" onclick="handleResign(`$gid`); return false;"}
    	</div>    			
    {/if}

    {if !$hostIsResidned && !$confirm_change_host && !$resign_send_message_form && !$resign_step2}      
			   <p class="prIndentTop prIndentBottom prText2">{t}You may resign as host and appoint a new host.{/t}</p>      
			   <p>{t}{tparam value=$SITE_NAME_AS_STRING}
				Please enter the %s Handle of the person who will owner of this group family.{/t}</p>				
				{if $errors}
					{include file="_design/form/form_errors_summary.tpl"}
				{/if}				
				<div class="prIndentTop">				
					<div class=" yui-skin-sam">
						<div class="yui-ac">
							<input type="text" id="newhost" name="newhost" value="" size="30" />
							<div id="acMembers"></div>
						</div>
					</div>				
				</div>
			           					
			   <p class="prIndentTop">{t}{tparam value=$SITE_NAME_AS_STRING}
				Please discuss the proposed change with the person who will be the facilitator of the group family before you send this email. %s will send a message to the named replacement that explains the proposed transition.{/t}
			   </p>					
			            									         
				<p class="prIndentTop prText2">{t}For the change to become effective the recipient must:{/t}</p>
				
				<ul class="prUnorderedList">
					<li>{t}Be the host of a family member group{/t}</li>
					<li>{t}{tparam value=$SITE_NAME_AS_STRING}Confirm that they will become the facilitator by clicking a link supplied by %s in the email.{/t}</li>
				</ul>
				 
				<p class="prIndentTop">{t}
					If there is no response to the email, you will continue to be the host of this group until you permanently resign your position as host.{/t}
				</p>
				          
				<div class="prTRight prIndentTop">
					{t var="in_button_5"}Change Facilitator{/t}
					{linkbutton name=$in_button_5 link="#" onclick="changeHost(); return false;"}
				</div>				     					
    		{/if} 
		{/if}
{/if}