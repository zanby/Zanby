{foreach from=$lstLists item=item}
	<div class="prClr3 prIndentTop">
		<div class="prFloatLeft">
			<a href="{$CurrentGroup->getGroupPath('listsview')}listid/{$item->getId()}/" target="_blank">{$item->getTitle()|escape:html}</a>
        </div>
		<div class="prFloatRight">
        	<a href="#null" onclick="xajax_doAttachList({$item->getId()}, 'DELETE'); return false;">{t}Delete{/t}</a>
		</div>	 
    </div>	
    <input type="hidden" name="event_lists[]" value="{$item->getId()}"/>  	
{/foreach}