<p>{t}{tparam value=$groupname}
You have chosen to permanently delete the group %s{/t}
</p>

 <div class="prTRight prIndentTop">  
 {t var="in_button"}Continue{/t} 	
{linkbutton name=$in_button onclick="xajax_privileges_deletegroupstep2(); return false;"}&nbsp; 
{t var="in_button_2"}Go Back{/t}
{linkbutton name=$in_button_2 onclick="TitltPaneAppGroupSettingsDeleteGroup.hide(); return false;"}
</div>

