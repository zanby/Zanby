$(function(){
    MListApplication.init();
})

var MListApplication = null;
if ( !MListApplication ) {
	MListApplication = function () {
		return {
			init : function () {
				$('#checkAll').bind('click', function(event) { MListApplication.onCheckAll(); return false; });
				$('#checkNone').bind('click', function(event) { MListApplication.onCheckNone(); return false; });
				$('#lnkOnActivateChecked').bind('click', function(event) { MListApplication.onActivateChecked(); return false; });
				$('#lnkOnBlockChecked').bind('click', function(event) { MListApplication.onBlockChecked(); return false; });
				$('#lnkOnDeleteChecked').bind('click', function(event) { MListApplication.onDeleteChecked(); return false; });								
				
			},
			showInfo : function (message) {
                document.getElementById("infoPanelContent").innerHTML = message;
                popup_window.target('infoPanel'); popup_window.title("Information");
                popup_window.width(350); popup_window.height(80);
                popup_window.open();
			},
            /* check if at least one item is choosed  */
            isAnyItemChecked : function() {
                if ( 0 != $('.item-checkbox:checked').size() ) return true;
                else return false;
            },
            /* check if one ite m is choosed only*/
            isOneItemChecked : function() {
                if ( 1 == $('.item-checkbox:checked').size() ) return true;
                else return false;
            },
            getCheckedAsString : function() {
                var strOut = [];
                $('.item-checkbox:checked').each(function(){
                    strOut[strOut.length] = $(this).val();
                })
                return strOut.join(',');
            },
            onActivateChecked : function () {
            	if ( MListApplication.isAnyItemChecked() ) {
                	$.post(cfgMListApplication.urlOnActivateChecked, 
                    		{ajax_mode: 'activate', members: MListApplication.getCheckedAsString()},
                    		function (data) {
                    			xajax.processResponse(data);
                	},'xml');
            	} else { MListApplication.showInfo('You must choose at least one item'); return; }
            },
            onBlockChecked : function () {
            	if ( MListApplication.isAnyItemChecked() ) {
                	$.post(cfgMListApplication.urlOnBlockChecked, 
                    		{ajax_mode: 'block', members: MListApplication.getCheckedAsString()},
                    		function (data) {
                    			xajax.processResponse(data);
                	},'xml');
            	} else { MListApplication.showInfo('You must choose at least one item'); return; }
            },
            onDeleteChecked : function () {
            	if ( MListApplication.isAnyItemChecked() ) {
                    popup_window.target('confirmDeletePanel');
                    popup_window.width(350); popup_window.height(80);
                    popup_window.open();
                    
                    $('#btnConfirmDeleteFormSubmit').unbind().bind('click', function() {
                    	$.post(cfgMListApplication.urlOnDeleteChecked, 
                    		{ajax_mode: 'delete', members: MListApplication.getCheckedAsString()},
                    		function (data) {
                    			xajax.processResponse(data);
                    	},'xml');
                    })
            	} else { MListApplication.showInfo('You must choose at least one item'); return; }
            },
            /* choose all items */
			onCheckAll : function () {
                $('.item-checkbox').each(function(){
                    $(this).attr('checked', true);
                })
			},
            /* deselect all items */
			onCheckNone : function () {
                $('.item-checkbox').each(function(){
                    $(this).attr('checked', false);
                })
			}
		}
	}();
};