{if $visibility_details == "logininformation"}
	<script type="text/javascript">xajax_settings_loginInformation_show();</script>
{else}
    {if $visibility == true}
        <!-- form begin -->
        {form from=$form onsubmit="xajax_settings_loginInformation_save(xajax.getFormValues('liForm')); return false;" id="liForm" name="liForm"}
		{form_errors_summary}
            <table class="prForm">
                <col width="30%" />
                <col width="40%" />
                <col width="30%" />               
                <tbody>
                    <tr>
                        <td class="prTRight"><label for="login">{t}User Name:{/t}</label></td>
                        <td>{form_text name="login" value=$edituser->getLogin()|escape:"html"}</td>
                        <td rowspan="2" class="prTip">{t}If you do not want to change your password just leave Password fields empty {/t}</td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="old_pass">{t}Current Password:{/t}</label></td>
                        <td>{form_password id="old_pass" name="old_pass" value=$old_pass|escape:"html"}</td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="new_pass">{t}New Password:{/t}</label></td>
                        <td>
                            {assign var="temp_url" value="http://$BASE_HTTP_HOST/$LOCALE/info/strength/"}
                            {form_password id="new_pass" name="new_pass" value=$new_pass|escape:"html" onKeyPress="startPasswordChange(event, '$temp_url');"}									
						</td>
                        <td class="prTip">
                            <span id="pswdstrengthLabel" style="display:none;">
                                <a href="{$temp_url}" target=_blank>{t}Password strength{/t}</a>:</span>
                            <span id="pswdstrength"></span>									
						</td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="new_pass_confirm">{t}Confirm New Password:{/t}</label></td>
                        <td>
                            {form_password name="new_pass_confirm" value=$new_pass_confirm|escape:"html"}									
						</td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="email">{t}Email Address:{/t}</label></td>
                        <td>
                            {form_text name="email" value=$edituser->getEmail()|escape:"html"}									
						</td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="email_confirm">{t}Confirm Email Address:{/t}</label></td>
                        <td>
                            {form_text name="email_confirm" value=$email_confirm|escape:"html"}									
						</td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"></td>
                        <td>
							{t var='button_01'}Save{/t}
							{form_submit name="form_save" value=$button_01}
							<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="TitltPaneAppAccountLoginInformation.hide(); return false;">{t}Cancel{/t}</a></span>
                        </td>
                        <td class="prTip"></td>
                    </tr>
                </tbody>
            </table>
        {/form}
        <!-- form end -->
    {/if}
{/if}