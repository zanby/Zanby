{include file="content_objects/edit_mode_settings_wide.tpl"}

<div class="prIndent">
	<table cellspacing="0" cellpadding="0" class="prForm">
		<col width="15%" />
		<col width="85%" />
		<tbody>
			<tr>
				<td class="prTRight"><label>{t}Channel:{/t}</label></td>
				<td><input type="text" name="channel" id="mogulus_channel_{$cloneId}" value="{$channel|escape:'html'}" onchange="changeMogulusChannel('{$cloneId}', this.value);"/></td>
			</tr>
			<tr>
				<td class="prTRight"><label>{t}Autoplay:{/t}</label></td>
				<td><input onclick="mogulus_start_on_init_check((document.getElementById('mogulus_start_on_init_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="mogulus_start_on_init_check_{$cloneId}" type="checkbox" {if $startOnInit}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder"/></td>
			</tr>
		</tbody>
	</table>
</div>

{include file="content_objects/edit_mode_buttons.tpl"}
