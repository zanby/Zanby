{*popup_item*}
{if $action == delete}
<div class="prTCenter prText2">{t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to delete this list "%s"?{/t}</div>
{elseif $action == unshare}
<div class="prTCenter prText2">{t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to unshare this list "%s"?{/t}</div>
{elseif $action == offwatch}
<div class="prTCenter prText2">{t}{tparam value=$list->getTitle()|escape:"html"}Do you really want to take off watch this list "%s"?{/t}</div>
{/if}
{form from=$form id=confirmForm}
    {form_hidden name="list_id" value=$list->getId()}
    {form_hidden name="action" value=$action|escape:html}
   	<div class="prTCenter prInnerTop">
			{if $action == unshare}
				{t var="in_button_3"}Unshare List{/t}
				{linkbutton name=$in_button_3 onclick="xajax_list_confirm_popup_close(xajax.getFormValues('confirmForm')); return false;"}
			{else}
				{t var="in_button_4"}Delete List{/t}
				{linkbutton name=$in_button_4 onclick="xajax_list_confirm_popup_close(xajax.getFormValues('confirmForm')); return false;"}
			{/if}
            <span class="prIEVerticalAling prIndentLeftSmall">{t} or{/t} <a class="prInnerLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
        
    </div>
{/form} 
{*popup_item*}