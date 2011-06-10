{include file="content_objects/edit_mode_settings_wide.tpl"}
{include file="content_objects/headline_block_wide.tpl"}
{include file="content_objects/ddFamilyIcons/light_block_wide.tpl"}

<div class="prIndentLeft">
    <a class="prLink2" href="#null" onclick='xajax_select_bgi("{$cloneId}", ""{if $currentAvatar->getId()},{/if} {$currentAvatar->getId()});return false;'>{t}Select photo{/t} &raquo;</a>
</div>
{include file="content_objects/edit_mode_buttons.tpl"}
