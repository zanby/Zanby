
<div id="deleteFBcontactConfirm">
	<div>
		<table class="prForm">
			<tr>
				<td><p class="prText2 prTCenter">{t}Are you sure you want to delete selected contact?{/t}</p></td>
			</tr>
			<tr>
				<td class="prTCenter"><span > 
					{t var="strBtnConfirm"}Delete{/t}{linkbutton id="btnGroupDeleteFormSubmit" name=$strBtnConfirm onclick="FBApplication.onremove_from_eventinvite($fbuid, 0);"} </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnGroupDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> 
				</td>
			</tr>
		</table>
	</div>
</div>
