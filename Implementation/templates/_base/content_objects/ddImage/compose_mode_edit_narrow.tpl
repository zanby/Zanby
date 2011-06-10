{include file="content_objects/edit_mode_settings_narrow.tpl"}

{include file="content_objects/headline_block_narrow.tpl"}

{include file="content_objects/ddImage/light_block_narrow.tpl"}

<div class="prIndentLeft">
    <a class="prLink2" href="#null" onclick='tmpElement = WarecorpDDblockApp.getObjByID("{$cloneId}"); xajax_ddImage_select_avatar("{$cloneId}", 0, 10, tmpElement.avatarId);return false;'>{t}Select photo{/t} &raquo;</a>
</div>
{include file="content_objects/edit_mode_buttons.tpl"}
