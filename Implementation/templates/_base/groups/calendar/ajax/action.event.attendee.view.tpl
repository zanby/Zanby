{*popup_item*}
<h4>{$objEvent->getTitle()|escape:html}</h4>
    {$objEvent->displayDate('attendee.view', $user, $currentTimezone)}

    {assign var="canManageEvent" value=$Warecorp_ICal_AccessManager->canManageEvent($objEvent, $CurrentGroup, $user)}
    {assign var="allowDisplayListToGuest" value=0}
    {if $canManageEvent}
        {assign var="allowDisplayListToGuest" value=1}
    {else}
        {if $objEvent->getInvite()->getDisplayListToGuest()}
            {assign var="allowDisplayListToGuest" value=1}
        {else}
            {if $objEvent->getOwnerType() == 'user'}
                {if $objEvent->getOwnerId() == $user->getId()}
                    {assign var="allowDisplayListToGuest" value=1}
                {/if}
            {else}
                {if $objEvent->getCreatorId() == $user->getId()}
                    {assign var="allowDisplayListToGuest" value=1}
                {/if}
            {/if}
        {/if}
    {/if}
<div class="prClr3 prIndentTop prEvents-att-BottomBlock" style="width: 99% !important;">
    <div class="prEventType">
            <div class="prEvents-att-attending">
                <span class="prEvents-att-attending-action">&#160;</span>
                <span class="prEvents-att-attending-count" href="#null">{$objEvent->getAttendee()->setAnswerFilter('YES')->getCount()}</span>
                <span class="prEvents-att-text">{t}Attending{/t}</span>
            </div>
            <div>
                <ul class="prInnerSmallTop prClr2">
                    {if $allowDisplayListToGuest}
                        {foreach from=$objEvent->getAttendee()->setFetchMode('object')->setAnswerFilter('YES')->getList() item='attendee'}
                            <li>
                            {if $attendee->getOwnerType() == 'user'}
                                {if null === $attendee->getOwner()->getId()}
                                    {if $attendee->getName()}
                                        {$attendee->getName()|escape:'html'}
                                    {else}
                                        {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                    {/if}
                                {else}
                                    <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>
                                {/if}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {elseif $attendee->getOwnerType() == 'fbuser'}
                                {$attendee->getName()}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {else}
                            {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
    </div>
    <div class="prEventType">
            <div class="prEvents-att-attending">
                <span class="prEvents-att-denying-action">&#160;</span>
                <span class="prEvents-att-denying-count" href="#null">{$objEvent->getAttendee()->setAnswerFilter('NO')->getCount()}</span>
                <span class="prEvents-att-text">{t}Not Attending{/t}</span>
            </div>
            <div class="prPopupHeight3">
                <ul class="prInnerSmallTop prClr2">
                    {if $allowDisplayListToGuest}
                        {foreach from=$objEvent->getAttendee()->setFetchMode('object')->setAnswerFilter('NO')->getList() item='attendee'}
                            <li>
                            {if $attendee->getOwnerType() == 'user'}
                                {if null === $attendee->getOwner()->getId()}
                                    {if $attendee->getName()}
                                        {$attendee->getName()|escape:'html'}
                                    {else}
                                        {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                    {/if}
                                {else}
                                    <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>
                                {/if}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {elseif $attendee->getOwnerType() == 'fbuser'}
                                {$attendee->getName()}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {else}
                            {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
    </div>
    <div class="prEventType prEvents-att-borderBottom prInnerSmallBottom" style="width: 34% !important;">
        <span class="prEvents-att-not-action">&#160;</span>
        <span class="prEvents-att-not-count" href="#null">{$objEvent->getAttendee()->setAnswerFilter('NONE')->getCount()}</span>
        <span class="prEvents-att-text">{t}Not responded{/t}</span>
    </div>
</div>
<div class="prPopupHeight3">
    <ul class="prInnerSmallTop prClr2">
        {if $allowDisplayListToGuest}
            {foreach from=$objEvent->getAttendee()->setFetchMode('object')->setAnswerFilter('NONE')->getList() item='attendee'}
                <li>
                {if $attendee->getOwnerType() == 'user'}
                    {if null === $attendee->getOwner()->getId()}
                        {if $attendee->getName()}
                            {$attendee->getName()|escape:'html'}
                        {else}
                            {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                        {/if}
                    {else}
                        <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>
                    {/if}
                    {if $attendee->getAnswerText()}
                        <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                    {/if}
                {elseif $attendee->getOwnerType() == 'fbuser'}
                    {$attendee->getName()}
                    {if $attendee->getAnswerText()}
                        <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                    {/if}
                {else}
                {/if}
                </li>
            {/foreach}
        {/if}
    </ul>
</div>


<div class="prTCenter prInnerTop">
    {t var="in_button"}Close Attendance Details{/t}{linkbutton name=$in_button onclick="popup_window.close();return false;"}
</div>
{*popup_item*}
