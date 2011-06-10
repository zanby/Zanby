{foreach from=$lstDocuments item=item}
	<div class="prClr3 prIndentTop">
    	<div class="prFloatLeft">
			<a href="{$currentUser->getUserPath('docget')}docid/{$item->getId()}/">{$item->getOriginalName()|escape}</a>
            &nbsp;|&nbsp;{$item->getFileSize()}&nbsp;|&nbsp;{$item->getFileExt()}
        </div>
		<div class="prFloatRight">
            <a href="#null" onclick="xajax_doAttachDocument({$item->getId()}, 'DELETE'); return false;">{t}Delete{/t}</a>       
        </div>	 
    </div>	
    <input type="hidden" name="event_documents[]" value="{$item->getId()}"/>  	
{/foreach}