<div class="prClr2">
	<div class="prEventList-left"> {tab template="tabs1" active=member}
		{tabitem link=$CurrentGroup->getGroupPath('calendar.list.view') name="family" first="first"}{t}Family-Wide Events{/t}{/tabitem}
		{tabitem link=$CurrentGroup->getGroupPath('calendar.hierarchy.view') name="member" last="last"}{t}Member Group Events{/t}{/tabitem}
		{/tab}
		<div class="freeClass">
			<!-- breadcrumb -->
			<div class="prInnerTop">
				<ul class="prClr2">
					<li class="prFloatLeft prIndentLeftSmall"><a href="{$CurrentGroup->getGroupPath('calendar.hierarchy.view')}">{t}Member Group Events{/t}</a></li>
					{foreach from=$arrPath item='path' name='path_i'}
					{if $smarty.foreach.path_i.last}
					<li class="prFloatLeft prIndentLeftSmall">{$path.name|longwords:40|escape}</li>
					{else}
					<li class="prFloatLeft prIndentLeftSmall"><a href="{$path.url}">{$path.name|longwords:40|escape}</a></li>
					{/if}
					{/foreach}
				</ul>
			</div>
			<!-- /breadcrumb -->
			<h2> {if $showSectionName}{$sectionName|escape}{/if}</h2>
			{foreach from=$lstGroupEvents item='groupInfo'}
			<!-- group event list -->
			<div class="prDropBox">
				<div class="prDropBoxInner">
					<div class="prDropHeader">
						<h2>{$groupInfo.objGroup->getName()|escape:html}</h2>
					</div>
					<div> {foreach from=$groupInfo.lstEvents item='objEvent' name='events_i'}
						<!-- group event list slot -->
						<div class="prEventListBlock {if $smarty.foreach.events_i.last}prNoBorder{/if}">
							<div class="prEventDate">
								<div class=""> {if $objEvent->getRrule() !== null}<img src='{$AppTheme->images}/decorators/event/repeat.gif'>{/if} </div>
								
								{$objEvent->displayDate('list.view.family.members', $user, $currentTimezone)} </div>
							<h4><a href="{$objEvent->entityURL()}">{$objEvent->getTitle()|escape:html}</a></h4>
							<div class="prEventList-detailTop">
								<div class="prEventList-detail"> {if $objEvent->getPictureId()}<img class="image_thumb" border=1  src="{$objEvent->getEventPicture()->setWidth(37)->setHeight(38)->getImage($user)}">{/if}
									
									{$objEvent->getDescription()}&nbsp;
									
									{if $user->getId()}
									{assign var='userAttendee' value=$objEvent->getAttendee()->findAttendee($user)}
									{if null !== $userAttendee && $userAttendee->getAnswer() == 'NONE'} <span class="prText5">{t}Waiting for a response...{/t}</span> {/if}
									{/if} </div>
								<div class="prEventList-rsvp">
									{if $viewMode == 'active'}
									{if $user->getId()}
									{if null !== $userAttendee}
									{if $userAttendee->getAnswer() == 'NONE'} <a href="#null" onclick="xajax_doAttendeeEvent({$objEvent->getId()}, {$objEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a> {elseif $userAttendee->getAnswer() == 'YES'} <a href="#null" onclick="xajax_doAttendeeEvent({$objEvent->getId()}, {$objEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnAttending.gif" /></a> {elseif $userAttendee->getAnswer() == 'NO'} <a href="#null" onclick="xajax_doAttendeeEvent({$objEvent->getId()}, {$objEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnNotAttending.gif" /></a> {elseif $userAttendee->getAnswer() == 'MAYBE'} <a href="#null" onclick="xajax_doAttendeeEvent({$objEvent->getId()}, {$objEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnMaybe.gif" /></a> {/if} <a href="#" onclick="xajax_viewAttendeeEvent({$objEvent->getId()}, {$objEvent->getUid()}); return false;"> <br />
									{t}View Attendance Details{/t} </a> {/if}
									{/if}
									{/if} </div>
							</div>
							<div class="prEventList-detailBottom">
								{if null !== $user->getId() && $user->getId() == $objEvent->getCreatorId()} <span class="prText5">{t}Organizer :{/t}</span> {t}You are organizer{/t}
								{else} <span class="prText5">{t}Organizer :{/t}</span> <a href="{$objEvent->getCreator()->getUserPath('profile')}">{$objEvent->getCreator()->getLogin()|escape:"html"}</a> {/if} <br />
								<span class="prText5">{t}Event Category:{/t} </span> 
								{foreach from=$objEvent->getCategories()->setFetchMode('object')->getList() item='category' name='event_cats'}
								{$category->getCategory()->getName()|escape:html}{if !$smarty.foreach.event_cats.last}, {/if}
								{/foreach} <br />
								{if $objEvent->getAttendee()->findAttendee($user) && $objEvent->getCreator()->getId()!==$user->getId()} <a href="#" onclick="xajax_doEventRemoveMe({$objEvent->getId()}, {$objEvent->getUid()}); return false;"> {t}Remove Me from the Guest List{/t} </a> {/if} </div>
						</div>
						<!-- /group event list slot -->
						{foreachelse}
						<div class="prText2 prTCenter">{t}No Events{/t}</div>
						{/foreach} </div>
				</div>
			</div>
			<!-- /group event list -->
			{foreachelse}
			<div>{t}No Events{/t}</div>
			{/foreach} </div>
	</div>
	<!-- right -->
	<div class="prEventList-right">
		<h3>{t}All events tags:{/t}</h3>
		{foreach from=$lstTags->getAllList() item=t} <a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$t->name}/">({$t->currentCnt}) {$t->name|escape:html}</a><br />
		{foreachelse}
		{t}No Tags{/t}
		{/foreach} </div>
	<!-- right -->
	{* PAGE CONTENT END *} </div>
<div id="TopicTooltipContent" style="position:absolute; display:none;" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>
