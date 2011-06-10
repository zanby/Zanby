{*popup_item*}
<div>
    <p class="prTCenter prText2">
    {if $action == delete}
    {t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to delete this list "%s"?{/t}
    {elseif $action == unshare}
    {t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to unshare this list "%s"?{/t}
    {elseif $action == offwatch}
    {t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to take off watch this list "%s"?{/t}
    </p>
    {/if}
    {form from=$form id=confirmForm}
        {form_hidden name="list_id" value=$list->getId()}
        {form_hidden name="action" value=$action|escape:html}
        <div class="prTCenter prInnerTop">
			{if $action == unshare}
			{t var='button_01'}Unshare List{/t}
                {linkbutton name=$button_01 onclick="xajax_list_confirm_popup_close(xajax.getFormValues('confirmForm')); return false;"}
			{else}
			{t var='button_02'}Delete List{/t}
				{linkbutton name=$button_02 onclick="xajax_list_confirm_popup_close(xajax.getFormValues('confirmForm')); return false;"}
			{/if}
                <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>

        </div>
    {/form}
</div>
{*popup_item*}