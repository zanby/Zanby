{*popup_item*}
<div class="prClr3">
	<label for="new_hname" class="prFloatLeft">{t}Hierarchy Name:{/t}</label>
	<input type="text" name="new_hname" id="new_hname" value="" class="prIndentLeft">
</div>
<div class="prInnerTop prTCenter">
	{t var="in_button"}Add Hierarchy{/t}
	{linkbutton name=$in_button onclick="addHierarchyHandler(); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
<script>
document.getElementById('new_hname').focus();
</script>
{*popup_item*}