{include file="content_objects/edit_mode_settings_wide.tpl"}
<div class="prIndent">
    <table class="prForm">
        <col width="33%" />
        <col width="33%" />
        <col width="33%" />
        <tbody>
            <tr>
                <td colspan="3" class="prTBold">{t}Select Pre-set Top Video tabs to display:{/t}</td>
            </tr>
            <tr>
                <td>
                    <input onclick="family_topvideos_most_active_check((document.getElementById('family_topvideos_most_active_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_topvideos_most_active_check_{$cloneId}" type="checkbox" {if $topvideosDisplayMostActive}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                    <label for="family_topvideos_most_active_check_{$cloneId}">{t}Most Active{/t}</label>
                </td>
                <td>
                    <input onclick="family_topvideos_most_recent_check((document.getElementById('family_topvideos_most_recent_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_topvideos_most_recent_check_{$cloneId}" type="checkbox" {if $topvideosDisplayMostRecent}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                    <label for="family_topvideos_most_recent_check_{$cloneId}">{t}Most Recent{/t}</label>
                </td>
                <td>&nbsp;
                    {*<input onclick="family_topvideos_most_upped_check((document.getElementById('family_topvideos_most_upped_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="family_topvideos_most_upped_check_{$cloneId}" type="checkbox" {if $topvideosDisplayMostUpped}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" />
                    <label for="family_topvideos_most_upped_check_{$cloneId}">{t}Most Upped{/t}</label>*}
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <label class="prTBold" for="family_topvideos_threads_number_select_{$cloneId}">{t}Set the number of videos you wish to display in each tab:{/t}</label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <select id="family_topvideos_threads_number_select_{$cloneId}" onchange="set_family_topvideos_thread_number(this.value, '{$cloneId}');return false;">
                <option value="0" {if $topvideosShowThreadsNumber == 0}selected="selected"{/if}>{t}Show 0 videos{/t}</option>
                <option value="1" {if $topvideosShowThreadsNumber == 1}selected="selected"{/if}>{t}Show 1 video{/t}</option>
                <option value="2" {if $topvideosShowThreadsNumber == 2}selected="selected"{/if}>{t}Show 2 videos{/t}</option>
                <option value="3" {if $topvideosShowThreadsNumber == 3}selected="selected"{/if}>{t}Show 3 videos{/t}</option>
                <option value="4" {if $topvideosShowThreadsNumber == 4}selected="selected"{/if}>{t}Show 4 videos{/t}</option>
                <option value="5" {if $topvideosShowThreadsNumber == 5}selected="selected"{/if}>{t}Show 5 videos{/t}</option>
                <option value="6" {if $topvideosShowThreadsNumber == 6}selected="selected"{/if}>{t}Show 6 videos{/t}</option>
                <option value="7" {if $topvideosShowThreadsNumber == 7}selected="selected"{/if}>{t}Show 7 videos{/t}</option>
                <option value="8" {if $topvideosShowThreadsNumber == 8}selected="selected"{/if}>{t}Show 8 videos{/t}</option>
                <option value="9" {if $topvideosShowThreadsNumber == 9}selected="selected"{/if}>{t}Show 9 videos{/t}</option>
                <option value="10" {if $topvideosShowThreadsNumber == 10}selected="selected"{/if}>{t}Show 10 videos{/t}</option>
                <option value="20" {if $topvideosShowThreadsNumber == 20}selected="selected"{/if}>{t}Show 20 videos{/t}</option>
                <option value="30" {if $topvideosShowThreadsNumber == 30}selected="selected"{/if}>{t}Show 30 videos{/t}</option>
                <option value="40" {if $topvideosShowThreadsNumber == 40}selected="selected"{/if}>{t}Show 40 videos{/t}</option>
                <option value="50" {if $topvideosShowThreadsNumber == 50}selected="selected"{/if}>{t}Show 50 videos{/t}</option>
            </select>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
{include file="content_objects/headline_block_wide.tpl"}
{include file="content_objects/ddFamilyTopVideos/light_block_wide.tpl"}
{include file="content_objects/edit_mode_buttons.tpl"}
