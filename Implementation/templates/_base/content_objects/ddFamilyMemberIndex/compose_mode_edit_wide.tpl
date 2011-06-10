{include file="content_objects/edit_mode_settings_wide.tpl"}
<div class="prInnerSmall">
    <table class="prForm">
        <col width="33%" />
        <col width="33%" />
        <col width="33%" />
        <tbody>
            <tr>
                <td colspan="3" class="prTBold">{t}Sort Member Hierarchies:{/t}</td>
            </tr>
            <tr>
                <td colspan="3">
                    <select name="default_fmi_index_sort_{$cloneId}" id="default_fmi_index_sort_{$cloneId}" onchange="default_fmi_index_sort_change('{$cloneId}', this.selectedIndex);">
            <option {if !$default_index_sort}selected="selected"{/if}>{t}Alphabetically by Name{/t}</option>
            <option {if $default_index_sort == '1'}selected="selected"{/if}>{t}None{/t}</option>
        </select>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <label class="prTBold">{t}Show top{/t} </label>
                    <select class="prAutoWidth" id="display_fmi_number_in_each_region_{$cloneId}" onchange="set_fmi_display_number_in_each_region(this.value, '{$cloneId}');return false;">
            <option value="1" {if $display_number_in_each_region==1}selected="selected"{/if}>1</option>
            <option value="2" {if $display_number_in_each_region==2}selected="selected"{/if}>2</option>
            <option value="3" {if $display_number_in_each_region==3}selected="selected"{/if}>3</option>
            <option value="4" {if $display_number_in_each_region==4}selected="selected"{/if}>4</option>
            <option value="5" {if $display_number_in_each_region==5}selected="selected"{/if}>5</option>
            <option value="6" {if $display_number_in_each_region==6}selected="selected"{/if}>6</option>
            <option value="7" {if $display_number_in_each_region==7}selected="selected"{/if}>7</option>
            <option value="8" {if $display_number_in_each_region==8}selected="selected"{/if}>8</option>
            <option value="9" {if $display_number_in_each_region==9}selected="selected"{/if}>9</option>
            <option value="10" {if $display_number_in_each_region==10}selected="selected"{/if}>10</option>
            <option value="20" {if $display_number_in_each_region==20}selected="selected"{/if}>20</option>
            <option value="30" {if $display_number_in_each_region==30}selected="selected"{/if}>30</option>
            <option value="40" {if $display_number_in_each_region==40}selected="selected"{/if}>40</option>
            <option value="50" {if $display_number_in_each_region==50}selected="selected"{/if}>50</option>
        </select>
                    <label class="prTBold">{t}Members per region  before &#34;More&#34; link{/t}</label>
                </td>
            </tr>
        </tbody>
    </table>
</div>
{include file="content_objects/headline_block_wide.tpl"}
<div class="themeA" id="light_{$cloneId}"> 
    {include file="content_objects/ddFamilyMemberIndex/light_block_wide.tpl"}
</div>
<div class="prClearer"></div>
{include file="content_objects/edit_mode_buttons.tpl"}
