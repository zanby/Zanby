{include file="content_objects/edit_mode_settings_wide.tpl"}
{include file="content_objects/headline_block_wide.tpl"}

<div class="prInnerSmall">
    <a style="zoom: 1;" id="href-profile-popup_{$cloneId}" href="#null" class="switched" onclick="switchProfilePopup('{$cloneId}');return false;">{t}Details fields{/t} <img src="{$AppTheme->images}/decorators/bkgArrowRight.gif" /></a>
</div>

<div class="prInnerSmall" id="profile-popup_{$cloneId}" style="display:none;">
        
        <div class="prFloatLeft">
            <input onclick="profile_element_hide(0,!document.getElementById('hide_check_0_{$cloneId}').checked,'{$cloneId}');" id="hide_check_0_{$cloneId}" type="checkbox" checked="checked" class="prAutoWidth prNoBorder" />
            <label for="hide_check_0_{$cloneId}"><span>{t}Username{/t}</span></label><br />
            <input onclick="profile_element_hide(3,!document.getElementById('hide_check_3_{$cloneId}').checked,'{$cloneId}');" id="hide_check_3_{$cloneId}" type="checkbox" checked="checked" class="prAutoWidth prNoBorder" /> <label for="hide_check_3_{$cloneId}"><span>{t}Real Name{/t}</span></label> 
        </div>
        <div class="prFloatLeft">
            <input onclick="profile_element_hide(1,!document.getElementById('hide_check_1_{$cloneId}').checked,'{$cloneId}');" id="hide_check_1_{$cloneId}" type="checkbox" checked="checked" class="prAutoWidth prNoBorder" /> <label for="hide_check_1_{$cloneId}"><span>{t}Age{/t}</span></label><br />
            <input onclick="profile_element_hide(4,!document.getElementById('hide_check_4_{$cloneId}').checked,'{$cloneId}');" id="hide_check_4_{$cloneId}" type="checkbox" checked="checked" class="prAutoWidth prNoBorder" /> <label for="hide_check_4_{$cloneId}"><span>{t}Location{/t}</span></label>
        </div>
        <div class="prFloatLeft">
            <input onclick="profile_element_hide(2,!document.getElementById('hide_check_2_{$cloneId}').checked,'{$cloneId}');" id="hide_check_2_{$cloneId}" type="checkbox" checked="checked" class="prAutoWidth prNoBorder" /> <label for="hide_check_2_{$cloneId}"><span>{t}Gender{/t}</span></label>
        </div>
        <div class="prClr2"></div>

</div>

{include file="content_objects/ddProfileDetails/light_block_wide.tpl"}

{include file="content_objects/edit_mode_buttons.tpl"}
