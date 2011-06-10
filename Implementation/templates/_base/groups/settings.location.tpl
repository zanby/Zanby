{literal}
	<script language="javascript">
	function GroupSettingsLocation_over() {
		document.getElementById("GroupSettingsLocationTitle").style.textDecoration = "underline";
		document.getElementById("GroupSettingsLocationImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";

	}
	function GroupSettingsLocation_out() {
		document.getElementById("GroupSettingsLocationTitle").style.textDecoration = "none";
		document.getElementById("GroupSettingsLocationImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
	}
    function getIframeCoord(){
    	document.getElementById("lat").value = window.frames[0].document.getElementById('lat').value;
    	document.getElementById("lng").value = window.frames[0].document.getElementById('lng').value;
    }
	</script>
{/literal}

{if $visibility_details == "location"}
	<script>xajax_privileges_location_show('{$groupId}');</script>
{else}
{if $visibility == false}
	<table width="100%" cellpadding="0" cellspacing="0" border="0" onmouseover="GroupSettingsLocation_over();" onmouseout="GroupSettingsLocation_out();" style="cursor:pointer;">
		<tr>
			<td id="GroupSettingsLocationArrow" width="13"><img id="GroupSettingsLocationImage" src="{$AppTheme->images}/decorators/groups/gsett_arrow_off.gif" width="13" /></td>
			<td id="GroupSettingsLocationTitle" style="padding-left: 5px; width:110px;" nowrap="nowrap">{t}Location{/t}</td>
			<td style="padding-left: 10px"></td>
			<td style="width:50px" align="right">[ <a href="#" onclick="xajax_privileges_location_show(); return false;">{t}Show{/t}</a> ]</td>
		</tr>
	</table>
{else}
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td id="GroupSettingsLocationArrow" width="13"><img id="GroupSettingsLocationImage" src="{$AppTheme->images}/decorators/groups/gsett_arrow.gif" width="13" /></td>
			<td id="GroupSettingsLocationTitle" style="padding-left: 5px; width:110px;" nowrap="nowrap">{t}Location{/t}</td>
			<td style="padding-left: 10px"></td>
			<td style="width:50px" align="right">[ <a href="#" onclick="xajax_privileges_location_hide(); return false;">{t}Hide{/t}</a> ]</td>
		</tr>
		<tr>
		  <td colspan="4">
				<center>{t}Location:{/t}</center>
			    <iframe name="iframe1" frameborder="0" id="iframe1" src="/{$LOCALE}/gmapsinglegroup/group/{$CurrentGroup->getId()}/sizex/500/sizey/300/zoom/5/showtools/yes/dragable/yes/" width="520" height="320"></iframe>
				<form name="form0" id="form0">
                	<input type="hidden" name="lat" id="lat">
                	<input type="hidden" name="lng" id="lng">
                	<input type="hidden" name="isPostBack" id="isPostBack" value="1">
                	<div align="right">
					{t var="in_button"}Save Location{/t}
                	{linkbutton name=$in_button onclick="getIframeCoord();document.getElementById('form0').submit(); return false;"}
                	</div>
            	</form>
		  </td>
		</tr>
	</table>
{/if}
{/if}
