$(function(){
    GListApplication.init();
})

var GListApplication = null;
if ( !GListApplication ) {
	GListApplication = function () {
		return {
			init : function () {
				$('#checkAll').bind('click', function(event) { GListApplication.onCheckAll(); return false; });
				$('#checkNone').bind('click', function(event) { GListApplication.onCheckNone(); return false; });
				$('#deleteChecked').bind('click', function(event) { GListApplication.onDeleteChecked(); return false; });
				$('#joinChecked').bind('click', function(event) { GListApplication.onJoinChecked(); return false; });
				
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
            onDeleteChecked : function () {
            	if ( GListApplication.isAnyItemChecked() ) {
                    popup_window.target('confirmDeletePanel');
                    popup_window.width(350); popup_window.height(80);
                    popup_window.open();
                    
                    $('#btnConfirmDeleteFormSubmit').unbind().bind('click', function() {
                    	$.post(cfgDGListApplication.urlOnDeleteChecked, 
                    		{ajax_mode: 'delete', groups: GListApplication.getCheckedAsString()},
                    		function (data) {
                    			xajax.processResponse(data);
                    	},'xml');
                    })
            	} else { GListApplication.showInfo('You must choose at least one item'); return; }
            },
            onJoinChecked : function () {
            	if ( GListApplication.isAnyItemChecked() ) {
                    popup_window.target('joinFamilyPanel');
                    popup_window.width(450); popup_window.height(120);
                    popup_window.open();
                    
                    $('#btnJoinFamilyFormSubmit').unbind().bind('click', function() {
                    	$.post(cfgDGListApplication.urlOnJoinChecked, 
                        		{ajax_mode: 'joinfamily', groups: GListApplication.getCheckedAsString(), family: $('#joinFamilyFormFamilyId').val()},
                        		function (data) {
                        			xajax.processResponse(data);
                    	},'xml');
                    })
            	} else { GListApplication.showInfo('You must choose at least one item'); return; }
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