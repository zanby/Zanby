{tab template="admin_subtabs" active='import_group'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importgroups/" name="import_group"}{t}Import groups{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
    <h2>{t}Import members list report:{/t}</h2>
	<div class="prText2">{t}{tparam value=$rec_total}%s records processed.{/t}</div>
	<div class="prText2 prIndentBottom">{t}{tparam value=$rec_total}%s errors - groups were not added:{/t}</div>
	<!-- result begin -->
	{$paging}
	<table class="prResult" cellpadding="0" cellspacing="0" border="0">
		<col width="5%" />
		<col width="40%" />
		<col width="15%" />
		<col width="40%" />
		<tr>
			<th>{t}Row{/t} </th>
			<th>{t}Value{/t} </th>
			<th>{t}Field{/t} </th>
			<th>{t}Error {/t}</th>
		</tr>
		{foreach item=e from=$allerr}
		<tr>
			<td>{$e.row} </td>
			<td>{$e.value} </td>
			<td>{$e.field} </td>
			<td>{$e.err} </td>
		</tr>
		{/foreach}
	</table>
	<!-- result end -->
	<table cellpadding="0" cellspacing="0" border="0" class="prResult">
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
			<td class="prTLeft">{t var="in_button"}Save warnings to file{/t}{linkbutton name=$in_button link=$path_warn}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTLeft prText2" colspan="2">{t}{tparam value=$rec_succ}%s records successfully  passed.{/t}</td>
		</tr>
		<tr>
			<td class="prTLeft" colspan="2">{t var="in_button_2"}Get result CSV file{/t}{linkbutton name=$in_button_2 link=$path_res} &nbsp
				{t}contain generated passwords and default values for empty fields{/t}</td>
		</tr>
	</table>
	{form from=$form id="iuForm" name="iuForm"}
	<table cellpadding="0" cellspacing="0" border="0" class="prResult">
		<col width="30%" />
		<col width="70%" />
		<tr>
			<td colspan="2"><h3>{t}{tparam value=$rec_succ}Create groups (%s):{/t}</h3></td>
		</tr>
		<tr>
			<td class="prTLeft" colspan="2"> {t}They will be joined to Group Family(es):{/t} {$group_names}
				<input name="group_names" type="hidden" value="{$group_names}">
				</input>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">{if $rec_succ>0}{t var="in_submit"}Accept{/t}{form_submit name="form_upload" value=$in_submit}{/if}</td>
			<td class="prTLeft">{t}or{/t} <a href="$admin->getAdminPath('importgroups/')">{t}Cancel{/t}</a></td>
		</tr>
	</table>
	{/form} </div>
<!-- result end -->
