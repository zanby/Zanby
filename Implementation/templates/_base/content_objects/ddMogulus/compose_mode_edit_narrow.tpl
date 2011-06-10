{include file="content_objects/edit_mode_settings_narrow.tpl"}

<div class="prIndent">
	<table cellspacing="0" cellpadding="0" class="prForm">
	    <col width="15%" />
        <col width="85%" />
		<tbody>  
			<tr>
				<td colspan="2">
					<strong>{t}Channel:{/t}</strong><br />
					<input type="text" name="channel" id="mogulus_channel_{$cloneId}" style="width: 95%" value="{$channel|escape:'html'}" onchange="changeMogulusChannel('{$cloneId}', this.value);"/>
				</td>
			</tr>
			<tr>
				<td><strong>{t}Autoplay:{/t}</strong></td>
				<td><input onclick="mogulus_start_on_init_check((document.getElementById('mogulus_start_on_init_check_{$cloneId}').checked)?1:0,'{$cloneId}');" id="mogulus_start_on_init_check_{$cloneId}" type="checkbox" {if $startOnInit}checked="checked"{/if} value="1" class="prAutoWidth prNoBorder" /></td>
			</tr>
		</tbody>
	</table>
</div>

{include file="content_objects/edit_mode_buttons.tpl"}
