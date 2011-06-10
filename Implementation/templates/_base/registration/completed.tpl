{if !$mode}
	{if $fromRegistration}
		<h2>{t}{tparam value=$SITE_NAME}Welcome to %s{/t}</h2> 
		<h3 class="prInnerTop">{$userData.login|escape:html}!</h3>
	{else}
		<span>{t}Thank you. We are processing your request.{/t}</span>
	{/if}
	<div class="prInnerTop">{t}{tparam value=$userData.email|escape:html}A confirmation message has been sent to %s.{/t}</div>
	<div class="prInnerTop">{t}Please read the email and follow the instructions to fully activate your account.{/t}</div>
	<div class="prInnerBottom">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you don't receive the message in the next 5-10 minutes, you can <a href="%s/%s/registration/confirm/">request another verification email</a>.{/t}</div>
{elseif $mode == 'facebook'}
	<h2>{t}{tparam value=$SITE_NAME}{tparam value=$userData.user->getFirstName()|escape}{tparam value=$userData.user->getLastName()|escape}You are successfully logged in on %s, %s %s!{/t}</h2>
	<div>{t}{tparam value=$userData.email|escape}We just sent your login/password to <span class="prTBold">%s</span>.{/t}</div>
	<div class="prInnerBottom">{t}{tparam value=$SITE_NAME}You may use your facebook account in order to Sign In on %s in the future too!{/t}</div>
	
	<h2><img class="prIndentRight" src="{$AppTheme->images}/decorators/icons/settingFB.gif" alt='' title='' /><a href="{$user->getUserPath('settings')}">{t}Update Your account settings{/t}</a></h2>
	<div class="prInnerBottom">{t}Allows to change your Username, Password, Email, etc.{/t}</div>
	
	<h2><img class="prIndentRight" src='{$user->getAvatar()->setWidth(16)->setHeight(16)->getImage()}' alt='' title='' width='16' height='16'/><a href="{$user->getUserPath('profile')}">{t}Update Your Profile{/t}</a></h2>
	<div class="prInnerBottom">{t}Organize your page, upload photos, videos, etc.{/t}</div>
	
	<h2><img class="prIndentRight" src="{$AppTheme->images}/decorators/icons/manageFB.gif" alt='' title='' /><a href="{$user->getUserPath('networks')}">{t}Manage Your Facebook sharing permissions{/t}</a></h2>
	{t}Sharing permissions management of feeds, events, etc. to your Facebook web pages.{/t}
{/if}
