<script type="text/javascript">  
{literal}
    function doInvite()
    {
        {/literal}
        var prop = [];
        prop["entityType"]  = "group";
        prop["entityId"]    = "{$CurrentGroup->getId()}";
        prop["entityUid"]   = 0;
        prop["returnUrl"]   = location.href;
        xajax_setInviteProperties(prop);
        {literal}
    }
    
{/literal}

</script>
{form from=$form id="formStep5"}
<table class="prForm">     
    <col width="20%" />
    <col width="75%" />
	<col width="5%" />
	<tr>
		<th colspan="3">{form_errors_summary}
		</th>
	</tr>
    <tr>
        <td  class="prTRight">
            <label for="r1"><span class="prMarkRequired">* </span>{t}From:{/t}</label>
        </td>
        <td>
            {form_radio id="r1" name="mail"  value="1" checked=$group.mail|default:"1"}<label for="r1"> {$currentUser->getEmail()}</label>
			<div class="prIndentTopSmall">
            {form_radio id="r2" name="mail"  value="2" checked=$group.mail|default:"1"}<label for="r2"> {$CurrentGroup->getGroupEmail()}</label>
			</div>
        </td>
		<td></td>
    </tr>
    {if FACEBOOK_USED}
        <tr class="prInnerTop">  
            <td></td>
            <td>
            <a href="javascript:void(0)" onclick="doInvite();FBApplication.oninvite_friends_toevent('external'); return false;">
                {t}Invite Friend from Facebook{/t}
            </a>
            <img alt="" src="{$AppTheme->images}/decorators/icons/icoFB_small.gif" class="prIndentTop" />
            </td>
        </tr>
    {/if}
    {if FACEBOOK_USED}
        <tr class="prInnerTop" style="{if !$formParams.event_invitations_fbfriends}display:none{/if}" id="EventInviteFBFriendsObjects">
            {include file="facebook/invitefriends.template.invited.tpl"}
        </tr>
    {/if}
    <tr>
		<td></td>
		<td>                        
            <p class="prTip">
                {t}Input email addresses or username separated by a comma.{/t}
            </p>    
        </td>
		<td></td>
    </tr>
    <tr>       
        <td class="prTRight">
            <label for="emails"><span class="prMarkRequired">* </span>{t}To:{/t}</label>  
        </td>
        <td>
        {form_textarea id="emails" name="emails" value=$group.emails|escape:"html"}
        </td>
		<td></td>
    </tr>
    <tr>       
        <td class="prTRight">
        <label for="subject"><span class="prMarkRequired">* </span>{t}Subject:{/t}</label>
        </td>
        <td>{form_text name="subject" id="subject" value=$group.subject|escape:"html"}</td>
		<td></td>
    </tr>                                            
    <tr>        
        <td class="prTRight">
        <label for="message"><span class="prMarkRequired">* </span>{t}Message:{/t}</label>
        </td>
        <td>{form_textarea id="message" name="message" cols="70" rows="5" value=$group.message|escape:"html"}</td>
		<td></td>
    </tr>
</table>
<div class="prTCenter prInnerTop">
{t var="in_button"}Send Message{/t}
{linkbutton name=$in_button onclick="xajax_invitemembers("|cat:$CurrentGroup->getId()|cat:", xajax.getFormValues('formStep5')); return false;"}       
    	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
		</div>
{/form}