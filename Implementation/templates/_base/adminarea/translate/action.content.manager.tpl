<table style="background-color:white;width:100%;">
    <col width="80%" />
    <col width="20%" />
    <tr>
        <td align="left">
            <a href="/{$LOCALE}/adminarea/translate/">Main</a> / Manage Languages List
        </td>
        <td align="right"><input type="button" value="+ new language" onclick="location.href = '/{$LOCALE}/adminarea/translate-add-language/'" /></td>
    </tr>
</table>
<table style="background-color:white;width:100%;">
    <tr>
        <th>Published</th>
        <th>Name</th>
        <th>Display Name</th>
        <th>Order</th>
        <th>Actions</th>
    </tr>
    {foreach from=$localesArray item=locale key=k name="list"}
        <tr>
            <td>{if isset($locale.published) && $locale.published==1}+{else}-{/if}</td>
            <td>{$locale.untranslateName|escape}</td>
            <td>{$locale.name|escape}</td>
            <td>{if $smarty.foreach.list.first}<a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/down/">Move Down</a>
                {elseif $smarty.foreach.list.last}<a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/up/">Move Up</a>
                {else}<a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/up/">Move Up</a> | <a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/down/">Move Down</a>
                {/if}
            </td>
            <td>
                <a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/edit/">Edit</a> | {if isset($locale.published) && $locale.published==1}<a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/unpublish/">Unpublish</a>{else}<a href="/{$LOCALE}/adminarea/translate-content-manager/locale/{$locale.value}/action/publish/">Publish</a>{/if}
            </td>
        </tr>
    {/foreach}
</table>