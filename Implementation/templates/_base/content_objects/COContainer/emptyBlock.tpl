{literal}
<!-- Container for header -->
<div id="cb-header-{$newObjectID}" class="prCO-headline prCO-headline-view-mode">
	
	<h4 id="{$newObjectID}_title" class="prCO-title">{$newObjectTitle}</h4>
	
	
	<!-- Buttons for view mode -->
	<div id="{$newObjectID}_view_mode_buttons" style="display:none; float:right;">
    	<span class="prCOHeaderButtons">
        	<a class="prCO-edit" href="#" onclick="setEditMode('{$newObjectID}'); return false;" title="Edit">&nbsp;</a>
            <a class="prCO-close" href="#" onclick="WarecorpDDblockApp.removeItem('{$newObjectID}'); return false;" title="Delete">&nbsp;</a>
        </span>
    </div>
                                        
    <!-- Buttons for edit mode -->
    <div id="{$newObjectID}_edit_mode_buttons" style="display:none; float:right;">
        <span class="prCOHeaderButtons">
            <a href="#null" onclick="applyEditMode('{$newObjectID}'); return false;" class="prCO-save" title="Save">&nbsp;</a>
            <a href="#null" onclick="WarecorpDDblockApp.removeItem('{$newObjectID}'); return false;" class="prCO-close" title="Delete">&nbsp;</a>
			<a href="#null" onclick="cancelEditMode('{$newObjectID}'); return false;" class="prCO-cancel" title="Cancel">&nbsp;</a>
        </span>
    </div>
    
</div>

<!-- Container for content -->        
<div id="cb-content-{$newObjectID}">
                            
	<!-- Content here -->
	<div align="center"><img style="padding:5px;" src="/theme/product/images/decorators/waiting.gif" alt=""/></div>
	
</div>
{/literal}