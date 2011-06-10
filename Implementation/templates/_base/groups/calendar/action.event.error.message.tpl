<div class="prInner">
	{if $errorMessage}
		{$errorMessage|escape:html}
	{/if}
	{if $backToEvent}
		<div class="prInnerTop"><a href="{$currentGroup->getGroupPath('calendar.event.view')}id/{$eventId}/uid/{$eventId}/">{t}Back to Event{/t}</a></div>
	{/if}  
		<div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
		<div class="prInnerTop">
			{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}  
		</div> 
</div>
