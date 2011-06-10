{if $incorrectUrl}
	<h3>{t}Invalid Link{/t}</h3>
	{t}The link that you followed was invalid or incomplete. Try copying and pasting the full link into the address bar. If you're stuck, click here {/t}<a href="{$BASE_URL}/{$LOCALE}/info/support/">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}%s/%s/info/support/{/t}</a>.
{elseif $urlExpired}
	<h3>{t}Link was Expired{/t}</h3>
	<div>
        {t}{tparam value=$BASE_URL}{tparam value=$LOCALE}Sorry, your password reset link was expired. Try to request a new one <a href="%s/%s/users/restore/">here</a>.{/t}
	</div>
{else}
	<div class="prBlockCentered prMiddleFormItem"><h3>{t}Change Password{/t}</h3>
	{form from=$restorePasswordForm}
        <div>{form_errors_summary}</div>
        <label for="pass">{t}Password:{/t}</label>
        <div class="prIndentBottom">{form_password id="pass" name="pass" value=$pass class="prLargeFormItem"}</div>
        <label for="pass_confirm">{t}Confirm Password:{/t}</label>
        <div class="prIndentBottom">{form_password id="pass_confirm" name="pass_confirm" value=$pass_confirm class="prLargeFormItem"}</div>
        {t var='button'}Change Password{/t}
        {form_submit name="change_password" value=$button}
	{/form}
    </div>
{/if}

