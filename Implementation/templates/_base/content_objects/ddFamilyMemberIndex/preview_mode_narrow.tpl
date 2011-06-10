<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}

<!-- section -->
    <div class="prInner"> {foreach from=$globalCategories item=main}
{foreach from=$main item=level1}
{foreach from=$level1.categories item=cat1}
<!-- Hierarchy List begin -->   
        <h2 class="prWithoutInnerBottom">{$cat1.name|escape:html}</h2>
    {if $cat1.categories}
    
    {foreach name='fCatLevel2' from=$cat1.categories item=cat2}
        <ul class="">
        <li>
                <h3>{$cat2.name|escape:html}</h3>
        {foreach from=$cat2.categories item=cat3}
            <ul>
                    <li class="prInnerLeft">
                        <h4>{$cat3.name|escape:html}</h4>
        </li>
                <ul>
                {foreach from=$cat3.groups item=group4 name=grname}
                {if $smarty.foreach.grname.iteration <= $display_number_in_each_region}
                        <li class="prInnerLeft"><a href="{$group4.group->getGroupPath('summary')}">{$group4.name|escape:html} ({$group4.group->getMembers()->getCount()})</a></li>
                {/if}
                {/foreach}
                </ul>
            </ul>
        {/foreach}
        <ul>
        {foreach from=$cat2.groups item=group3 name=grname}
        {if $smarty.foreach.grname.iteration <= $display_number_in_each_region}
                    <li> <a href="{$group3.group->getGroupPath('summary')}">{$group3.name|escape:html} ({$group3.group->getMembers()->getCount()})</a> </li>
        {/if}
        {/foreach}
        </ul>
        </li>
    </ul>
    {/foreach}
    
    {/if}
    
    {if $cat1.groups}
        <ul class="">
        <li>
            <ul>
            {foreach name='fGroupLevel2' from=$cat1.groups item=group2}
            {if $smarty.foreach.fGroupLevel2.iteration <= $display_number_in_each_region}
                <li><a href="{$group2.group->getGroupPath('summary')}">{$group2.name|escape:html} ({$group2.group->getMembers()->getCount()})</a></li>
            {/if}
            {/foreach}
            </ul>
        </li>
    </ul>
    {/if}
<!-- Hierarchy List end -->
{/foreach}
{/foreach}
        {/foreach}</div>
<!-- / section --> </div>
