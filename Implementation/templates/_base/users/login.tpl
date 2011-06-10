{literal}
	<script type="text/javascript">//<![CDATA[ 
	    $(function(){ $('#zaysLogin').focus(); })
	//]]></script>
{/literal}
{if $FACEBOOK_USED}
{literal}
    <script type="text/javascript">//<![CDATA[ 
		FBCfg.url_do_signup = '{/literal}{$BASE_URL}/{$LOCALE}/facebook/processregistration/{literal}';
		FBCfg.url_onlogin_ready = '{/literal}{$BASE_URL}/{$LOCALE}/facebook/processlogin/{literal}';
    //]]></script>
{/literal}
{/if}
<div class="prLoginContent">			
	{form from=$form class="" id="loginForm"}
	{capture name="form_error"}{form_errors_summary}{/capture}
	{if $smarty.capture.form_error}{$smarty.capture.form_error}{/if}
		{if $FACEBOOK_USED}
		<div class="prLoginFacebookBlock prClr">
			<div class="prFaceBookLeft prFloatLeft">
				<div>
					<label for="zaysLogin">{t}Username:{/t}</label><br />
					{form_text id="zaysLogin" name="login" tabindex="1" value=$login|escape:"html"}
				</div>
				<div class="prIndentTop">
					<label for="zaysPassword">{t}Password:{/t}</label><br />
					{form_password name="password" tabindex="2" id="zaysPassword"}
				</div>
				<div class="prIndentTop">
					{form_checkbox id="Rememberme" name="rememberme" tabindex="3" checked = $isChecked} <label class="prTNormal" for="Rememberme">{t}Remember me on this computer{/t}</label>
				</div>
				<div class="prLoginButBlock">
					{t var='button_01'}Sign In{/t}
					{form_submit name="form_login" tabindex="4" value=$button_01}
				</div>
				<div class="prIndentTop">
					<a href="/{$LOCALE}/users/restore/">{t}Forgot your username or password?{/t}</a>
				</div>
				<div class="prIndentTop">
					<a href="{$BASE_URL}/{$LOCALE}/registration/index/">{t}Create an account{/t}</a>
				</div>
			</div>
			<div class="prFaceBookRight prFloatRight prIndentTop">
				<div class="prIndentTop">
					<fb:login-button onlogin="FBApplication.onlogin_ready();"  size="medium" length="long"></fb:login-button>
				</div>
				<div class="prIndentTop">
					<a href="javascript:void(0);" onclick="FBApplication.do_signup();">{t}Sign Up with your Facebook account{/t}</a>
				</div>
			</div>			
						
		</div>
		{else}
		<div class="prLoginContentBlock">
			<div class="prIndentTop">
				<label for="zaysLogin">{t}Username:{/t}</label><br />
				{form_text id="zaysLogin" name="login" tabindex="1" value=$login|escape:"html"}
			</div>
			<div class="prIndentTop">
				<label for="zaysPassword">{t}Password:{/t}</label><br />
				{form_password name="password" tabindex="2" id="zaysPassword"}
			</div>
			<div class="prIndentTop">
				{form_checkbox id="Rememberme" name="rememberme" tabindex="3" checked = $isChecked} <label class="prTNormal" for="Rememberme">{t}Remember me on this computer{/t}</label>
			</div>
			<div class="prLoginButBlock">
				{t var='button_01'}Sign In{/t}
				{form_submit name="form_login" tabindex="4" value=$button_01}
			</div>
			<div class="prIndentTop">
				<a href="/{$LOCALE}/users/restore/">{t}Forgot your username or password?{/t}</a>
			</div>
			<div class="prIndentTop">
				<a href="{$BASE_URL}/{$LOCALE}/registration/index/">{t}Create an account{/t}</a>
			</div>
		</div>	
		{/if}
	{/form}	
	
{*
 * Implementation of OpenID form
 * @author Artem Sukharev
 * DON'T REMOVE THIS BLOCK, it will be used later
<div class="prLoginFacebookBlock">
	<!--  -->                               
	{form from=$formOpenID class="" id="loginOpenIDForm"}
		<div class="prIndentTop">
		    <label for="zaysLogin">{t}OpenID Login:{/t}</label><br />
		    {form_text id="zaysLogin" name="openid_identifier" value=""}
		</div>
		<div class="prLoginButBlock">
		    {t var='button_01'}Sign In with OpenID{/t}
		    {form_submit name="openid_action" tabindex="4" value=$button_01}
		</div>
	{/form}
	<!--  -->
</div>
*}
</div>
