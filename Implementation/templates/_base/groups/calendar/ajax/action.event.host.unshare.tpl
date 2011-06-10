{*popup_item*}
<div class="prClr3">
<table cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="prInnerBottom">
			{t}Clicking 'Unshare Event' below will unshare the event from your group calendar.{/t}
        </td>
    </tr>
    <tr height="25" valign="bottom">
        <td>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td align="center">
						{t var="in_button"}Go Back{/t}
						{linkbutton name=$in_button color="blue" onclick="popup_window.close(); return false;"}
					</td>
                    <td align="center">
					<div style="float:right;">
						{t var="in_button_2"}Unshare Event{/t}
						{linkbutton name=$in_button_2 color="blue" onclick="xajax_doHostUnshareEvent($id, $uid, true); return false;"}
					</div>
					</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>
{*popup_item*}