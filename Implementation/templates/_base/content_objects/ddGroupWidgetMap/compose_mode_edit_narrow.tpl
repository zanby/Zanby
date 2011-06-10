{include file="content_objects/edit_mode_settings_narrow.tpl"}

<div class="themeA">
    <iframe src="" id="wMap_iframe_{$cloneId}" style="width:240px; height:320px;" frameborder="0" ></iframe>
</div>

<div class="prIndent">
    <table class="prForm">
        <col width="25%" />
        <col width="25%" />
        <col width="25%" />
        <col width="25%" />
        <tbody>
            <tr>
                <td colspan="2" class="prTBold">{t}Default display:{/t}</td>
            </tr>
           
            
            <tr>
                <!-- Default display -->
                <td>
                    <input name="wMap_defaultDisplayType_{$cloneId}" id="wMap_defaultDisplayType_0_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $defaultDisplayType==0 || !$defaultDisplayType}checked="checked"{/if} onclick="wMap_defaultDisplayType_change('{$cloneId}',0);" /><label for="wMap_defaultDisplayType_0_{$cloneId}">&nbsp;{t}Groups{/t}</label>
                    
                </td>
                <td>
                    <input name="wMap_defaultDisplayType_{$cloneId}" id="wMap_defaultDisplayType_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $defaultDisplayType==1}checked="checked"{/if} onclick="wMap_defaultDisplayType_change('{$cloneId}',1);" /><label for="wMap_defaultDisplayType_1_{$cloneId}">&nbsp;{t}Events{/t}</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="prTBold">{t}Range:{/t}</td>
            </tr>
            <tr>    
                <!-- Range -->
                <td>
                    <input name="wMap_displayRange_{$cloneId}" id="wMap_displayRange_0_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $displayRange==0 || !$displayRange}checked="checked"{/if} onclick="wMap_displayRange_change('{$cloneId}',0);" /><label for="wMap_displayRange_0_{$cloneId}">&nbsp;{t}Everywhere{/t}</label>
                    
                </td>
                <td>
                    <input name="wMap_displayRange_{$cloneId}" id="wMap_displayRange_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $displayRange==1}checked="checked"{/if} onclick="wMap_displayRange_change('{$cloneId}',1);" /><label for="wMap_displayRange_1_{$cloneId}">&nbsp;{t}Just My District{/t}</label>
                </td> 
            </tr>
            
            
            
            
            <!-- {t}Event display{/t} -->
            <tr>
                <td colspan="4" class="prTBold">{t}Event display:{/t}</td>
            </tr>
            
            <tr>
                <td colspan="4">
                    <input name="wMap_eventsDisplayType_{$cloneId}" id="wMap_eventsDisplayType_0_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $eventsDisplayType==0 || !$eventsDisplayType}checked="checked"{/if} onclick="wMap_eventsDisplayType_change('{$cloneId}',0);" /><label for="wMap_eventsDisplayType_0_{$cloneId}">&nbsp;{t}Display all events{/t}</label>
                    
                </td>
            </tr>
            
            <tr>
                <td colspan="4">
                    <input name="wMap_eventsDisplayType_{$cloneId}" id="wMap_eventsDisplayType_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $eventsDisplayType == 1}checked="checked"{/if} onclick="wMap_eventsDisplayType_change('{$cloneId}',1);" /><label for="wMap_eventsDisplayType_1_{$cloneId}">&nbsp;{t}Select National Day of Action and Affilated Events{/t}</label>
                    
                </td>
            </tr>
            
        
            <tr {if !$eventsDisplayType}style="display:none"{/if} id="wMap_eventToDisplayId_2_{$cloneId}">
                <td colspan="4">
                    
                    {if $ndaCount}
                        <select id="wMap_eventToDisplayId_select_{$cloneId}" onchange="set_wMap_eventToDisplayId(this.value, '{$cloneId}');return false;">
                            {foreach from=$ndaList item=nda}
                                <option value="{$nda->getId()}" {if $nda->getId() == $eventToDisplayId}selected="selected"{/if}>{$nda->getName()|escape}</option>
                            {/foreach}
                        </select>
                    {else}
                        {t}National Days of Action were not found{/t}
                    {/if}
                </td>
            </tr>
         
        </tbody>
    </table>
</div>

{include file="content_objects/edit_mode_buttons.tpl"}
