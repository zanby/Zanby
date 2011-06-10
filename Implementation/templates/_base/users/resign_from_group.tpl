<p class="prText2 prTCenter">
	{t}
	{tparam value=$groupTypeName}
	{tparam value=$group->getName()|escape}
		Are you sure you want to resign from %s "%s"?
	{/t}
</p>
<div class="prInnerTop prTCenter">
{t var='button'}Resign{/t}
{linkbutton color="blue" name=$button onclick="xajax_resignFromGroupDo("|cat:$group->getId()|cat:"); return false;"}
		<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span>
		<a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
