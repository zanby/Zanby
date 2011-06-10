<div class="prInner">	

    {* PAGE CONTENT START *}
        
    {if $confirmMode != 'ERROR'}
	    <div class="prTCenter">
		    {if $confirmMode == 'EDIT'}
                &quot;
                <a href="{$objEvent->entityURL()}">
                    {$objEvent->getTitle()|escape:html}</a>
                &quot; {t}has been changed.{/t}
                <br /><br />

                {if $objHostDocument}
                    <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('docget')}docid/{$objHostDocument->getId()}/">{t}Download Host Documents{/t}</a> | {$objHostDocument->getFileSize()}</div>
                {/if}
                            
                <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
                <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>
                            
                <div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
                {t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}
            {elseif $confirmMode == 'CREATE'}
                &quot;
                <a href="{$objEvent->entityURL()}">
                    {$objEvent->getTitle()|escape:html}</a>
                &quot; {t}has been added to your calendars.{/t} 
                <br /><br />

                {if $objHostDocument}
                    <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('docget')}docid/{$objHostDocument->getId()}/">{t}Download Host Documents{/t}</a> | {$objHostDocument->getFileSize()}</div>
                {/if}


                <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
                <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>
                            
                <div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
                <div class="prInnerTop">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}</div>
            {/if}
                     
        </div>
    {else}
        <div class="prTCenter">
   			<div class="prInnerTop">
				{if $confirmMessage}{$confirmMessage|escape:html}
				{else}{t}Sorry, event not found.{/t}
				{/if}
			</div>
            
			<div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.list.view')}">{t}Back to List View{/t}</a></div>
			<div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('calendar.month.view')}">{t}Back to Calendar View{/t}</a></div>									
			<div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
			<div class="prInnerTop">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}</div>
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

{* PAGE CONTENT END *}
</div>