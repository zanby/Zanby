{foreach from=$formParams.mail_groups item='group'}
<div class="prClr3">
    <div class="prFloatLeft">{$group->getName()|escape}</div>
    <div class="prFloatLeft prIndentLeftSmall"><a href="javascript:void(0);" style="font-size:11px;" onClick="xajax_deleteAddressFromField('group', {$group->getId()}, getListObjects(), getGroupObjects()); return false;">{t}Delete{/t}</a></div>
    <input type="hidden" name="mail_groups[]" value="{$group->getId()}" class="mail-group-object-hidden" />
</div>
{/foreach}
