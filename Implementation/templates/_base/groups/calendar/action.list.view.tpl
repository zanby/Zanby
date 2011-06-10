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

<div class="prInner prClr2">
	<!-- left -->         
	<div class="prEventList-left">
		<!-- group event list -->
		{if $arrEvents}
                <div class="prEGLandSearchPaging prClr3">
                <div class="prIndentBottomSmall prIndentTop prFloatRight prPaginatorRight">
                {$paging}     
                </div>
                </div>
                {foreach from=$arrEvents item='objEvent' name='events_i'}      
				{view_factory 
					entity='event' 
					view='listView' 
					object=$objEvent 
					Warecorp_ICal_AccessManager=$Warecorp_ICal_AccessManager 
					currentOwner=$CurrentGroup 
					user=$user 
					arrEventsLinks=$arrEventsLinks 
					viewMode=$viewMode 
					currentTimezone=$currentTimezone 
					last=$smarty.foreach.events_i.last 
					AppTheme=$AppTheme}
				{/foreach}

                <div class="prEGLandSearchPaging">
                <div class="prIndentBottomSmall prIndentTop prFloatRight prPaginatorRight">
                {$paging}     
                </div>
                </div>
		
		{else}
				<div class="">{t}No Events{/t}</div>
		{/if}
		<!-- /group event list -->
		{* MAIN GROUP EVENTS BLOCK END *}
	</div>
	<!-- left -->

	<!-- right -->
	<div class="prEventList-right">
		<h3>{t}All events tags:{/t}</h3>
		{foreach from=$lstTags->getAllList() item=t}
				<a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$t->name}/">({$t->currentCnt}) {$t->name|escape:html}</a><br />
		{foreachelse}
			{t}No Tags{/t}
		{/foreach}
	</div>
	<!-- right -->
</div>


<div id="TopicTooltipContent" class="TooltipContent" style="position:absolute; display:none; width: 400px; padding: 5px; font-size:12px" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>
