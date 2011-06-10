<div class="prDropBoxInner"> {form from=$form id="udForm"}
    {form_errors_summary}
    <div class="prText5 prTLeft">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</div>
    <table class="prFullWidth">
        <col width="50%" />
        <tr>
            <td align="left"><table width="50%" cellpadding="0" cellspacing="0" class="prForm">
                    <col width="30%" />
                    <col width="70%" />
                    <tr>
                        <td class="prTRight"><span class="prMarkRequired">*</span>
                            <label>{t}RSS items count:{/t}</label></td>
                        <td class="prTLeft">{form_text name="limit" value=$limit|escape:"html"}</td>
                    </tr>
                    <tr>
                        <td class="prTRight"><span class="prMarkRequired">*</span>
                            <label>{t}Max decription length:{/t}</label></td>
                        <td class="prTLeft">{form_text name="descMaxLength" value=$descMaxLength|escape:"html"}</td>
                    </tr>
                </table></td>
        </tr>
    </table>
    <div class="prTCenter prIndentTop">{t var="in_submit"}Save{/t}{form_submit name="form_save" value=$in_submit}</div>
    {/form} </div>
