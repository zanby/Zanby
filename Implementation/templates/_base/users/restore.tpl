<h2>{t}Forgot Your Password?{/t}</h2>
	<p>{t}{tparam value=$SITE_NAME_AS_STRING}Please enter the Email address you used to create your %s account<br /> or Username and we will send your password.{/t}</p>
<div class="prMiddleFormItem">
	<div class="prLargeFormItem">
		{form from=$form_username}
		{form_errors_summary space_after=10}
		<div class="prIndentTop">
			<label>{t}Email address or Username:{/t}</label>
			<div class="prInnerSmallTop">{form_text name="username_or_email" class="prLargeFormItem" value=$data.username|escape:"html"}</div>
			<div class="prIndentTop">
			{t var='button'}Retrieve Your Password{/t}
			{form_submit name="form_getpassword" value=$button}
			</div>
		</div>
		{/form}
	</div>
</div>