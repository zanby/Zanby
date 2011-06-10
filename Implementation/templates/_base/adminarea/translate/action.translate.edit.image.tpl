<script type="text/javascript">
//<![CDATA[
{literal}

    function langSelected(select) {
        var lang=select.value;
        
        window.location="/{/literal}{$LOCALE}/adminarea/translate-edit-image/lang/{if $page}page/{$page|escape:'url'}/{/if}" + lang + "/?file={$imageInfo.file|escape:'url'}&dir={$imageInfo.dir|escape:'url'}{literal}";
    }
{/literal}
//]]>   
</script>

{form from=$form enctype="multipart/form-data"}

<table style="background-color:white;width:100%;">
    <tr>
        <td><a href="/{$LOCALE}/adminarea/translate/">Main</a> / <a href="/{$LOCALE}/adminarea/translate-image/lang/{$imageInfo.lang}">Images</a> / Edit</td>
        <td>
            {form_select options=$langList selected=$imageInfo.lang id="lang-select" name="lang-select" style="width:200px;" onchange="langSelected(this)"}
        </td>
    </tr>
</table>

<div style="background-color: #FFF; text-align: left;">
<div>{form_errors_summary}</div>

<strong>Original:</strong> {$imageInfo.file|escape:'html'}<br />
<img src="{$imageInfo.untranslateImage|escape:html}" alt="" /> <br />
<strong>Translation:</strong>
{if $imageInfo.translateImage}
<br />
{* Add get parameter to prevent image caching *}
<img src="{$imageInfo.translateImage|escape:html}?nocache={$rand}" alt="" /> <br />
{else}
&quot;not translated&quot;<br />
{/if}
{form_file name="translateImage"}

<div>{form_submit value="Save"} or <a href="/{$LOCALE}/adminarea/translate-image/lang/{$imageInfo.lang}/{if $page}page/{$page|escape:'url'}/{/if}">Cancel</a></div>
</div>
{/form}