{include file="content_objects/edit_mode_settings_narrow.tpl"}

<div class="prInner">
	
        <label class="prText2">{t}Code:{/t}</label><br />
		<textarea style="width:100%; height:200px;" id="script_src_{$cloneId}" onkeyup="changeScriptAltSrc('{$cloneId}', this.value);">{$alt_src|escape:'html'}</textarea>
        <label class="prText2">{t}Custom height:{/t}&nbsp;</label>
         <br /><input type="text" value="{$custom_height|escape:'html'}" onkeyup="changeScriptCustomHeight('{$cloneId}', this.value);" />
    
</div>	

{include file="content_objects/edit_mode_buttons.tpl"}
