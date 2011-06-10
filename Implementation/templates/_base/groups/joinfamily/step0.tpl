<div class="prInner">
    <div class="prClr3"> <a href="{$group->getGroupPath('summary')}" class="prFloatLeft prIndentRight"><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" alt="" /></a>
        <h2 class="prFloatLeft">{t}{tparam value=$group->getName()|escape:html}The %s Group Family{/t}&#8482;</h2>
    </div>
    <p class="prIndentTop prIndentBottom"> <strong>{t}Membership is open to any group.{/t}</strong> </p>
    <p> </p>
    <p> {t}{tparam value=$group->getName()|escape:html}In the next step, we will identify the groups you want to join with the %s Group Family.{/t} </p>
    <br />
    {capture name=familyName}{$group->getName()|escape:html}{/capture}
    <div class="prIndentTop">{t var="in_button"}{tparam value=$smarty.capture.familyName}Continue joining the `%s` Group Family™{/t}{linkbutton name=$in_button link=$group->getGroupPath('joinfamilystep1')}</div>
</div>
