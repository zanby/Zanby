{include file="content_objects/edit_mode_settings_wide.tpl"}
{include file="content_objects/headline_block_wide.tpl"}


<div class="prInnerSmall">
	<input type="hidden" name="newindex" id="newindex" value="{$cloneId}">
	<input type="hidden" name="rss_rendered_{$cloneId}" id="rss_rendered_{$cloneId}" value="0" />
	<table cellspacing="0" cellpadding="0" class="prForm">
		<col width="15%" />
		<col width="85%" />
		<tbody>
			<tr>
				<td class="prTRight"><strong>{t}Title:{/t}</strong></td>
				<td><div class="ieInputFix"><input type="text" name="rss_title" id="rss_title_{$cloneId}" value="{$rss_title|escape:'html'}" onchange="changeRSSTitle('{$cloneId}', this.value);" /></div></td>
			</tr>
			<tr>
				<td class="prTRight"><strong>{t}URL:{/t}</strong></td>
				<td><div class="ieInputFix"><input type="text" name="rss_url" id="rss_url_{$cloneId}" value="{$rss_url|escape:'html'}" onchange="changeRSSUrl('{$cloneId}', this.value);"/></div></td>
			</tr>
			<tr>
				<td class="prTRight"><strong>{t}Display:{/t}</strong></td>
				<td>
					<select id="rss_view_{$cloneId}" onchange="changeRSSView('{$cloneId}', this.options[this.selectedIndex].value);">
						<option value="0" {if $rss_view==0}selected="selected"{/if}>{t}Headlines Only{/t}</option>
						<option value="1" {if $rss_view==1}selected="selected"{/if}>{t}Headlines, Text and Media{/t}</option>
						<option value="2" {if $rss_view==2}selected="selected"{/if}>{t}Headlines, Text without Media{/t}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="prTRight"><strong>{t}Show:{/t}</strong></td>
				<td>
					<select id="rss_max_lines_{$cloneId}" onchange="changeRSSMaxLines('{$cloneId}', this.options[this.selectedIndex].value);">
						<option value="1" {if $rss_max_lines==1}selected="selected"{/if}>{t}Show 1 item{/t}</option>
						<option value="2" {if $rss_max_lines==2}selected="selected"{/if}>{t}Show 2 items{/t}</option>
						<option value="3" {if $rss_max_lines==3}selected="selected"{/if}>{t}Show 3 items{/t}</option>
						<option value="4" {if $rss_max_lines==4}selected="selected"{/if}>{t}Show 4 items{/t}</option>
						<option value="5" {if $rss_max_lines==5}selected="selected"{/if}>{t}Show 5 items{/t}</option>
						<option value="6" {if $rss_max_lines==6}selected="selected"{/if}>{t}Show 6 items{/t}</option>
						<option value="7" {if $rss_max_lines==7}selected="selected"{/if}>{t}Show 7 items{/t}</option>
						<option value="8" {if $rss_max_lines==8}selected="selected"{/if}>{t}Show 8 items{/t}</option>
						<option value="9" {if $rss_max_lines==9}selected="selected"{/if}>{t}Show 9 items{/t}</option>
						<option value="10" {if $rss_max_lines==10}selected="selected"{/if}>{t}Show 10 items{/t}</option>
						<option value="20" {if $rss_max_lines==20}selected="selected"{/if}>{t}Show 20 items{/t}</option>
						<option value="30" {if $rss_max_lines==30}selected="selected"{/if}>{t}Show 30 items{/t}</option>
						<option value="40" {if $rss_max_lines==40}selected="selected"{/if}>{t}Show 40 items{/t}</option>
						<option value="50" {if $rss_max_lines==50}selected="selected"{/if}>{t}Show 50 items{/t}</option>
					  </select>
				</td>
			</tr>
            <tr>
                <td class="prTRight"><strong>{t}Headline Font:{/t}</strong></td>
                <td>
                    <select id="rss_header_font_{$cloneId}" onchange="changeRSSHeaderFont('{$cloneId}', this.options[this.selectedIndex].value);">
                        <option value="" {if $rss_description_font==''}selected="selected"{/if}>{t}Original{/t}</option>
                        <option value="Times New Roman" {if $rss_description_font=="Times New Roman"}selected="selected"{/if}>{t}Times New Roman{/t}</option>
                        <option value="Arial" {if $rss_description_font=="Arial"}selected="selected"{/if}>{t}Arial{/t}</option>
                        <option value="Tahoma" {if $rss_description_font=="Tahoma"}selected="selected"{/if}>{t}Tahoma{/t}</option>
                        <option value="Verdana" {if $rss_description_font=="Verdana"}selected="selected"{/if}>{t}Verdana{/t}</option>
                        <option value="Lucida Console" {if $rss_description_font=="Lucida Console"}selected="selected"{/if}>{t}Lucida Console{/t}</option>
                    </select>                
                </td>
            </tr>
            <tr>
                <td class="prTRight"><strong>{t}Headline Font size:{/t}</strong></td>
                <td>
                    <select id="rss_header_font_size_{$cloneId}" onchange="changeRSSHeaderFontSize('{$cloneId}', this.options[this.selectedIndex].value);">
                        <option value="" {if $rss_header_font_size==''}selected="selected"{/if}>{t}Original{/t}</option>
                        <option value="8" {if $rss_header_font_size=='8'}selected="selected"{/if}>{t}8{/t}</option>
                        <option value="10" {if $rss_header_font_size=='10'}selected="selected"{/if}>{t}10{/t}</option>
                        <option value="12" {if $rss_header_font_size=='12'}selected="selected"{/if}>{t}12{/t}</option>
                        <option value="14" {if $rss_header_font_size=='14'}selected="selected"{/if}>{t}14{/t}</option>
                        <option value="18" {if $rss_header_font_size=='18'}selected="selected"{/if}>{t}18{/t}</option>
                        <option value="24" {if $rss_header_font_size=='24'}selected="selected"{/if}>{t}24{/t}</option>
                        <option value="36" {if $rss_header_font_size=='36'}selected="selected"{/if}>{t}36{/t}</option>
                    </select>                
                </td>
            </tr>
            <tr>
                <td class="prTRight"><strong>{t}Headline Color:{/t}</strong></td>
                <td>
                    <div class="ieInputFix">
                        <input type="text" name="rss_header_color" id="rss_header_color_{$cloneId}" value="{$rss_header_color|escape:'html'}" onchange="changeRSSTitle('{$cloneId}', this.value);" />
                        <a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('1', '', getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'setFormValues(\'rss_header_color_{$cloneId}\', \'#\')');">&nbsp;</a>
                    </div>               
                </td>
            </tr>
            <tr>
                <td class="prTRight"><strong>{t}Description font:{/t}</strong></td>
                <td>
                    <select id="rss_description_font_{$cloneId}">
                        <option value="" {if $rss_description_font==''}selected="selected"{/if}>{t}Original{/t}</option>
                        <option value="Times New Roman" {if $rss_description_font=="Times New Roman"}selected="selected"{/if}>{t}Times New Roman{/t}</option>
                        <option value="Arial" {if $rss_description_font=="Arial"}selected="selected"{/if}>{t}Arial{/t}</option>
                        <option value="Tahoma" {if $rss_description_font=="Tahoma"}selected="selected"{/if}>{t}Tahoma{/t}</option>
                        <option value="Verdana" {if $rss_description_font=="Verdana"}selected="selected"{/if}>{t}Verdana{/t}</option>
                        <option value="Lucida Console" {if $rss_description_font=="Lucida Console"}selected="selected"{/if}>{t}Lucida Console{/t}</option>
                    </select>                
                </td>
            </tr>
            <tr>
                <td class="prTRight"><strong>{t}Description Color:{/t}</strong></td>
                <td>
                    <div class="ieInputFix">
                        <input type="text" name="rss_description_color" id="rss_description_color_{$cloneId}" value="{$rss_description_color|escape:'html'}" />
                        <a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('1', '', getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'setFormValues(\'rss_description_color_{$cloneId}\', \'#\')');">&nbsp;</a>
                    </div>               
                </td>
            </tr>
            <tr>
                <td class="prTRight"><strong>{t}Description Font size:{/t}</strong></td>
                <td>
                    <select id="rss_description_font_size_{$cloneId}">
                        <option value="" {if $rss_description_font_size==''}selected="selected"{/if}>{t}Original{/t}</option>
                        <option value="8" {if $rss_description_font_size=='8'}selected="selected"{/if}>{t}8{/t}</option>
                        <option value="10" {if $rss_description_font_size=='10'}selected="selected"{/if}>{t}10{/t}</option>
                        <option value="12" {if $rss_description_font_size=='12'}selected="selected"{/if}>{t}12{/t}</option>
                        <option value="14" {if $rss_description_font_size=='14'}selected="selected"{/if}>{t}14{/t}</option>
                        <option value="18" {if $rss_description_font_size=='18'}selected="selected"{/if}>{t}18{/t}</option>
                        <option value="24" {if $rss_description_font_size=='24'}selected="selected"{/if}>{t}24{/t}</option>
                        <option value="36" {if $rss_description_font_size=='36'}selected="selected"{/if}>{t}36{/t}</option>
                    </select>                
                </td>
            </tr>

		</tbody>
	</table>
</div>
{include file="content_objects/edit_mode_buttons.tpl"}
