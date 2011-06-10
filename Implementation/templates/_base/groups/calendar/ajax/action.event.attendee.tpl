{*popup_item*}
{form from=$form id="rsvp_event_form" onsubmit="xajax_doAttendeeEvent(`$event_id`, `$uid`, '`$view`', xajax.getFormValues('rsvp_event_form'), '`$date`'); return false;"}

{form_errors_summary}

<p class="prText2">{t}Will you attend?{/t}</p>
{if $date && $objEvent->getRrule()}
    {form_radio name='attendee_mode' id="attendee_mode_2" value='2' checked=2}<label for="attendee_mode_2"> {t}for this date only{/t}</label>
    <div class="prIndentTopSmall prIndentBottom">
        {form_radio name='attendee_mode' id="attendee_mode_1" value='1'}<label for="attendee_mode_1"> {t}for all dates{/t}</label>
    </div>
{/if}
<span class="prInnerRight">
    {if $userAttendee->getAnswer() == 'NONE'}
        {form_radio name="attending_rsvp_way" id="attending_rsvp_way_yes" value="YES" checked='YES'}<label for="attending_rsvp_way_yes"> {t}Yes{/t}</label>
    {else}
        {form_radio name="attending_rsvp_way" id="attending_rsvp_way_yes" value="YES" checked=$userAttendee->getAnswer()}<label for="attending_rsvp_way_yes"> {t}Yes{/t}</label>
    {/if}
</span>
<span class="prInnerRight">
    {form_radio name="attending_rsvp_way" id="attending_rsvp_way_no" value="NO" checked=$userAttendee->getAnswer()}<label for="attending_rsvp_way_no"> {t}No{/t}</label></span>
    {form_radio name="attending_rsvp_way" id="attending_rsvp_way_maybe" value="MAYBE" checked=$userAttendee->getAnswer()}<label for="attending_rsvp_way_maybe"> {t}Maybe{/t}</label>
    {form_textarea name="attending_rsvp_message" class="prPerWidth995" rows="5" value=$userAttendee->getAnswerText()|escape:html}

<div class="prTCenter prIndentTop">
    {t var="in_button"}Send RSVP{/t}
    {linkbutton name=$in_button  onclick="xajax_doAttendeeEvent(`$event_id`, `$uid`, '`$view`', xajax.getFormValues('rsvp_event_form'), '`$date`'); return false;"}</span>
     <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
</div>
{/form}
{*popup_item*}
