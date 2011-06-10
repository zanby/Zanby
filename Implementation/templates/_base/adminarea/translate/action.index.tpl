{form from=$form}
<table width="100%" style="background-color:white;">
    <col width="50%" />
    <col width="50%" />
    <tr>
        <td>{form_errors_summary}</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <th>Translate</th>
        <th>Configure</th>
    </tr>
    <tr>
        <td>
            Language:<br />
            {form_select options=$langList selected=$answer.derection name="derection" style="width:200px;"}
        </td>
        <td align="left">
            <a href="/{$LOCALE}/adminarea/translate-content-manager/">Manage Language List</a><br />
            <span style="font-size:small;">Allows user to create new languages? change order and etc.</span>
        </td>
    </tr>
    <tr>
        <td>
            Content elements:<br />
            {form_select options=$langNames selected=$answer.translatePart name="translatePart" style="width:200px;"}
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="right">
            {form_submit value="Apply"}
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
{/form}