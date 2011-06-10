<script type="text/javascript" src = "/js/yui/treeview/treeview.js"></script>
			<div class="prToggleArea">
				<div id="eventDocumentsContent" class="prIndentTopSmall">
					{include file="users/calendar/action.event.template.documents.tpl" lstDocuments=$formParams.event_documents}
				</div>
				<div class="prInnerTop">
					<a href="#" onclick="xajax_doAttachDocument(); return false;">+ {t}attach document{/t}</a>
				</div>
				{form_hidden name="show_documents_block" id="show_documents_block" value=$formParams.show_documents_block}
			</div>