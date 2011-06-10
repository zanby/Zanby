
<table style="background-color:white;width:100%;">
    <tr>
        <td><a href="/{$LOCALE}/adminarea/translate/">Main</a> / {if $section=="system"}System Messages{/if}{if $section=="core"}Core Messages{/if}{if $section=="plugins"}Plugin Messages{/if}{if $section=="templates"}Templates{/if}</td>
        <td>
            {form from=$formDerection}
            {/form}
        </td>
    </tr>
</table>

{form from=$formFilter}
<table style="background-color:white;width:100%;">
    <tr>
        <th>Keyword Filter</th>
        <th>Category Filter</th>
        <th>Translation Availability</th>
        <th>&nbsp;</th>
    </tr>
    <tr>
        <td>{form_text value="" name=""}</td>
        <td>{form_text value="" name=""}</td>
        <td>{form_text value="" name=""}</td>
        <td>{form_submit value="Reset"}</td>
    </tr>
</table>
{/form}

<div>{$paging}</div>

<table style="background-color:white;width:100%;">
    <tr>
        <th>Published</th>
        <th>Original</th>
        <th>Translation</th>
        <th>Last modified</th>
        <th>Actions</th>
    </tr>
    {foreach from=$toScreen item=row key=k}
    {assign var=lang value=$row.langs.$derection}
    {if $lang}
    <tr>
        <td>{if $lang.publish}yes{else}no{/if}</td>
        <td>{$row.langs.en.message|escape:'html'}</td>
        <td>{$lang.message|escape:'html'}</td>
        <td>{if $lang.changedate}{$lang.changedate->toString('MM/dd/yyyy h:mm a')}{/if}</td>
        <td><a href="/{$LOCALE}/adminarea/translate-edit-text/section/{$section}/derection/{$derection}/page/{$page}/?file={$row.file|escape:'url'}&amp;tuid={$row.tuid|escape:'url'}">Edit</a> | {if $lang.publish}<a href="/{$LOCALE}/adminarea/translate-unpublish-text/section/{$section}/derection/{$derection}/page/{$page}/?file={$row.file|escape:'url'}&amp;tuid={$row.tuid|escape:'url'}">Unpublish</a>{else}<a href="/{$LOCALE}/adminarea/translate-publish-text/section/{$section}/derection/{$derection}/page/{$page}/?file={$row.file|escape:'url'}&amp;tuid={$row.tuid|escape:'url'}">Publish</a>{/if}</td>
    </tr>
    {else}
    <tr>
        <td>yes</td>
        <td>{$row.langs.en.message|escape:'html'}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><a href="/{$LOCALE}/adminarea/translate-edit-text/section/{$section}/derection/{$derection}/page/{$page}/?file={$row.file|escape:'url'}&amp;tuid={$row.tuid|escape:'url'}">Edit</a> | <a href="/{$LOCALE}/adminarea/translate-unpublish-text/section/{$section}/derection/{$derection}/page/{$page}/?file={$row.file|escape:'url'}&amp;tuid={$row.tuid|escape:'url'}">Unpublish</a></td>
    </tr>
    {/if}
    {/foreach}
</table>

<div>{$paging}</div>