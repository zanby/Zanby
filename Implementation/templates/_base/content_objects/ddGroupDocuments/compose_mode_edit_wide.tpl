{include file="content_objects/edit_mode_settings_wide.tpl"}
{include file="content_objects/headline_block_wide.tpl"}
<div class="prInnerLeft">
    <h3>
        <a href="#null" onclick="addDDDocument('{$cloneId}');return false;">{t}Add document{/t}</a>
    </h3>
</div>
<div class="themeA" id="light_{$cloneId}"> {include file="content_objects/ddGroupDocuments/light_block_wide.tpl"} </div>
{include file="content_objects/edit_mode_buttons.tpl"} 