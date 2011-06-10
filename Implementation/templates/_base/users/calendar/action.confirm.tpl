{if $currentUser->getId() == $user->getId()}{assign var="title" value="My Events"}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}
{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}     


{* PAGE CONTENT START *}

{if $confirmMode != 'ERROR'}
	<div class="prTCenter">
   			    {if $confirmMode == 'EDIT'}
				<div class="prInnerTop">
					&quot; <a href="{$objEvent->entityURL()}">
						{$objEvent->getTitle()|escape:html}
					</a> &quot; {t}has been changed.{/t}
				</div>
                
                <div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
				
                <div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>
                
               	<div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
				<div class="prInnerTop">
                	{t}If you have a special question or suggestion, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a>
				</div>	
            {elseif $confirmMode == 'CREATE'}
               
			   <div class="prInnerTop">
					&quot; <a href="{$objEvent->entityURL()}">
						{$objEvent->getTitle()|escape:html}
					</a> &quot; {t}has been added to your calendars.{/t} 
				</div>
              
				<div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
				
                <div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>
                
				<div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
					
				<div class="prInnerTop">
					{t}If you have a special question or suggestion, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a>
				</div>
            {/if}
			
  	</div>
    
{else}
    <div class="prTCenter">
   	
     
            	<div class="prInnerTop">
					{if $confirmMessage}{$confirmMessage|escape:html}
						{else}{t}Sorry, event not found.{/t}
					{/if}
                </div>

                <div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
				
                <div class="prInnerTop"><a href="{$currentUser->getUserPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>
                
                <div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
				
                <div class="prInnerTop">{t}If you have a special question or suggestion, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a></div>
             
    </div>
    
{/if}
{if empty($denyAutoRedirect)}
	{literal}
	<script type="text/javascript">
		initRedirect = function () {setTimeout(doRedirect, 7000);};
		doRedirect = function () {document.location.href = {/literal}"{$confirmRedirectURL}"{literal};};
		YAHOO.util.Event.onDOMReady(initRedirect);
	</script>
	{/literal}
{/if}
