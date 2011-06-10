{*popup_item*}
{if $FACEBOOK_USED}
{literal}
    <script type="text/javascript">
        {/literal}{assign_adv var="url_onrsvplogin_ready" value="array('controller' => 'facebook', 'action' => 'processrsvplogin')"}{literal}
        FBCfg.url_onrsvplogin_ready = '{/literal}{$Warecorp->getCrossDomainUrl($url_onrsvplogin_ready)}{literal}';
        $(function(){
            FB.XFBML.Host.parseDomElement(document.getElementById('fbConnectButtonPlaceholder'));
        })
    </script>
{/literal}
{/if}
<div>
    {form from=$form id="rsvp_event_form" onsubmit="xajax_doAttendeeEventSignup(`$event_id`, `$uid`, '`$view`', xajax.getFormValues('rsvp_event_form')); return false;"}

    <div class="prInnerTop prClr3 prBorderBottom prInnerBottom" id="fbConnectButtonPlaceholder">
        <div class="prFloatLeft">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}To RSVP you need to <a href="%s/%s/users/login/">Sign In</a>{/t} {if $FACEBOOK_USED} {t}Or{/t} </div><div class="prIndentLeftSmall prFloatLeft"><fb:login-button onlogin="FBApplication.onrsvplogin_ready();"  size="medium" length="long"></fb:login-button></div>{else}</div>{/if}
    </div>

    <div class="prInnerTopLarge">
        {t}If you do not have an account, you may RSVP as an <span class="prTBold">anonymous</span> user. Please, fill the form below:{/t}
    </div>

    <div class="prInnerTop">
        {t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}
    </div>

    {form_errors_summary}
    <table class="prForm">
        <col width="25%" />
        <col width="50%" />
        <col width="25%" />
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="email">{t}E-Mail{/t} : </label></td>
            <td>{form_text name="email" id="email" value=$handle.email}</td>
            <td>&#160;</td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="firstName">{t}First Name{/t} : </label></td>
            <td>{form_text name="firstName" id="firstName" value=$handle.firstName}</td>
            <td>&#160;</td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="lastName">{t}Last Name{/t} : </label></td>
            <td>{form_text name="lastName" id="lastName" value=$handle.lastName}</td>
            <td>&#160;</td>
        </tr>
    </table>
    <div class="prInnerTop">
        {form_checkbox name="register" id="register" value="1" checked=$handle.register}<label for="register">{t}{tparam value=$SITE_NAME_AS_STRING}I want to create an account on %s{/t}</label>
    </div>

    <div class="prTCenter prIndentTop">
        {t var='button'}Confirm{/t}
        {linkbutton name=$button  onclick="xajax_doAttendeeEventSignup(`$event_id`, `$uid`, '`$view`', xajax.getFormValues('rsvp_event_form')); return false;"}
        <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
    </div>
    {/form}
</div>
{*popup_item*}
