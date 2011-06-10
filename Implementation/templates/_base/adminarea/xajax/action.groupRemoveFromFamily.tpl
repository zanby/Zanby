<div>
    <div>
        <table class="prForm">
            <tr>
                <td><p>{t}{tparam value=$group->getName()|escape}{tparam value=$family->getName()|escape}Are you sure you want to detach group "%s" from Family "%s"?{/t}</p></td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
					    {t var="yes_button}Detach group from family{/t}
					    {linkbutton name=$yes_button link="javascript:void(0)" onclick="xajax_groupRemoveFromFamilyDo("|cat:$group->getId()|cat:", "|cat:$family->getId()|cat:"); return false;"}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>
