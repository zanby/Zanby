<h2>{t}Join Family{/t}</h2>
<h3> <span>{t}Congratulations!{/t}</span> <br /><br />
    {if $family->getJoinMode() != 1}
        {t}Your group(s){/t} <br />
        {foreach item=group from=$groups} 
            <a href="{$group->getGroupPath('summary')}">{$group->getName()|escape:html}</a><br />
        {/foreach}
        {t}is now a member of the {tparam value=$CurrentGroup->getGroupPath('summary')}{tparam value=$CurrentGroup->getName()|escape:html}<a href="%s">%s</a> group family.{/t}
    {else}
        {t}Your request to join has been sent{/t}
    {/if} 
</h3>
<div class="prIndentTop">{t}Your group's photos, lists, documents and discussions have been added to the {tparam value=$CurrentGroup->getGroupPath('summary')}{tparam value=$CurrentGroup->getName()|escape:html} <a href="%s">%s</a> database.{/t}</div>
<div class="prIndentTop prIndentBottom">
    {foreach item=group from=$groups}
        {t}The members of <a href="{$group->getGroupPath('summary')}">{$group->getName()|escape:html}</a> have been notified that a group they belong to has joined the {tparam value=$CurrentGroup->getGroupPath('summary')}{tparam value=$CurrentGroup->getName()|escape:html} <a href="%s">%s</a> group family.{/t}<br />
    {/foreach}
</div>
<div> 
	<a href="{$CurrentGroup->getGroupPath('summary')}"> <strong>{t}{tparam value=$CurrentGroup->getName()|escape:html}Take me to the %s workspace{/t} &raquo;</strong></a> <br /><br />
	{foreach item=group from=$groups} 
	    <a href="{$group->getGroupPath('summary')}"><strong>{t}{tparam value=$group->getName()|escape:html}Take me to the %s workspace{/t} &raquo;</strong></a> <br /><br />
	{/foreach} 
</div>