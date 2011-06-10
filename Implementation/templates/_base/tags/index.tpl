    <table cellpadding="4" cellspacing="0" width="100%" border="0">
        <tr>
            <td><h4>{t}Groups{/t}</h4></td>
        </tr>
        {if $entities.groups.items}
        <tr>
            <td>
                {foreach from=$entities.groups.items item=i}<li><a href="{$i->getGroupPath('summary')}">{$i->name|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Groups Photos{/t}</h4></td>
        </tr>
        {if $entities.groups.photos}
        <tr>
            <td>
                {foreach from=$entities.groups.photos item=i}<li><a href="{$i->getGallery()->getOwner()->getGroupPath('galleryView')}id/{$i->id}/">{$i->title|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Groups Lists{/t}</h4></td>
        </tr>
        {if $entities.groups.lists}
        <tr>
            <td>
                {foreach from=$entities.groups.lists item=i}<li><a href="#">{$i->id}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Groups Events{/t}</h4></td>
        </tr>
        {if $entities.groups.events}
        <tr>
            <td>
                {foreach from=$entities.groups.events item=i}<li><a href="#">{$i->title|escape:html}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Groups Documents{/t}</h4></td>
        </tr>
        {if $entities.groups.documents}
        <tr>
            <td>
                {foreach from=$entities.groups.documents item=i}<li><a href="{$i->getOwner()->getGroupPath('documents')}">{$i->originalName|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Members{/t}</h4></td>
        </tr>
        {if $entities.members.items}
        <tr>
            <td>
                {foreach from=$entities.members.items item=i}<li><a href="{$i->getUserPath('profile')}">{$i->getLogin()|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Members Photos{/t}</h4></td>
        </tr>
        {if $entities.members.photos}
        <tr>
            <td>
                {foreach from=$entities.members.photos item=i}<li><a href="{$i->getGallery()->getOwner()->getUserPath('galleryView')}id/{$i->id}/">{$i->title|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Members Lists{/t}</h4></td>
        </tr>
        {if $entities.members.lists}
        <tr>
            <td>
                {foreach from=$entities.members.lists item=i}<li><a href="#">{$i->id}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Members Events{/t}</h4></td>
        </tr>
        {if $entities.members.events}
        <tr>
            <td>
                {foreach from=$entities.members.events item=i}<li><a href="#">{$i->title|escape:html}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
        <tr>
            <td><h4>{t}Members Documents{/t}</h4></td>
        </tr>
        {if $entities.members.documents}
        <tr>
            <td>
                {foreach from=$entities.members.documents item=i}<li><a href="{$i->getOwner()->getUserPath('documents')}">{$i->originalName|escape:"html"}</a></li>{/foreach}
            </td>
        </tr>
        {else}
        <tr>
            <td>{t}No Tags{/t}</td>
        </tr>
        {/if}
    </table>