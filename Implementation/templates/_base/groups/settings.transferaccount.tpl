{literal}
	<script language="javascript">
	function GroupSettingsTransfer_over() {
		document.getElementById("GroupSettingsTransferTitle").style.textDecoration = "underline";
		document.getElementById("GroupSettingsTransferImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";

	}
	function GroupSettingsTransfer_out() {
		document.getElementById("GroupSettingsTransferTitle").style.textDecoration = "none";
		document.getElementById("GroupSettingsTransferImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
	}
	</script>
{/literal}

{if $visibility_details == "transfer"}
	<script>xajax_privileges_transfer_show();</script>
{else}

<div>
<div>
	{if $visibility == true }
	{if $ErrorString}<div>{t}{tparam value='ssssssssss'}<span>ERROR:</span> %s{/t}</div>{/if}
	
    {form from=$form onsubmit="xajax_privileges_transfer_do(xajax.getFormValues('transferForm')); return false;" id="transferForm" name="transferForm"}
    {form_errors_summary width="90%"}
    {form_hidden name="groupId" id="groupId" value=$currentGroup->getId()}

	<!-- -->
	<table width="100%" class="prInnerTop">
	<col width="20%"/>
	<col width="35%"/>
	<col width="45%"/>
	<tbody>			
        <tr class="prVTop">
            <td nowrap="nowrap" colspan="3">
                <p class="prIndentTop prText2">{t}Identify new owner{/t}</p>
            </td>
        </tr>		
		<tr class="prVTop">
			<td nowrap="nowrap" class="prTRight">
				<span class="prMarkRequired">*</span> <label>{t}New Owner:{/t}</label>
			</td>
			<td>
			    {form_text name='new_owner' size=40 value=$new_owner}
			</td>
			<td class="prTip">
				{t}{tparam value=$SITE_NAME_AS_STRING}%s Username or  email address of the person  who will own the family. {/t}         
			</td>
		</tr>
        <tr class="prVTop">
            <td nowrap="nowrap" colspan="3">
                <p class="prIndentTop prText2">{t}Compose a message to the new owner{/t}</p>
            </td>
        </tr>       
        <tr class="prVTop">
            <td colspan="3">
                {t}Compose a message to Username if you wish. Instructions for accepting the position as group family owner will be added on to your message.{/t}
            </td>
        </tr>       
		<tr>
			<td class="prTRight">
				<span class="prMarkRequired">*</span> <label>{t}Subject:{/t}</label>
			</td>	
			<td colspan=2>	
			    {form_text id="message_subject" name="message_subject" style="width: 450px;" value=$message_subject|escape:"html"}		     						
			</td>
		</tr>					
		<tr>
			<td class="prTRight prVTop">
				<span class="prMarkRequired">*</span> <label>{t}Message:{/t}</label>
			</td>
			<td colspan=2>
			    {form_textarea style="width: 450px; height: 140px;" id="message_body" name="message_body" value=$message_body}	
			</td>
		</tr>
		<tr>
		   <td class="prTRight"></td>
		   <td colspan="2" class="prInnerTop">
               {t var="in_submit"}Transfer Group Family Account{/t}
               {form_submit name="form_save" value=$in_submit}
			</td>
		</tr>	
        <tr class="prVTop">
            <td nowrap="nowrap" class="prTRight">&nbsp;</td>
            <td>&nbsp;</td>
            <td class="prTip">
                {t}Confirm that they will become the owner by clicking a link supplied by Zanby in the email.{/t}         
            </td>
        </tr>
        <tr class="prVTop">
            <td nowrap="nowrap" class="prTRight">&nbsp;</td>
            <td>&nbsp;</td>
            <td class="prTip">
                {t}If there is no response to the email, you will continue to be the owner of this group until you permanently resign your position as family owner.{/t}         
            </td>
        </tr>
	</tbody>
	</table>
	<!-- / -->
	
	{/form}
{/if} 
 </div>
</div>
{/if}
