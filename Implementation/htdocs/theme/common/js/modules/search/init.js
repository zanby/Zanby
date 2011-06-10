$(function(){1
    SearchApplication.init();
})

var SearchApplication = null;
if ( !SearchApplication ) {
	SearchApplication = function () {
		return {
            init : function () {
            },
            photoAddToMy : function (gallery, photo, handle) {
                data = null;
                if ( handle ) {
                    handle = 0;
                    $("input[name='addPhotoMode']").each(function(){ if ( $(this).attr('checked') == true ) handle = $(this).val();} );
                    if ( handle == 0 ) { alert('Please choose mode'); return false; }
                    if ( handle == 1 ) data = $('#addPhotoGalleryExist').val();
                    else data = $('#addPhotoGalleryNew').val();
                }          
                $.post(cfgSearchApplication.hPhotoAddToMy, 
                    { gallery : gallery, photo : photo, handle : handle, data : data}, function(data) {
                        xajax.processResponse(data);
                }, 'xml');
            },
            videoAddToMy : function (gallery, video, handle) {
                data = null;
                $.post(cfgSearchApplication.hVideoAddToMy,
                    { gallery : gallery, video : video, handle : handle, data : data}, function(data) {
                        xajax.processResponse(data);
                }, 'xml');
            },
            documentAddToMy : function (document, handle) {
                data = null;
                $.post(cfgSearchApplication.hDocumentAddToMy,
                    { document : document, handle : handle, data : data}, function(data) {
                        xajax.processResponse(data);
                }, 'xml');
            },
            eventAddToMy : function (id, uid, handle) {
                data = null;
                $.post(cfgSearchApplication.hEventAddToMy,
                    { id : id, uid : uid, handle : handle, data : data}, function(data) {
                        xajax.processResponse(data);
                }, 'xml');
            },
            listAddToMy : function (list, handle) {
                if ( handle ) {
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                    }}
    				var oForm = YAHOO.util.Dom.get('list_add_form');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgSearchApplication.hListAddToMy, callback);
                    return false;
                }
                $.post(cfgSearchApplication.hListAddToMy,
                    { list : list, handle : handle}, function(data) {
                        xajax.processResponse(data);
                }, 'xml');
            }
		}
	}();
};