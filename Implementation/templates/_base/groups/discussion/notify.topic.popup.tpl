{*popup_item*}
<select name="subscription" id="subscription">
	<option value="-1">{t}Don't subscribe to this topic{/t}</option>
	{foreach from=$subscribeContentOptions key=optkey item=opt}
	<option value="{$optkey}"{if $subscription && $subscription->getSubscriptionType() == $optkey} {t}selected{/t}{/if}>{$opt}</option>
	{/foreach}
</select>
<!-- popup -->
<div class="prInnerTop prTCenter">
	{t var="in_button"}Save{/t}
	{linkbutton color="blue" name=$in_button  onclick="notify_topic_do({$topic->getId()}); return false;"}	
	 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>	
</div>
<!-- /popup -->
{*popup_item*}