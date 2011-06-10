{if $currentUser->getId() == $user->getId()}{assign var="title" value="My Events"}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}     



{* PAGE CONTENT START *}
        	<div class="prTCenter">
					<div class="prInner">
						{if $errorMessage}
							{$errorMessage|escape:html}
						{/if}
					</div><br>    
                    {if $backToEvent}
                        <h2 class="prInner"><a href="{$currentGroup->getGroupPath('calendar.event.view')}id/{$eventId}/uid/{$eventId}/">{t}Back to Event{/t}</a></h2><br />
                    {/if}  
					<h2 class="prInner"><strong><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></strong></h2>
					<div class="prInner">
						{t}ss If you have a special question or suggestion, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a>  
					</div> 
			</div>	


{* PAGE CONTENT END *}
