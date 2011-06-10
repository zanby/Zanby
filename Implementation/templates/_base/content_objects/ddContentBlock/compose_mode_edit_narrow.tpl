<div class="themeA prClearIndent">
    <div id="tinyMCE_{$cloneId}_div_wait" style="display:block;" align="center"><img style="padding:5px;" src="{$AppTheme->images}/decorators/waiting.gif" alt=""/></div>
    <div id="tinyMCE_{$cloneId}_div" style="display:none; margin-right:1px;">
        <form  name="tinyMCEform_{$cloneId}" id="tinyMCEform_{$cloneId}" method="post" action="">
            <textarea id="tinyMCE_{$cloneId}" name="tinyMCE_{$cloneId}" rows="16" {*cols="80"*} style="line-height:0px; font-size:0px; width:100%;">{$Content|escape:html}</textarea>
        </form>
    </div>
</div>

{include file="content_objects/edit_mode_buttons.tpl"}
