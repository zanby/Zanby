{if $mode == 'app'}
	{form from=$form id="tdForm" name="tdForm"}
	<table  cellspacing="0" cellpadding="0" class="prForm">
		<col width="10%" />
		<col width="10%" />
		<col width="60%" />
		<col width="20%" />
		<tr>
			<td>&nbsp;</td>
			<td class="prTRight"><label>{t}Template Key:{/t}</label></td>
			<td class="prTLeft"> {form_text name="template_key" value=$template->templateKey|escape:"html" style="width:100%;"} </td>
			<td>&nbsp;</td>
		<tr>
			<td>&nbsp;</td>
			<td class="prTRight"><label>{t}Description:{/t}</label></td>
			<td class="prTLeft"> {form_text name="description" value=$template->description|escape:"html" style="width:100%;"} </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTRight"><label>{t}Content:{/t}</label></td>
			<td class="prTLeft"> {form_textarea name="content" value=$template->content style="width:100%;height:400px"} </td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div class="prIndentTop">{t var="in_submit"}Save{/t}{form_submit value=$in_submit}</div>
	{/form}
{else}
    {form from=$form id="tdForm" name="tdForm"}
    <table  cellspacing="0" cellpadding="0" class="prForm">
        <col width="10%" />
        <col width="10%" />
        <col width="60%" />
        <col width="20%" />
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}Template UID:{/t}</label></td>
            <td class="prTLeft">{$template.uid}</td>
            <td>&nbsp;</td>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}Description:{/t}</label></td>
            <td class="prTLeft"> {form_text name="description" value=$template.description|escape:"html" style="width:100%;"} </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}Subject:{/t}</label></td>
            <td class="prTLeft"> {form_text name="subject" value=$localization.subject style="width:100%;"} </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}PLAIN Part:{/t}</label></td>
            <td class="prTLeft"> {form_textarea name="body_plain" value=$localization.body_plain style="width:100%;height:200px"} </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}HTML Part:{/t}</label></td>
            <td class="prTLeft"> {form_textarea name="body_html" value=$localization.body_html style="width:100%;height:200px"} </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}PMB Subject:{/t}</label></td>
            <td class="prTLeft"> {form_text name="pmb_subject" value=$localization.pmb_subject style="width:100%;"} </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="prTRight"><label>{t}PMB Message:{/t}</label></td>
            <td class="prTLeft"> {form_textarea name="pmb_message" value=$localization.pmb_message style="width:100%;height:200px"} </td>
            <td>&nbsp;</td>
        </tr>        
    </table>
    <div class="prIndentTop">{t var="in_submit"}Save{/t}{form_submit value=$in_submit}</div>
    {/form}
{/if}
