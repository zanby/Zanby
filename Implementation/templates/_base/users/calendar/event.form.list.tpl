                <div class="prToggleArea">
                    <div id="eventListsContent" class="prIndentTopSmall">
                        {include file="users/calendar/action.event.template.lists.tpl" lstLists=$formParams.event_lists}
                    </div>
					<div class="prInnerTop">
                    <a href="#" onclick="xajax_doAttachList(); return false;">+ {t}attach list{/t}</a>
                    </div>
                    {form_hidden name="show_lists_block" id="show_lists_block" value=$formParams.show_lists_block}
                </div>
