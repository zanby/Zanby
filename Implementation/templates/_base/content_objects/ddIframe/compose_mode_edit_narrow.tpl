{include file="content_objects/edit_mode_settings_narrow.tpl"}

<div class="themeA">
	<div class="prInner">
        <table cellspacing="0" cellpadding="0" style="width: 95%;">
            <tbody>  
                <tr>
                    <td>
                        <strong>{t}Custom code:{/t}</strong><br />
                        <input type="text" name="altSrc" id="iframe_src_{$cloneId}" value="{$alt_src|escape:'html'}" onchange="changeIframeAltSrc('{$cloneId}', this.value);"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>	

{include file="content_objects/edit_mode_buttons.tpl"}
