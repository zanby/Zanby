<a id="GroupSettingsDeleteGroupAnchor"></a>
<h4 class="prInnerTop">{t}{tparam value=$currentGroup->getName()}You have chosen to permanently delete the <span>'%s'</span> group family.{/t}</h4>

{*
<p class="prInnerTop">{t}Perhaps your group members would like an opportunity to support the family on their own.  Did you know you can convert your group family to the organic model?  In organic families member groups pay to support the family.{/t}</p>
<div class="prTCenter prInnerTop">{t var="in_button"}Convert the family to the organic model{/t}{linkbutton name=$in_button link="#" onclick="confirmConvert(); return false;"}</div>
<h4 class="prInnerTop">{t}{tparam value=$groupname}You have chosen to permanently delete the group '<span>%s</span>'.{/t}</h4>  *}

<p class="prInnerTop">{t}To continue deleting your group family, click 'Continue' below{/t}</p>
<div class="prTRight prInnerTop">   	
	{t var="in_button_2"}Go Back{/t}{linkbutton name=$in_button_2 link="#" onclick="xajax_privileges_deletegroup_hide(); return false;"} &#160;
	{t var="in_button_3"}Continue{/t}{linkbutton name=$in_button_3 link="#" onclick="xajax_privileges_deletegroupstep2(); return false;"}
</div>
