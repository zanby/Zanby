{tab template="admin_subtabs" active='import_members'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importmembers/" name="import_members"}{t}Import members{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
    <h2>{t}Import members list report:{/t}</h2>
	<div class="prText2">{t}{tparam value=$rec_total}%s records processed.{/t}</div>
	<div class="prText2 prIndentBottom">{t}{tparam value=$rec_err}%s errors:{/t}</div>
	<!-- result begin -->
	{$paging}
	<table class="prResult" cellpadding="0" cellspacing="0" border="0">
		<col width="5%" />
		<col width="40%" />
		<col width="15%" />
		<col width="40%" />
		<tr>
			<th style="text-align: center;">{t}Row {/t}</th>
			<th style="text-align: center;">{t}Value{/t} </th>
			<th style="text-align: center;">{t}Field{/t} </th>
			<th style="text-align: center;">{t}Error {/t}</th>
		</tr>
		{foreach item=e from=$allerr}
		<tr>
			<td style="text-align: center;">{$e.row} </td>
			<td>{$e.value} </td>
			<td style="text-align: center;">{$e.field} </td>
			<td>{$e.err} </td>
		</tr>
		{/foreach}
	</table>
	<!-- result end -->
	<table class="prResult" cellpadding="0" cellspacing="0" border="0">
		<col width="20%" />
		<col width="80%" />
		<tr>
			<td class="prTLeft">{t var="in_button"}Save errors to file{/t}{linkbutton name=$in_button link=$path_err}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft">{t var="in_button_2"}Get rejected strings{/t}{linkbutton name=$in_button_2 link=$path_rej}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft prText2" colspan="2">{t}{tparam value=$rec_warn}%s warnings (empty fields will be set default).{/t}</td>
		</tr>
		{if $is_show_warn}
		{foreach item=warn from=$allwarn}
		<tr>
			<td class="prTLeft">{$warn} </td>
			<td>&nbsp;</td>
		</tr>
		{/foreach}
		{/if}
		<tr>
			<td class="prTLeft">{t var="in_button_3"}Save warnings to file{/t}{linkbutton name=$in_button_3 link=$path_warn}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft prText2" colspan="2">{t}{tparam value=$rec_succ}%s records successfully  passed.{/t}</td>
		</tr>
	</table>
	{form from=$form id="iuForm" name="iuForm"}
	<table cellpadding="0" cellspacing="0" border="0">
		<col width="30%" />
		<col width="70%" />
		<tr>
			<td colspan="2"><h3>{t}Create members accounts{/t} ({$rec_create}):</h3></td>
		</tr>
		<tr>
			<td class="prTLeft" colspan="2">{t var="in_button_4"}Result CSV file{/t}{linkbutton name=$in_button_4 link=$path_res } &nbsp
				{t}contain generated passwords and default values for empty fields{/t}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft"> {form_radio id="h1" name="activate_now" value="0" checked="0"}
				<label for="h1">{t}Activate all added members Immediately{/t}</label></td>
			<td class="prTLeft">{form_checkbox is="send_notifications" name="send_notifications" value=1 checked=true}
				<label for="send_notifications">{t}Send notifications to members{/t}</label>
			</td>
		</tr>
		<tr>
			<td class="prTLeft"> {form_radio id="h2" name="activate_now" value="1" checked=false}
				<label for="h2">{t}Send activation request to members{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><h3>{t}{tparam value=$rec_succ}And join members (%s) to groups {/t}</h3></td>
		</tr>
		<tr>
			<td class="prTLeft">{t var="in_button_5"}Result file (matrix){/t}{linkbutton name=$in_button_5 link=$path_mx }</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft" colspan="2">{t}{tparam value=$rec_succ} Also all %s members will be joined to group(s):{/t} {$group_names}
				<input name="group_names" type="hidden" value="{$group_names}">
				</input>
				<input name="tmname" type="hidden" value="{$tmname}">
				</input>
			</td>
		</tr>
		<tr>
			<td class="prTRight">{t var="in_submit"}Accept{/t}{form_submit name="form_upload" value=$in_submit}</td>
			<td class="prTLeft">{t}or{/t} <a href="$admin->getAdminPath('importmembers/')">{t}Cancel{/t}</a></td>
		</tr>
	</table>
	{/form} </div>
