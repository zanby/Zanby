{foreach from=$formParams.event_invitations_groups item='group'}
<div class="prInnerSmallTop prClr3">
    <div class="prFloatLeft">{$group->getName()|escape}</div>
    <div class="prFloatRight"><a href="#null" onClick="xajax_deleteAddressFromField('group', {$group->getId()}, CreateEventApp.getInviteListObjects(), CreateEventApp.getInviteGroupObjects()); return false;">{t}Delete{/t}</a></div>    
    <input type="hidden" name="event_invitations_groups[]" value="{$group->getId()}" class="" />
</div>
{/foreach}