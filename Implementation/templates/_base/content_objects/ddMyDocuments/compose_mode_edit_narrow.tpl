{include file="content_objects/edit_mode_settings_narrow.tpl"}
{include file="content_objects/headline_block_narrow.tpl"}
<div class="prInnerLeft">
    <h3>
        <a href="#null" onclick="addDDDocument('{$cloneId}');return false;">{t}Add document{/t}</a>
    </h3>
</div>
<div class="themeA" id="light_{$cloneId}"> {include file="content_objects/ddMyDocuments/light_block_narrow.tpl"} </div>
{include file="content_objects/edit_mode_buttons.tpl"} 