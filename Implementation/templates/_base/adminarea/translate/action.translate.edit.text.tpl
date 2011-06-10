<script type="text/javascript">
//<![CDATA[
{literal}
    function copyOriginal() {
        original = document.getElementById('originalMessage');
        if (original) {
            originalText = original.firstChild.nodeValue;
            document.getElementById('translateMessage').value = originalText;
        }
    }
    
    function langSelected(select) {
        var lang=select.value;
        
        window.location="{/literal}/{$LOCALE}/adminarea/translate-edit-text/section/{$section}/derection/" + lang + "/{if $page}page/{$page|escape:'url'}/{/if}?file={$translate_file|escape:'url'}&tuid={$tuid|escape:'url'}{literal}";
    }
{/literal}
//]]>   
</script>

{form from=$form}
<table style="background-color:white;width:100%;">
    <tr>
        <td><a href="/{$LOCALE}/adminarea/translate/">Main</a> / <a href="/{$LOCALE}/adminarea/translate-text/section/{$section}/derection/{$derection}/">{if $section=="system"}System Messages{/if}{if $section=="core"}Core Messages{/if}{if $section=="plugins"}Plugin Messages{/if}{if $section=="templates"}Templates{/if}</a> / Edit</td>
        <td>
            {form_select options=$langList selected=$derection id="lang-select" name="lang-select" style="width:200px;" onchange="langSelected(this)"}
        </td>
    </tr>
</table>

<div style="background-color: #FFF;">
<div>{form_errors_summary}</div>

<div>
<span>Original:</span><br />
<span id="originalMessage">{$originalMessage|escape:'html'}</span>
</div>

<div>
<label for="translateMessage">Translation:</label> <a href="javascript:void(0)" onclick="copyOriginal(); return false;">Copy Original</a><br />
{form_textarea name=translateMessage id=translateMessage value=$form_values.translateMessage}
</div>

<div>{form_checkbox name=publish id=publish value=1 checked=$form_values.publish} <label for="publish">Published</label></div>

<div>{form_submit value="Save"} or <a href="/{$LOCALE}/adminarea/translate-text/section/{$section}/derection/{$derection}/{if $page}page/{$page|escape:'url'}/{/if}">Cancel</a></div>

{/form}
</div>