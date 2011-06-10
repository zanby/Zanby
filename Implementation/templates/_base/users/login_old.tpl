
{contentblock width="100%"}
<table align="center" border=0>
    <tr>
        <td style="padding-top:20px;padding-bottom:10px;">
            <img src="{$AppTheme->images}/decorators/login_string.jpg" />
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:5px;">
            {contentblock}
			{$login_message}
            <table width="100%" align="center">
            	<tr>
            		<td width=50% style="border-right:solid 1px #000;padding-right:20px;">
            			<form {$loginFormData.attributes}>
            			{$loginFormData.hidden}
            				<table width=100%>
            				    {if $loginFormData.errors}
           					    <tr>
            						<td colspan="2" style="color:#FF0000;">
                                        {foreach item=e from=$loginFormData.errors}{$e}<br>{/foreach}
            						</td>
            					</tr>
            					{/if}
            					<tr>
            						<td>{$loginFormData.login.label}</td>
            						<td>{$loginFormData.login.html}</td>
            					</tr>
            					<tr>
            						<td>{$loginFormData.password.label}</td>
            						<td>{$loginFormData.password.html}</td>
            					</tr>
            					<tr>
            						<td colspan=2 align="right"><a href="/{$LOCALE}/users/restore/">{t}Forgot Password?{/t}</a><br/><br/>
                						<table width=100%>
                							<tr>
                								<td><input type="checkbox" name="rememberme" value="1" checked>
                								<td style="font-size:9px;">{t}Remember me on this computer{/t}
                								<td>{$loginFormData.submit.html}</td>
                							</tr>
                						</table>
            						</td>
            					</tr>
            				</table>
            			</form>
            		</td>
            		<td width=40% style="padding:0px 20px;">
            			{t}{tparam value=$SITE_NAME_AS_STRING}If you are not member of %s please <a href="/{$LOCALE}/registration/index/">register</a> by clicking the button below.{/t}
            			<br /><br /><div align="center">
						{t var='button_01'}Register{/t}
						{linkbutton name=$button_01 link="/`$LOCALE`/registration/index/" color="orange"}</div>
            		</td>
            	</tr>
            </table>
            {/contentblock}
        </td>
    </tr>
    <tr>
        <td align="center" style="padding-top:20px;padding-bottom:30px;">
            <div class="anonbottomlinks">
                <span><a href="/{$LOCALE}/" alt="Home" title="Home">{t}Home</a></span> |
                <span><a href="/{$LOCALE}/registration/index/" alt="Register" title="Register">{t}Register{/t}</a></span> |
                <span><a href="/{$LOCALE}/info/contactus/" alt="Contact Us" title="Contact Us">{t}Contact Us{/t}</a></span> |
                <span><a href="/{$LOCALE}/info/about/" alt="About Us" title="About Us">{t}About Us{/t}</a></span> |
                <span><a href="/{$LOCALE}/info/privacy/" alt="Privacy Policy" title="Privacy Policy">{t}Privacy Policy{/t}</a></span> |
                <span><a href="/{$LOCALE}/info/feedback/" alt="Feedback" title="Feedback">{t}Feedback{/t}</a></span> |
                <span><a href="http://zanby.blogspot.com/" alt="Blog" title="Blog">{t}Blog{/t}</a></span> |
                <span><a href="/{$LOCALE}/users/login" alt="Log In" title="Log In">{t}Log In{/t}</a></span>
             </div>
        </td>
    </tr>
</table>
{/contentblock}
