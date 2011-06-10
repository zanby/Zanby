{foreach from=$formParams.event_invitations_lists item='concactList'}
<div class="prInnerSmallTop prClr3">
   <div class="prFloatLeft">{$concactList->getDisplayName()|escape}</div>
   <div class="prFloatRight"><a href="#null" onClick="xajax_deleteAddressFromField('list', {$concactList->getContactListId()}, CreateEventApp.getInviteListObjects(), CreateEventApp.getInviteGroupObjects()); return false;">{t}Delete{/t}</a></div>    
    <input type="hidden" name="event_invitations_lists[]" value="{$concactList->getContactListId()}" class="events-object-list-hidden" />
</div>
{/foreach}