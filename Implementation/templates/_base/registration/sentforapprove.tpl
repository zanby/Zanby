{if $fromRegistration}
	<h2>{t}{tparam value=$SITE_NAME}Welcome to %s{/t}</h2> 
	<h3 class="prInnerTop">{$userData.login|escape:html}!</h3>
	<span>{t}The registration details have been sent for approval.
	Your registration will be processed within 72 hours of submission due to individual review of each application.{/t}</span>
	{else}
	<span>{t}Thank you. We are processing your request.{/t}</span>
{/if}