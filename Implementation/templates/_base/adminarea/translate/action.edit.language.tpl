<table style="background-color:white;width:100%;">
    <tr>
        <td align="left">
            <a href="/{$LOCALE}/adminarea/translate/">Main</a> / <a href="/{$LOCALE}/adminarea/translate-content-manager/">Manage Languages List</a> | Add Language
        </td>
    </tr>
</table>

{form from=$form}
{form_hidden value=$form_values.abbreviation name="abbreviation"}
<table style="background-color:white;width:100%;">
    <tr>
        <td align="left" colspan="2">{form_errors_summary}</td>
    </tr>
    <tr>
        <td align="left">Locale: </td>
        <td align="left">{$localeName}</td>
    </tr>
    <tr>
        <td align="left"><span style="color:red;">*</span>Display Name: </td>
        <td align="left">{form_text value=$form_values.name name="name"}<span style="font-size:small;">Example: English, Русский</span></td>
    </tr>
    <tr>
        <td colspan="2" align="left">{form_checkbox value="1" name="published" checked=$form_values.published} <span style="font-weight:bold;">Published</span></td>
    </tr>
    <tr>
        <td colspan="2" align="left">{form_submit value="Save"} or <a href="/{$LOCALE}/adminarea/translate-content-manager/">Cancel</a></td>
    </tr>
</table>
{/form}