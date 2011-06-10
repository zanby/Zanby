<div class="prDropBoxInner"> {form id="dForm" name="dForm" from=$form}
	<table width="50%" cellpadding="0" cellspacing="0" border="0" class="prForm">
		{if $deleted>0}
		<tr>
			<td colspan="2"><h2>{t}{tparam value=$rec_count}%s members deleted successfilly{/t}</h2></td>
		</tr>
		<tr>
			<td> {t var="in_button"}Ok{/t}{linkbutton name=$in_button link=$cancel_path}<br />
			</td>
			<td></td>
		</tr>
		{else}
		<tr>
			<td colspan="2"><h2>{t}{tparam value=$rec_count}%s members will be deleted{/t}</h2></td>
		</tr>
		<tr>
			<td class="prTRight"> {t var="in_submit"}Delete{/t}{form_submit name="form_del" value=$in_submit } </td>
			<td class="prTLeft"> {t}or{/t} <a href="$cancel_path">{t}Cancel{/t}</a> </td>
		</tr>
		{/if}
	</table>
	{/form} </div>