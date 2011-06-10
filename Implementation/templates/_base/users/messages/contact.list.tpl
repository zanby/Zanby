{foreach from=$formParams.mail_lists item='concactList'}
<div class="prClr3">
    <div class="prFloatLeft">{$concactList->getDisplayName()|escape}</div>
    <div class="prFloatLeft prIndentLeftSmall"><a href="javascript:void(0);" style="font-size:11px;" onClick="xajax_deleteAddressFromField('list', {$concactList->getContactListId()}, getListObjects(), getGroupObjects()); return false;">{t}Delete{/t}</a></div>
    <input type="hidden" name="mail_lists[]" value="{$concactList->getContactListId()}" class="mail-list-object-hidden" />
</div>
{/foreach}
