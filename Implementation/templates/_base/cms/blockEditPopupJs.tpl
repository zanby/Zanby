{*popup_item*}
<form id="frmEditBlock" name="frmEditBlock" action="javascript:void(0);">
    <input type="hidden" name="container" id="container" value="{$arrData.container}" />
    <input type="hidden" name="edit_block_id" id="edit_block_id" value="{$arrData.id}" />
    <input type="hidden" name="edit_block_page_id" id="edit_block_page_id" value="{$arrData.page_id}" />
    <input type="hidden" name="edit_block_order" id="edit_block_order" value="{$arrData.order}" />
    
    <label for="edit_block_content">{t}Content:{/t}</label>
    <div class="znbClear"><span></span></div>
    <textarea id="edit_block_content_area" name="edit_block_content_area" rows="20" cols="40">{$arrData.content}</textarea>
    {*<div id="edit_block_content_area" name="edit_block_content_area">{$arrData.content}</div>*}
    <div class="znbClear"><span></span></div>
    <div class="co-buttons-pannel-pop">
        <!--div style="margin-left: -88px;"-->
        <div>
			{t var='button_01'}Save{/t}
            {linkbutton onclick="tinyMCE.get('edit_block_content_area').save();
                document.getElementById('`$arrData.container`').innerHTML = document.getElementById('edit_block_content_area').value;
                document.getElementById('`$arrData.container`_hidden').value = document.getElementById('edit_block_content_area').value;
                popup_window.close();return false;" name=$button_01} {t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a>
        </div>
    </div>
</form>
{*popup_item*}