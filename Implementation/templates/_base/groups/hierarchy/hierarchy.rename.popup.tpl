{*popup_item*}
<div class="prClr3">
	<label for="hname" class="prFloatLeft">{t}Hierarchy Name:{/t}</label>
	<input type="text" name="hname" id="hname" value="{$h->getName()|escape:html}" class="prIndentLeft">
</div>
<div class="prInnerTop prTCenter">
	<a class="prButton" href="#null" onClick="renameHierarchyHandler({$curr_hid}); return false;"><span>{t}Save Changes{/t}</span></a>
	<span class="prIndentLeftSmall">{t}or{/t} <a href="#null" onClick="popup_window.close(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
{*popup_item*}