{if $FACEBOOK_USED && !$_RSVP_ && !$user->getId()}
	{literal}
	<script type="text/javascript">
		{/literal}{assign_adv var="url_oncheck_rsvp_status_ready" value="array('controller' => 'facebook', 'action' => 'checkrsvpstatus')"}{literal}
		FBCfg.url_oncheck_rsvp_status_ready = '{/literal}{$Warecorp->getCrossDomainUrl($url_oncheck_rsvp_status_ready)}{literal}';
		$(function(){ 
			FBApplication.check_rsvp_status(0, 0); 
		})
	</script>
	{/literal}
{/if}

{if $currentUser->getId() == $user->getId()}{t var="title"}My Events{/t}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}


{if $currentUser->getId() != $user->getId()}
<div class="prInnerBottom"></div>
{/if}

{* PAGE CONTENT START *}
<!-- left -->
<div class="prEventList-left">
	<!-- group event list -->
	{if $arrEvents}
	{foreach from=$arrEvents item='objEvent' name='events_i'}
	{view_factory 
		entity='event' 
		view='listView' 
		object=$objEvent 
		Warecorp_ICal_AccessManager=$Warecorp_ICal_AccessManager 
		currentOwner=$currentUser 
		user=$user 
		arrEventsLinks=$arrEventsLinks 
		viewMode=$viewMode 
		currentTimezone=$currentTimezone 
		last=$smarty.foreach.events_i.last 
		AppTheme=$AppTheme}
	{/foreach}
	
	{else}
	<div class="prNoEvents">{t}No Events{/t}</div>
	{/if}
	<!-- /group event list -->
</div>
<!-- left -->
<!-- right -->
<div class="prEventList-right">
	<h3>{t}All events tags:{/t}</h3>
	{foreach from=$lstTags->getAllList() item=t}
	  <a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$t->name}/">({$t->currentCnt}) {$t->name|escape:html}</a><br />
	{foreachelse}
	{t}No Tags{/t}
	{/foreach} </div>
<!-- right -->
{* PAGE CONTENT END *} 