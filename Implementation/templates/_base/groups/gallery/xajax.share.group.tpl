<!-- user content -->
<label>{t}{tparam value=$gallery->getTitle()|escape:html}Share %s with a group or group family{t}</label>
<label>{t}Select group or group family{/t}</label>
<select style="width: 80%;" name="groupsForShare" id="groupsForShare">
	<option value="0">{t}Choose group or family group{/t}</option>
	<optgroup label="Groups">
	{foreach from=$simpleGroups item=group}
	{if $gallery->isShared($group)}
		<option value="{$group->getId()}" disabled="disabled">{$group->getName()|escape:html} - {t}Shared on{/t} {$gallery->getShareDate($group)}</option>
	{else}
    	<option value="{$group->getId()}">{$group->getName()|escape:html}</option>
	{/if}
	{/foreach}
	</optgroup>
	<optgroup label="Family Groups">
	{foreach from=$familyGroups item=group}
	{if $gallery->isShared($group)}
		<option value="{$group->getId()}" disabled="disabled">{$group->getName()|escape:html} - {t}Shared on{/t} {$gallery->getShareDate($group)}</option>
	{else}
    	<option value="{$group->getId()}">{$group->getName()|escape:html}</option>
	{/if}
	{/foreach}
	</optgroup>
</select>
<span>
	<span class="co-orange">{t}Note:{/t}</span>
	{t}Only groups which you host, or in which the host has granted you 
	permission to upload photos will appear in this field. 
	If you do not see the desired group, you must contact the 
	host to get permission to share your photo.{/t}
</span>
</div>
<!-- popup -->
<div class="co-buttons-pannel-pop">
<div style="margin-left: -83px;">
    <!-- minus half of buttons width to center them -->
    <div class="co-button" onClick="{$JsApplication}.showShareGroupHandle({$gallery->getId()})" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a href="#null">{t}Share{/t}</a></div>
    <div class="co-button" onClick="{$JsApplication}.hideSharePanel(); return false;" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a href="#null">{t}Cancel{/t}</a></div>
</div>
<!-- /popup -->
<!-- /user content -->
