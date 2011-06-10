{assign var="objCopyEvent" value=$objEvent}
<div class="prClr3">
    <div class="prEventViewImg">
        {if $objCopyEvent->getEventPicture() !== null}
            <img src="{$objCopyEvent->getEventPicture()->setWidth(75)->setHeight(75)->getImage($user)}" />
        {else}
            <img src="{$AppTheme->images}/decorators/event/no_event_image.jpg" />
        {/if}
    </div>

    <div class="prEventViewDescr">
        <div class="prInnerRight">
            {$objCopyEvent->displayDate('event.view', $user, $currentTimezone)}

            <p class="prInnerSmallTop">
            {$objCopyEvent->getTitle()|escape:html}
            </p>
        </div>
    </div>
    <div class="prFloatRight"><a onclick="printEventDetails();" href="#null" class="prButton"><span>Print</span></a></div>
</div>

{if $attendeeYesItemsCount > 0}
<h2 class="prTCenter">{t}Attending{/t} ({$attendeeYesItemsCount})</h2>
    <table class="prResultPrint">
    <col width="20%" />
    <col width="20%" />
    <col width="60%" />
    <thead><th>Full Name</th><th>Username</th><th>Comments</th></thead>
    <tbody>
    {foreach from=$attendeeYesItems item="attendee" name="attendeeLoop"}
        {if $attendee->getOwnerType() == 'user'}
            {if null === $attendee->getOwner()->getId()}
                {if $attendee->getName()}
                    {assign var="attendeeName" value=$attendee->getName()}
                {else}
                    {assign var="attendeeName" value=$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                {/if}
                {assign var="attendeeLogin" value=""}
            {else}
                {capture assign="attendeeName"}{$attendee->getOwner()->getFirstname()} {$attendee->getOwner()->getLastname()}{/capture}
                {assign var="attendeeLogin" value=$attendee->getOwner()->getLogin()}
            {/if}
        {elseif $attendee->getOwnerType() == 'fbuser'}
            {assign var="attendeeName" value=$attendee->getName()}
            {assign var="attendeeLogin" value=""}
        {else}
            {assign var="attendeeName" value=""}
            {assign var="attendeeLogin" value=""}
        {/if}
        <tr {if $smarty.foreach.attendeeLoop.index % 2 == 0}class="prEvenBg"{else}class="prOddBg"{/if}>
            <td>{$attendeeName|escape:'html'}</td>
            <td>{$attendeeLogin|escape:'html'}</td>
            <td>{$attendee->getAnswerText()|escape:'html'|nl2br}</td>
        <tr>
    {/foreach}
    </tbody>
    <table>
{/if}

{if $attendeeNoItemsCount > 0}
<h2 class="prTCenter">{t}No Attending{/t} ({$attendeeNoItemsCount})</h2>
    <table class="prResultPrint">
    <col width="20%" />
    <col width="20%" />
    <col width="60%" />
    <thead><th>Full Name</th><th>Username</th><th>Comments</th></thead>
    <tbody>
    {foreach from=$attendeeNoItems item="attendee" name="attendeeLoop"}
        {if $attendee->getOwnerType() == 'user'}
            {if null === $attendee->getOwner()->getId()}
                {if $attendee->getName()}
                    {assign var="attendeeName" value=$attendee->getName()}
                {else}
                    {assign var="attendeeName" value=$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                {/if}
                {assign var="attendeeLogin" value=""}
            {else}
                {capture assign="attendeeName"}{$attendee->getOwner()->getFirstname()} {$attendee->getOwner()->getLastname()}{/capture}
                {assign var="attendeeLogin" value=$attendee->getOwner()->getLogin()}
            {/if}
        {elseif $attendee->getOwnerType() == 'fbuser'}
            {assign var="attendeeName" value=$attendee->getName()}
            {assign var="attendeeLogin" value=""}
        {else}
            {assign var="attendeeName" value=""}
            {assign var="attendeeLogin" value=""}
        {/if}
        <tr {if $smarty.foreach.attendeeLoop.index % 2 == 0}class="prEvenBg"{else}class="prOddBg"{/if}>
            <td>{$attendeeName|escape:'html'}</td>
            <td>{$attendeeLogin|escape:'html'}</td>
            <td>{$attendee->getAnswerText()|escape:'html'|nl2br}</td>
        <tr>
    {/foreach}
    </tbody>
    <table>
{/if}

{if $attendeeMaybeItemsCount > 0}
<h2 class="prTCenter">{t}Maybe{/t} ({$attendeeMaybeItemsCount})</h2>
    <table class="prResultPrint">
    <col width="20%" />
    <col width="20%" />
    <col width="60%" />
    <thead><th>Full Name</th><th>Username</th><th>Comments</th></thead>
    <tbody>
    {foreach from=$attendeeMaybeItems item="attendee" name="attendeeLoop"}
        {if $attendee->getOwnerType() == 'user'}
            {if null === $attendee->getOwner()->getId()}
                {if $attendee->getName()}
                    {assign var="attendeeName" value=$attendee->getName()}
                {else}
                    {assign var="attendeeName" value=$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                {/if}
                {assign var="attendeeLogin" value=""}
            {else}
                {capture assign="attendeeName"}{$attendee->getOwner()->getFirstname()} {$attendee->getOwner()->getLastname()}{/capture}
                {assign var="attendeeLogin" value=$attendee->getOwner()->getLogin()}
            {/if}
        {elseif $attendee->getOwnerType() == 'fbuser'}
            {assign var="attendeeName" value=$attendee->getName()}
            {assign var="attendeeLogin" value=""}
        {else}
            {assign var="attendeeName" value=""}
            {assign var="attendeeLogin" value=""}
        {/if}
        <tr {if $smarty.foreach.attendeeLoop.index % 2 == 0}class="prEvenBg"{else}class="prOddBg"{/if}>
            <td>{$attendeeName|escape:'html'}</td>
            <td>{$attendeeLogin|escape:'html'}</td>
            <td>{$attendee->getAnswerText()|escape:'html'|nl2br}</td>
        <tr>
    {/foreach}
    </tbody>
    <table>
{/if}

{if $attendeeNoneItemsCount > 0}
<h2 class="prTCenter">{t}Have not responded{/t} ({$attendeeNoneItemsCount})</h2>
    <table class="prResultPrint">
    <col width="20%" />
    <col width="20%" />
    <col width="60%" />
    <thead><th>Full Name</th><th>Username</th><th>Comments</th></thead>
    <tbody>
    {foreach from=$attendeeNoneItems item="attendee" name="attendeeLoop"}
        {if $attendee->getOwnerType() == 'user'}
            {if null === $attendee->getOwner()->getId()}
                {if $attendee->getName()}
                    {assign var="attendeeName" value=$attendee->getName()}
                {else}
                    {assign var="attendeeName" value=$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                {/if}
                {assign var="attendeeLogin" value=""}
            {else}
                {capture assign="attendeeName"}{$attendee->getOwner()->getFirstname()} {$attendee->getOwner()->getLastname()}{/capture}
                {assign var="attendeeLogin" value=$attendee->getOwner()->getLogin()}
            {/if}
        {elseif $attendee->getOwnerType() == 'fbuser'}
            {assign var="attendeeName" value=$attendee->getName()}
            {assign var="attendeeLogin" value=""}
        {else}
            {assign var="attendeeName" value=""}
            {assign var="attendeeLogin" value=""}
        {/if}
        <tr {if $smarty.foreach.attendeeLoop.index % 2 == 0}class="prEvenBg"{else}class="prOddBg"{/if}>
            <td>{$attendeeName|escape:'html'}</td>
            <td>{$attendeeLogin|escape:'html'}</td>
            <td>{$attendee->getAnswerText()|escape:'html'|nl2br}</td>
        <tr>
    {/foreach}
    </tbody>
    <table>
{/if}
<div class="prClr3"><div class="prFloatRight"><a onclick="printEventDetails();" href="#null" class="prButton"><span>Print</span></a></div></div>
