{literal}
	<script language="javascript">
	function GroupSettingsDeleteGroup_over() {
		document.getElementById("GroupSettingsDeleteGroupTitle").style.textDecoration = "underline";
		document.getElementById("GroupSettingsDeleteGroupImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";
	}
	function GroupSettingsDeleteGroup_out() {
		document.getElementById("GroupSettingsDeleteGroupTitle").style.textDecoration = "none";
		document.getElementById("GroupSettingsDeleteGroupImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
	}
	</script>
{/literal}
{if $visibility_details == "deletegroup"}
	<script>xajax_privileges_deletegroup_show();</script>
{else}
	{if $visibility == true }
		<a id="GroupSettingsDeleteGroupAnchor"></a>				
		 <p class="prIndentTop">{t}{tparam value=$currentGroup->getName()}Remove the files, group family relationships, forum posts, landing pages and data structure for your <span class="prText2">'%s'</span> group family.{/t}</p>
		<div class="prTRight prIndentTop">{t var="in_button"}Delete Group Family{/t}{linkbutton name=$in_button link="#" onclick="xajax_privileges_deletegroupstep1(); return false;"}</div>					
	{/if}
{/if}