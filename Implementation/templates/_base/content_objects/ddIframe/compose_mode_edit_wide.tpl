{include file="content_objects/edit_mode_settings_wide.tpl"}

<div class="themeA prTCenter">
	<div class="prInner">
        <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <col width="25%" />
            <col width="75%" />
            <tbody>
                <tr>
                    <td class="prTRight"><label>{t}Custom code:{/t}&nbsp;</label></td>
                    <td><input type="text" name="altSrc" id="iframe_src_{$cloneId}" value="{$alt_src|escape:'html'}" onchange="changeIframeAltSrc('{$cloneId}', this.value);"/></td>
                </tr>
            </tbody>
        </table>
   </div>
</div>	

{include file="content_objects/edit_mode_buttons.tpl"}