{*
{if !$hideHeadlineHeader}
    <div class="prCO-editpanel prInnerLeft" style="background-color:#f1f1f1;">
        <h3>
            <span>{t}Headline{/t}</span>
        </h3>
    </div>
{/if}

<div class="prCO-editpanel" style="display:block; font-size:10px;" id="tinyMCE_{$cloneId}_div_wait_H"><div style="position:absolute; margin-left:12px; margin-top:30px; z-index:99999;"><img style="padding:5px;" src="{$AppTheme->images}/decorators/waiting.gif" alt=""/></div></div>

<div class="prCO-editpanel" style="border:9px solid #f1f1f1; visibility:hidden;" id="tinyMCE_{$cloneId}_div_H">


<textarea rows="3" id="tinyMCE_{$cloneId}_H" name="tinyMCE_{$cloneId}_H" onkeyup="WarecorpDDblockApp.setHeadline('{$cloneId}', this.value);">{$headline|escape:'html'}</textarea>

</div>
*}
