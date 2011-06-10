$(function(){
	startTranslateMode();
})
function handleTranslateMessageForm() {
    var callback = { success: handleTranslateMessageFormResponse }
    var oForm = YAHOO.util.Dom.get('translateMessageForm');
    YAHOO.util.Connect.setForm(oForm);
    var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
}
function handleTranslateMessageFormResponse( oResponse ) {
	xajax.processResponse(oResponse.responseXML);
}
function startTranslateMode( parent ) {
	if ( typeof(parent) == "undefined" ) elements = $("font[translate='on']");
	else elements = $("font[translate='none']");
	elements.each(function(i){
		var key = $(this).attr('key');
		var file = $(this).attr('file');
		$(this).append('<img translate="on" key="'+key+'" file="'+file+'" id="translateImg_'+ $(this).attr('key') +'" src="/theme/product/images/documents/docMove.gif">');
		$(this).attr('translate', 'done');
	})
	$("img[translate='on']").each(function(i){
		$(this).css('cursor', 'pointer');
		$(this).css('width', '8px');
		$(this).css('height', '8px');
		$(this).unbind('click').bind('click', function(){
			xajax_showTranslatePopup($(this).attr('key'), $(this).attr('file')); return false;
		})
	})

}