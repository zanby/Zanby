<table style="background-color:#000000" width="100%">
    <tr>
        <td style="padding:10px;"><img src="/images/credentialRegistrationFormLogo.gif"></td>
        <td></td>
    </tr>
</table>
<h2 style="color:#000000;">{t}{tparam value=$SITE_NAME_AS_STRING}Register for your %s Media Room Credentials{/t}</h2>
{t}Applications are accepted on a rolling basis - space is extremely limited APPLY NOW!{/t}
<br /><br />
{if !$confirm}
    {form from=$form id="credentialRegistrationForm" enctype="multipart/form-data"}
    {form_errors_summary}
    <table class="prForm">
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="firstname">{t}First Name:{/t}</label></td>
            <td style="width:65%">{form_text name="firstname" id="firstname" value=$params.firstname|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="lastname">{t}Last Name:{/t}</label></td>
            <td>{form_text name="lastname" id="lastname" value=$params.lastname|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="email">{t}Email Address:{/t}</label></td>
            <td>{form_text name="email" id="email" value=$params.email|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="address1">{t}Address 1:{/t}</label></td>
            <td>{form_text name="address1" id="address1" value=$params.address1|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><label for="address2">{t}Address 2:{/t}</label></td>
            <td>{form_text name="address2" id="address2" value=$params.address2|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="phone">{t}Phone:{/t}</label></td>
            <td>{form_text name="phone" id="phone" value=$params.phone|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="organization">{t}Organization:{/t}</label></td>
            <td>{form_text name="organization" id="organization" value=$params.organization|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="website">{t}Website:{/t}</label></td>
            <td>{form_text name="website" id="website" value=$params.website|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="description">{t}Briefly describe your{/t}<br/> coverage plans:</label></td>
            <td>{form_textarea name="description" id="description" value=$params.description|escape:html}</td>
            <td class="prTip"></td>
        </tr>		
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="filename">{t}Upload a picture<br/> for media credential:{/t}</label></td>
            <td>{form_file name="filename" id="filename" value=$params.filename|escape:html}</td>
            <td class="prTip"></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right;">
				{t var='button_01'}Send message{/t}
                {linkbutton color="blue" name=$button_01 onclick='CredentialRegistrationApplication.handleFormSubmit(); return false;'}
                <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
            </td>
        </tr>
    </table>
{/form}
{else}
    <div style="text-align:center; padding-top:30px; padding-bottom:30px;">
        {t}Thanks for registering. We will contact you soon with further details.{/t} 
        <br /><br /><br /><br />
		{t var='button_02'}Close Window{/t}
        {linkbutton name=$button_02 onclick="popup_window.close(); return false;"}
    </div>
{/if}