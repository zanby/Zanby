{assign var="GroupName" value=$CurrentGroup->getName()}
    <table cellpadding="4" cellspacing="0" width="100%" border="0">
        <tr>
            <td>{t}Group Tags - Click to see other groups with similar tags{/t}</td>
        </tr>
        {if $tags.group}
        <tr>
            <td>
                {foreach from=$tags.group item=t}<a href="#" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td>{t}Group Member Tags - Click to see members of this group who use the selected tags{/t}</td>
        </tr>
        {if $tags.members}
        <tr>
            <td>
                {foreach from=$tags.members item=t}<a href="http://users.{$BASE_HTTP_HOST}/{$LOCALE}/search/new/1/tag/{$t->id}/" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td>{t}Event Tags - Click to see this group's events described with the selected tags{/t}</td>
        </tr>
        {if $tags.events}
        <tr>
            <td>
                {foreach from=$tags.events item=t}<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/tags/view/tag/{$t->id}" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td>{t}Photo Tags - Click to see this group's photos described with the selected tags{/t}</td>
        </tr>
        {if $tags.photos}
        <tr>
            <td>
                {foreach from=$tags.photos item=t}<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/tags/view/tag/{$t->id}" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td>{t}List Tags - Click to see this group's lists described with the selected tags{/t}</td>
        </tr>
        {if $tags.lists}
        <tr>
            <td>
                {foreach from=$tags.lists item=t}<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/tags/view/tag/{$t->id}" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td>{t}Document Tags - Click to see this group's documents described with the selected tags{/t}</td>
        </tr>
        {if $tags.documents}
        <tr>
            <td>
                {foreach from=$tags.documents item=t}<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/tags/view/tag/{$t->id}" class="tag">{$t->name}</a> {/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
    </table>
