{*popup_item*}
<div>
	{if $mode == 'user'}
	<div class="prInnerTop" id="fbConnectButtonPlaceholder">
		<h2>{t}E-mail you entered already registered.{/t}</h2>
	</div>
	{else}
	<div class="prInnerTop" id="fbConnectButtonPlaceholder">
		<h2>{t}Facebook user is already linked with an existing account in our community{/t}</h2>
	</div>
	{/if}

	<div class="prInnerTop">
		{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}{tparam value=$BASE_URL}{tparam value=$LOCALE}
		Please <a href="%s/%s/users/login/">Sign In</a> or, if you forgot your password, <a href="%s/%s/users/restore/">reset it</a> now.{/t}
	</div>
</div>

{*popup_item*}