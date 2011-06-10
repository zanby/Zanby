BEGIN:VCALENDAR
PRODID:-//Calendar//Calendar//EN
VERSION:2.0
METHOD:PUBLISH
{$v_timezone_info}
BEGIN:VEVENT
ORGANIZER;CN={$event_object->getOwner()->getFirstname()} {$event_object->getOwner()->getLastname()}:MAILTO:{$event_object->getOwner()->getEmail()}
UID:{$uid}
ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;RSVP=TRUE;CN={$event_object->getOwner()->getFirstname()} {$event_object->getOwner()->getLastname()};X-NUM-GUESTS=0:MAILTO:{$event_object->getOwner()->getEmail()}
{foreach from=$attendances item=value}
{if $value->getAnswer() != 0 && $value->getEmail() != $event_object->getOwner()->getEmail()}
ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP={if $value->getAnswer() == 1}TRUE{else}FALSE{/if};CN={$value->getEmail()};X-NUM-GUESTS=0:MAILTO:{$value->getEmail()}
{/if}
{/foreach}
{if $type == "A"}
DTSTART;VALUE=DATE:{$event_object->getDtStartObj()->toString('yyyyMMdd')}
{else}
DTSTART;TZID="{$tzid}":{$event_object->getDtStartObj()->toString('yyyyMMddTHHmmss')}Z
{/if}
{if $type == "A"}
DTEND;VALUE=DATE:{$event_object->getDtEndObj()->toString('yyyyMMdd')}
{else}
DTEND;TZID="{$tzid}":{$event_object->getDtEndObj()->toString('yyyyMMddTHHmmss')}Z
{/if}
{if $rrule}
RRULE:{$rrule}  
{/if}
{if $exdate && $rdate}
EXDATE:{$exdate}
RDATE:{$rdate}
{/if}
{if $type == "A"}
TRANSP:TRANSPARENT
{else}
TRANSP:OPAQUE
{/if}
DTSTAMP:{$cur_time|date_format:"%Y%m%d"}T{$cur_time|date_format:"%H%M%S"}Z
DESCRIPTION:{$notes}
SUMMARY:{$title}
PRIORITY:5
CLASS:PUBLIC
END:VEVENT
END:VCALENDAR