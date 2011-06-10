<table style="background-color:white;width:100%;">
    <tr>
        <td align="left">
            <a href="/{$LOCALE}/adminarea/translate/">Main</a> / <a href="/{$LOCALE}/adminarea/translate-content-manager/">Manage Languages List</a> | Add Language
        </td>
    </tr>
</table>

{form from=$form}
<table style="background-color:white;width:100%;">
    <tr>
        <td align="left" colspan="2">{form_errors_summary}</td>
    </tr>
    <tr>
        <td align="left"><span style="color:red;">*</span>Locale: </td>
        <td align="left">{form_select options=$options selected=$form_values.abbreviation name="abbreviation"}</td>
    </tr>
    <tr>
        <td align="left"><span style="color:red;">*</span>Display Name: </td>
        <td align="left">{form_text value=$form_values.name name="name"}</td>
    </tr>
    <tr>
        <td colspan="2" align="left">{form_checkbox value="1" name="published"} <span style="font-weight:bold;">Published</span></td>
    </tr>
    <tr>
        <td colspan="2" align="left">{form_submit value="Save"} or <a href="/{$LOCALE}/adminarea/translate-content-manager/">Cancel</a></td>
    </tr>
</table>
{/form}