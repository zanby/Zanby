<script type="text/javascript">
//<![CDATA[
{literal}

    function langSelected(select) {
        var lang=select.value;
        
        window.location="/{/literal}{$LOCALE}{literal}/adminarea/translate-image/lang/" + lang + "/";
    }
{/literal}
//]]>   
</script>


<table style="background-color:white;width:100%;">
    <tr>
        <td><a href="/{$LOCALE}/adminarea/translate/">Main</a> / Images</td>
        <td>
            {form from=$langForm}
                {form_select options=$langList selected=$lang id="lang-select" name="lang-select" style="width:200px;" onchange="langSelected(this)"}
            {/form}
        </td>
    </tr>
</table>

<table style="background-color:white;width:100%;">
    <tr>
        <th>Original</th>
        <th>Translation</th>
        <th>Last modified</th>
        <th>Actions</th>
    </tr>
    {foreach from=$toScreen item=row}
    <tr>
        <td><img src="{$row.untranslateImage}" alt="" /></td>
        {* Add get parameter to prevent image caching *}
        <td>{if $row.translateImage}<img src="{$row.translateImage}?nocache={$rand}" alt="" />{/if}</td>
        <td></td>
        <td><a href="/{$LOCALE}/adminarea/translate-edit-image/lang/{$lang}/page/{$page}/?file={$row.file|escape:'url'}&amp;dir={$row.dir|escape:'url'}">Edit</a></td>
    </tr>
    {/foreach}
</table>

<div>{$paging}</div>
