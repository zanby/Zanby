<script src="/js/discussion/settings.user.js"></script>	
<!-- tabs area begin -->
	<!-- START Sub Menu -->
	{if IS_GLOBAL_GROUP}
		<h2>{t}{tparam value=$currentGroup->getName()}Discussions on %s{/t}</h2>
	{/if}	
    <!-- END Sub Menu -->	
{form from=$form}
<!-- toggle section begin -->
{if $GroupDiscussionStyle == 1}
	{TitlePane id='DigestSubscriptionsContent' showContent=$ContentOpen.DigestSubscriptionsContent}
		{TitlePane_Title}{t}Email Settings{/t}{/TitlePane_Title}	
        {TitlePane_Content}			
				{form_radio name="digest_type" id="digest_type_1" value="1" checked=$subscription->getSubscriptionMode() onclick="turnAllSubscriptionChecked(this);"}<label for="digest_type_1"> {t}Turn off all subscriptions{/t}</label>
				<div class="prIndentTopSmall">				
				{form_radio name="digest_type" id="digest_type_2" value="2" checked=$subscription->getSubscriptionMode()  onclick="allowAllSubscriptionChecked(this);"}<label for="digest_type_2" > {t}Subscribe to all content on the discussion boards{/t}</label>
				</div>
				<div class="prInner">				
					<label class="prInnerSmallLeft">{t}Send as:{/t}</label>
					{if $subscription->getSubscriptionMode() == 1 || $subscription->getSubscriptionMode() == 3}
						{form_select name="digest_type_value_all" id="digest_type_value_all" options=$subscribeContentOptions selected=$subscription->getSubscriptionType() disabled='disabled'} 
					{else}
						{form_select name="digest_type_value_all" id="digest_type_value_all" options=$subscribeContentOptions selected=$subscription->getSubscriptionType()} 
					{/if}
				</div>
							
				{form_radio name="digest_type" id="digest_type_3" value="3" checked=$subscription->getSubscriptionMode() onclick="allowCustomSubscriptionChecked(this);"}<label for="digest_type_3"> {t}Only subscribe to specific discussions{* and topics*}:{/t}</label>					
			
			
			<div class="prInnerLeft prClr3">
				<div class="prInnerTop prInnerBottom">			
					<h3>{t}Discussions:{/t}</h3>				
					<div class="prTip">
						{t}Subscribing to a discussion will subscribe you to all existing and new topics in that discussion.{/t}
					</div>
				</div>				
				<table id="DiscussionsSubscriptionBlock" class="prForm">
					<col width="65%" />					
					<col width="35%" />
					{foreach from=$discussions item=discussion}
					<tr>
						<td>{$discussion->getTitle()|escape:"html"} <span class="prText4">({$discussion->getFullEmail()|escape:"html"})</span></td>
						<td>
							{if $subscription->getSubscriptionMode() == 1 || $subscription->getSubscriptionMode() == 2}
							{form_select name="digest_type_value_discussions["|cat:$discussion->getId()|cat:"]" options=$subscribeContentOptionsWithPause selected=$discussion->subscription->getSubscriptionType() disabled='disabled'}
							{else}
							{form_select name="digest_type_value_discussions["|cat:$discussion->getId()|cat:"]" options=$subscribeContentOptionsWithPause selected=$discussion->subscription->getSubscriptionType()}
							{/if}
							{form_hidden name="digest_type_value_discussions_hidden["|cat:$discussion->getId()|cat:"]" value=0 }
						</td>
					</tr>
					{/foreach}
				</table>				
				{if $topicSucriptions}
				<div class="prInnerTop prInnerBottom">
					<h3>{t}Topics:{/t}</h3>
					<div class="prTip">
						{t}To subscribe to a topic, go to that topic and click notify.{/t}
					</div>
				</div>				
				<table id="TopicsSubscriptionBlock" class="prForm">
					{foreach from=$topicSucriptions item=ts}
					<tr id="TopicSubscriptionTR{$ts->getId()}">
						<td>{$ts->getTopic()->getSubject()|escape:"html"}<br /><span>{t}{tparam value=$ts->getTopic()->getDiscussion()->getTitle()|escape:"html"}Discussion: %s{/t}</span></td>
						<td>
							{if $subscription->getSubscriptionMode() == 1 || $subscription->getSubscriptionMode() == 2}
							{form_select name="digest_type_value_topics["|cat:$ts->getId()|cat:"]" options=$subscribeContentOptionsWithPause selected=$ts->getSubscriptionType() disabled='disabled'}
							{else}
							{form_select name="digest_type_value_topics["|cat:$ts->getId()|cat:"]" options=$subscribeContentOptionsWithPause selected=$ts->getSubscriptionType()}
							{/if}
							{form_hidden name="digest_type_value_topics_hidden["|cat:$ts->getId()|cat:"]" value=0}
							
							<a href="#null" onclick="xajax_delete_topic_subscription({$ts->getId()}); return false;" class="prInnerLeft"><img src="{$AppTheme->images}/decorators/profile-marker.gif" border="0" alt="" align="top"  /></a>
							</td>
					</tr>
					{/foreach}
				</table>				
				{/if}
				 
				<div class="prTRight prInnerTop  prInnerSmallBottom">{t var="in_submit_1"}Save{/t}{form_submit name="Save" value=$in_submit_1}</div>				
			</div>			
				
		{/TitlePane_Content}
	{/TitlePane}
<!-- toggle section end -->
{/if}
{/form}
		
<!-- toggle section begin -->
{TitlePane id='PermissionsContent' showContent=$ContentOpen.PermissionsContent}
	{TitlePane_Title}{t}Permissions{/t}{/TitlePane_Title}	
        {TitlePane_Content}		
			<div class="prIndentLeftLarge">		
				<ul class="prUnorderedList">
					{if $CurrentGroup->getDiscussionAccessManager()->canPostMessages($CurrentGroup, $user->getId())}<li>{t}You can post messages.{/t}{else}<li>{t}You can't post messages.{/t}{/if}</li>
					{if $CurrentGroup->getDiscussionAccessManager()->canReplyMessages($CurrentGroup, $user->getId())}<li>{t}You can reply to messages.{/t}{else}<li>{t}You can't reply to messages.{/t}{/if}</li>
					{if $CurrentGroup->getDiscussionAccessManager()->canEditOwnMessages($CurrentGroup, $user->getId())}<li>{t}You can edit your messages.{/t}{else}<li>{t}You can't edit your messages.{/t}{/if}</li>
					{if $CurrentGroup->getDiscussionAccessManager()->canDeleteOwnMessages($CurrentGroup, $user->getId())}<li>{t}You can delete your messages.{/t}{else}<li>{t}You can't delete your messages.{/t}{/if}</li>
				</ul>
			</div>	
		{/TitlePane_Content}
	{/TitlePane}	                 
<!-- toggle section end -->

<!-- toggle section begin -->
{TitlePane id='ModeratorsContent' showContent=$ContentOpen.ModeratorsContent}
	{TitlePane_Title}{t}Moderators{/t}{/TitlePane_Title}	
    {TitlePane_Content}			
		{strip}				 
				{if $CurrentGroup->getDiscussionGroupHost()}
					<div>{t}Host:{/t} <a href="{$CurrentGroup->getdiscussionGroupHost()->getModeratorHomePageLink()}">{$CurrentGroup->getdiscussionGroupHost()->getLogin()|escape:html}</a></div>				
				{/if}				
				{if $moderators}
				<div>{t}Moderators:{/t}</div>				
				<div>
					{foreach name=mod from=$moderators item=m}
					<a href="{$m->getModeratorHomePageLink()}">{$m->getModeratorName()|escape:html}</a>{if !$smarty.foreach.mod.last}, {/if}
					{/foreach}
				</div> 				
				{/if}						

		{/strip}			
	{/TitlePane_Content}
{/TitlePane}	   
<!-- toggle section end -->

<!-- toggle section begin -->
{if $CurrentGroup->getGroupType() neq 'family'}
{TitlePane id='GroupFamiliesContent' showContent=$ContentOpen.GroupFamiliesContent}
	{TitlePane_Title}{t}Group Families{/t}{/TitlePane_Title}	
    {TitlePane_Content}			
		<div class="prInnerTop">			
			<p class="prDefaultText prInnerBottom">
				{if $familyGroups}
				{foreach name=families from=$familyGroups item=gr}
					{$gr->getName()|escape:html} <a href="{$gr->getGroupPath('discussionsettings')}">{t}[digest settings]{/t}</a>{if !$smarty.foreach.families.last}<br />{/if}
				{/foreach}
				{/if}
			</p>
		</div>			
	{/TitlePane_Content}
{/TitlePane}
{/if}
<!-- toggle section end -->	
