$(function(){
    DocumentApplication.init();
})

var DocumentApplication = null;
if ( !DocumentApplication ) {
	DocumentApplication = function () {
		return {
			currentFolderId : 0,
			currentOwnerId : cfgDocumentApplication.currentOwnerId,
			moveCurrentFolder : null,
			moveSelectedFolder : null,
			moveSelectedOwner : null,
			init : function () {
        		DocumentApplication.menuAddDocument = $('#lnkAddDocument').menu({ 
        			content: $('#lnkAddDocument').next().html(),
        			width : 180,
        			positionOpts: {posX: 'left', posY: 'bottom', offsetX: -5, offsetY: 4, directionH: 'right',directionV: 'down', detectH: true, detectV: true, linkToFront: false}
        		});	
                DocumentApplication.menuAddDocument.create();
                DocumentApplication.menuAddDocument.kill();
                
        		DocumentApplication.menuCheckInOut = $('#lnkMoreActions').menu({ 
        			content: $('#lnkMoreActions').next().html(),
        			width : 180,
        			positionOpts: {posX: 'left', posY: 'bottom', offsetX: -5, offsetY: 4, directionH: 'right',directionV: 'down', detectH: true, detectV: true, linkToFront: false}
        		});	
                DocumentApplication.menuCheckInOut.create();
                DocumentApplication.menuCheckInOut.kill();
                DocumentApplication.initContent();
                
				if ( YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId) ) {
					YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId).className = 'tree-documents-folder-active';
				}
                jsTree();
                /* drag & drop */
                if ( cfgDocumentApplication.initDragDrop ) DocumentApplication.initDranDrop();
                
			},   
            initDranDrop : function () {
                $('.drag-source').bind( "dragstart", function( event ){
                    DocumentApplication.initDrop();
            		/* ref the "dragged" element, make a copy */
            			var $drag = $(this), $proxy = $drag.clone();
            		/* modify the "dragged" source element */
            			$drag.addClass("drag-drop-outline");
            		/* insert and return the "proxy" element */
            			return $proxy.appendTo( document.body ).addClass("drag-drop-ghost");
                }).bind( "drag", function( event ){
                    /* update the "proxy" element position */
                    	$( event.dragProxy ).css({
                    		left: event.offsetX + event.cursorOffsetX + 5, 
                    		top: event.offsetY + event.cursorOffsetY + 5 
                    	});
                }).bind( "dragend", function( event ){
            		/* remove the "proxy" element */
//            			$( event.dragProxy ).fadeOut( "normal", function(){
//            				$( this ).remove();
//            			});
                        $( event.dragProxy ).remove();
            		/* if there is no drop AND the target was previously dropped */
            			if ( !event.dropTarget && $(this).parent().is(".drop") ){
            				/* put it in it's original place */
            				//$('#nodrop').append( this );
            			}
            		/* restore to a normal state */
            			$( this ).removeClass("drag-drop-outline");	            			
                });
            },  
            initDrop : function () {
                //$.dropManage({ mode:'mouse' }); 
            	$('.drop-source').unbind("dropstart").bind( "dropstart", function( event ){
            		/* don't drop in itself */
            		    if ( this == event.dragTarget.parentNode ) return false;
            	    /* activate the "drop" target element */
            			$( this ).addClass("drag-drop-active");
            	}).unbind("drop").bind( "drop", function( event ){
                    /* if there was a drop, do changes */
            			//$( this ).append( event.dragTarget );
                        if ($(this).is('.drag-source')) {
                            /* try to move */                               
                                groups = ( $(event.dragTarget).attr('document') ) ? $(event.dragTarget).attr('document') : '';
                                fgroups = ( $(event.dragTarget).attr('folder') ) ? $(event.dragTarget).attr('folder') : '';
                                $.post(cfgDocumentApplication.hMoveGroup, 
                                    { groups : groups, fgroups : fgroups, 
                                      folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId,
                                      to_folder_id : $(this).attr('folder'), to_owner_id : $(this).attr('owner')
                                    }, 
                                    function(data) {
                                        xajax.processResponse(data);
                                }, 'xml');
                        } 
                        /* to tree menu */
                        else {
                            if ($(this).parent().attr('node')) var currNode = tree_0.getNodeByProperty('id', $(this).parent().attr('node'));
                            else var currNode = eval('tree_0_root_node_'+$(this).parent().attr('roottype')+'_'+$(this).parent().attr('rootnode'));
                            /* try to move */                               
                                groups = ( $(event.dragTarget).attr('document') ) ? $(event.dragTarget).attr('document') : '';
                                fgroups = ( $(event.dragTarget).attr('folder') ) ? $(event.dragTarget).attr('folder') : '';
                                $.post(cfgDocumentApplication.hMoveGroup, 
                                    { groups : groups, fgroups : fgroups, 
                                      folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId,
                                      to_folder_id : currNode.data.id, to_owner_id : currNode.data.ownerId
                                    }, 
                                    function(data) {
                                        xajax.processResponse(data);
                                }, 'xml');
                        }
                        
            	}).unbind("dropend").bind( "dropend", function( event ){
            		/* deactivate the "drop" target element */
            			$( this ).removeClass("drag-drop-active");
            	});                
            },                   
			showInfo : function (message) {
                YAHOO.util.Dom.get("infoPanelContent").innerHTML = message;
                popup_window.target('infoPanel'); popup_window.title("Information");
                popup_window.width(350); popup_window.height(80);
                popup_window.open();
			},
			/* change active folder for main level */
			cangeActiveMainFolder : function (currNode) {
				id = currNode.data.ownerId;
				if ( DocumentApplication.currentOwnerId != id || ( DocumentApplication.currentOwnerId == id && DocumentApplication.currentFolderId != 0) ) {
					//if ( currNode.hasChildren() ) currNode.expand();
					
					YAHOO.util.Dom.get('tree_0_main_node_label_' + id).className = 'tree-documents-folder-active';
					if ( YAHOO.util.Dom.get('tree_0_node_label_' + DocumentApplication.currentFolderId) ) {
						YAHOO.util.Dom.get('tree_0_node_label_' + DocumentApplication.currentFolderId).className = 'tree-documents-folder-inactive';
					} else if ( YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId) ) {
						YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId).className = 'tree-documents-folder-inactive';
					}
					DocumentApplication.currentFolderId = 0;
					DocumentApplication.currentOwnerId = id;
					xajax_change_main_folder(id);
				}
			},
            /* change active folder */
			changeActiveFolder : function (currNode) {
				folder_id = currNode.data.id;
				if ( DocumentApplication.currentFolderId != folder_id ) {
					//if ( currNode.hasChildren() ) currNode.expand();
					if ( currNode.parent ) currNode.parent.expand();
					YAHOO.util.Dom.get('tree_0_node_label_' + folder_id).className = 'tree-documents-folder-active';
					if ( YAHOO.util.Dom.get('tree_0_node_label_' + DocumentApplication.currentFolderId) ) {
						YAHOO.util.Dom.get('tree_0_node_label_' + DocumentApplication.currentFolderId).className = 'tree-documents-folder-inactive';
					} 
					if ( YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId) ) {
						YAHOO.util.Dom.get('tree_0_main_node_label_' + DocumentApplication.currentOwnerId).className = 'tree-documents-folder-inactive';
					}
					DocumentApplication.currentFolderId = folder_id;
					xajax_change_folder(folder_id);
				}
			},
            /* change active folder */
			changeActiveFolderDirect : function (folder_id) {
				if ( DocumentApplication.currentFolderId != folder_id ) {
					var currNode = tree_0.getNodeByProperty('id', folder_id);
					DocumentApplication.changeActiveFolder(currNode);
				}
			},     
			initContent : function() {
                $('.item-checkbox').each(function(){
                    $(this).bind('click', function(){
                        DocumentApplication.initCheckInOutMenu();
                    })
                })
                DocumentApplication.initCheckInOutMenu();
            },
            initCheckInOutMenu : function(){
                /* no items are choosed */
                if (!DocumentApplication.isAnyItemChecked()) {
                    this.denyAllActions();
                } else /* one item is choosed */ if (DocumentApplication.isOneItemChecked()) {
                    this.allowAllActions();
                    /* allow or deny checkout action */  
                        this.allowdenyCheckoutAction();
                    /* allow or deny cancel checkout action */  
                        this.allowdenyCancelCheckoutAction();
                    /* allow or deny cancel checkin action */
                        this.allowdenyCheckInAction();
                    /* allow or deny view revision history action */
                        this.allowdenyRevisionAction()
                    /* allow or deny share action */
                        this.allowdenyShareAction();
                    /* allow or deny unshare action */
                        this.allowdenyUnShareAction();
                } else /* more than one items are choosed */ {
                    this.allowAllActions();
                    /* allow or deny checkout action */  
                        this.allowdenyCheckoutAction();
                    /* allow or deny cancel checkout action */  
                        this.allowdenyCancelCheckoutAction();
                    /* allow or deny cancel checkin action */
                        this.allowdenyCheckInAction();
                    /* allow or deny share action */
                        this.allowdenyShareAction();
                    /* allow or deny unshare action */
                        this.allowdenyUnShareAction();
                    /* deny singl-actions */
                        $('.positionHelper ul').each(function(){
                            $(this).find("li[type='singl-action']").each(function(){
                                $(this).children('a').hide();
                                $(this).children('span').addClass('fg-menu-disabled-item').show();
                            })
                        })   
                }                 
            },
            allowAllActions : function() {
                /* allow all actions */
                $('.positionHelper ul').each(function(){
                    $(this).find("li[action]").each(function(){
                        $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                        $(this).children('a').show();
                    })
                })                   
            },
            denyAllActions : function() {
                /* deny all actions */
                $('.positionHelper ul').each(function(){
                    $(this).find("li[action]").each(function(){
                        $(this).children('a').hide();
                        $(this).children('span').addClass('fg-menu-disabled-item').show();
                    })
                })                   
            },
            /* allow or deny checkout action */
            allowdenyCheckoutAction : function() {
                if (0 == $(".item-checkbox[cancheckout='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='checkout']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='checkout']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
            },
            /* allow or deny cancel checkout action */
            allowdenyCancelCheckoutAction : function() {
                if (0 == $(".item-checkbox[cancancelcheckout='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='cancelcheckout']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='cancelcheckout']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
            },
            /* allow or deny cancel checkin action */
            allowdenyCheckInAction : function() {
                if (0 == $(".item-checkbox[cancheckin='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='checkin']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='checkin']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
            },
            /* allow or deny view revision history action */
            allowdenyRevisionAction : function() {
                if (0 == $(".item-checkbox[canrevision='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='revision']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='revision']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
            },
            /* allow or deny share action */
            allowdenyShareAction : function() {
                if (0 == $(".item-checkbox[canshare='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='share']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='share']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
            },
            /* allow or deny unshare action */
            allowdenyUnShareAction : function() {
                if (0 == $(".item-checkbox[canunshare='1']:checked").size()) {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='unshare']").each(function(){
                            $(this).children('a').hide();
                            $(this).children('span').addClass('fg-menu-disabled-item').show();
                        })
                    })
                } else {
                    $('.positionHelper ul').each(function(){
                        $(this).find("li[action='unshare']").each(function(){
                            $(this).children('span').removeClass('fg-menu-disabled-item').hide();
                            $(this).children('a').show();
                        })
                    })
                }
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
            /* check if one ite m is choosed only*/
            isOneItemOrFolderChecked : function() {
                if ( 1 == ($('.item-checkbox:checked').size() + $('.folder-checkbox:checked').size()) ) return true;
                else return false;
            },
            /* check if at least one item is choosed  */
            isAnyFolderChecked : function() {
                if ( 0 != $('.folder-checkbox:checked').size() ) return true;
                else return false;
            },
            getCheckedAsString : function() {
                var strOut = [];
                $('.item-checkbox:checked').each(function(){
                    strOut[strOut.length] = $(this).val();
                })
                return strOut.join(',');
            },
            getCheckedFoldersAsString : function() {
                var strOut = [];
                $('.folder-checkbox:checked').each(function(){
                    strOut[strOut.length] = $(this).val();
                })
                return strOut.join(',');
            },
            /* choose all documents and folders */
			onCheckAll : function () {
                if ($('#checkAll').attr('checked') == true) {
                    $('.item-checkbox').each(function(){
                        if ( !$(this).attr('disabled') ) $(this).attr('checked', true);
                    })
                    $('.folder-checkbox').each(function(){
                        if ( !$(this).attr('disabled') ) $(this).attr('checked', true);
                    })                
                } else {
                    $('.item-checkbox').each(function(){
                        $(this).attr('checked', false);
                    })
                    $('.folder-checkbox').each(function(){
                        $(this).attr('checked', false);
                    })
                }
                DocumentApplication.initCheckInOutMenu();
			},
			/* create new folder */
			createFolder : function () {
				if ( DocumentApplication.currentFolderId == 0 ) {
					var currNode = tree_0.getNodeByProperty('ownerId', DocumentApplication.currentOwnerId);
                    $("#addFolderPanelFolderLabel").html("top level of \"" + currNode.data.name + "\" Documents");
				} else {
					var currNode = tree_0.getNodeByProperty('id', DocumentApplication.currentFolderId);
                    $("#addFolderPanelFolderLabel").html(currNode.data.name + " folder");
				}

                $('#create_folder_folder_id').val(DocumentApplication.currentFolderId);
                $('#create_folder_owner_id').val(DocumentApplication.currentOwnerId);
                $('#create_folder_name').val('');
                $('#addFolderPanelErrorContainer').hide();

                popup_window.target('addFolderPanel'); popup_window.title("Add Folder");
                popup_window.width(500); popup_window.height(150);
                popup_window.open();    
                $('#create_folder_name').focus();
                
                $('#btnNewFolderFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('newFolderForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hCreateFolder, callback);
                    return false;
                })   
			},    
			/* create new document from computer */
			addFile : function (folder_id) {
				if ( DocumentApplication.currentFolderId == 0 ) {
					var currNode = tree_0.getNodeByProperty('ownerId', DocumentApplication.currentOwnerId);
                    $("#addFilePanelFolderLabel").html("top level of \"" + currNode.data.name + "\" Documents");
				} else {
					var currNode = tree_0.getNodeByProperty('id', DocumentApplication.currentFolderId);
                    $("#addFilePanelFolderLabel").html(currNode.data.name + " folder");
				}

                $('#new_file_folder_id').val(DocumentApplication.currentFolderId);
                $('#new_file_owner_id').val(DocumentApplication.currentOwnerId);
                $('#new_file').val('');
                $('#new_file_description').val('');
                $('#new_file_tags').val('');
                $('#new_file_privacy_private').attr('checked', true);
                $('#new_file_is_bulk').attr('checked', false);
                $('#addFilePanelErrorContainer').hide();

                popup_window.target('addFilePanel'); popup_window.title("Add Document");
                popup_window.width(500); popup_window.height(400);
                popup_window.open();     
                
                $('#btnNewFileFormSubmit').unbind('click').bind('click', function(){
    				var callback = {upload: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('newFileForm');
    				YAHOO.util.Connect.setForm(oForm, true);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hAddFile, callback);
                    return false;
                })       
			},
			/* create new document from computer */
			addWeblink : function (folder_id) {
				if ( DocumentApplication.currentFolderId == 0 ) {
					var currNode = tree_0.getNodeByProperty('ownerId', DocumentApplication.currentOwnerId);
                    $("#addWebLinkPanelFolderLabel").html("top level of \"" + currNode.data.name + "\" Documents");
				} else {
					var currNode = tree_0.getNodeByProperty('id', DocumentApplication.currentFolderId);
                    $("#addWebLinkPanelFolderLabel").html(currNode.data.name + " folder");
				}

                $('#new_weblink_folder_id').val(DocumentApplication.currentFolderId);
                $('#new_weblink_owner_id').val(DocumentApplication.currentOwnerId);
                $('#new_weblink').val('');
                $('#new_weblink_description').val('');
                $('#new_weblink_tags').val('');
                $('#new_weblink_privacy_private').attr('checked', true);
                $('#addWebLinkPanelErrorContainer').hide();

                popup_window.target('addWebLinkPanel'); popup_window.title("Add Document Link");
                popup_window.width(500); popup_window.height(350);
                popup_window.open();   
                $('#new_weblink').focus();  
                
                $('#btnNewWebLinkFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('newWebLinkForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hAddWeblink, callback);
                    return false;
                })       
			},   
            addToMy : function() {
                if (!this.isAnyItemChecked() && !this.isAnyFolderChecked()) { this.showInfo('You must choose at least one item'); return; }
                
                $('#addtomy_groups').val(this.getCheckedAsString());
                $('#addtomy_folder_id').val(DocumentApplication.currentFolderId);
                $('#addtomy_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('addToMyPanel'); popup_window.title("Add to My Documents");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();            

                $('#btnAddtomySubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        $('.item-checkbox').each(function(){ $(this).attr('checked', false); });
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); });
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('addToMyForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hAddToMy, callback);
                    return false;
                })
            },
			addToMyAccept : function () {
                $('#addtomyaccept_folder_id').val(DocumentApplication.currentFolderId);
                $('#addtomyaccept_owner_id').val(DocumentApplication.currentOwnerId);
                $('#addtomyaccept_groups_accept_name').attr('disabled', false);
                
                if ($('#addtomyaccept_groups_accept_name').val() == '') {
                    $('#addToMyAcceptErrorContainer').show();
                    $('#addToMyAcceptMessageContainer').hide();
                } else {
                    $('#addToMyAcceptErrorContainer').hide();
                    $('#addToMyAcceptMessageContainer').show();
                }
                
                popup_window.target('addToMyAcceptPanel'); popup_window.title("Information");
                popup_window.width(350); popup_window.height(180);
                popup_window.open();            

                $('#btnAddToMyAcceptFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        $('.item-checkbox').each(function(){ $(this).attr('checked', false); });
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); });
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('addToMyAcceptForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hAddToMy, callback);
                    return false;
                })
                $('#btnAddToMyAcceptFormCancel').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        $('.item-checkbox').each(function(){ $(this).attr('checked', false); });
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); });
                        DocumentApplication.initContent();
                    }}
                    var groups_accept = $('#addtomyaccept_groups_accept').val();
                    $('#addtomyaccept_groups_accept').val('');                    
                    if ($('#addtomyaccept_groups_cancel').val() == '') {
                        $('#addtomyaccept_groups_cancel').val(groups_accept);
                    } else {
                        $('#addtomyaccept_groups_cancel').val( $('#addtomyaccept_groups_cancel').val() + ',' +groups_accept);
                    }
                    $('#addtomyaccept_groups_accept_name').attr('disabled', true);
                    
    				var oForm = YAHOO.util.Dom.get('addToMyAcceptForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hAddToMy, callback);
                    return false;
                })
			},
			/* edit document or folder */
			editItem : function () {
                if (!this.isAnyItemChecked() && !this.isAnyFolderChecked()) { this.showInfo('You must choose at least one item'); return; }
                if (!this.isOneItemOrFolderChecked()) { this.showInfo('You must choose one item only'); return; }
                
                /* edit folder */
                if (this.isAnyFolderChecked()) {
                    var folder_id = this.getCheckedFoldersAsString();
                    $.post(cfgDocumentApplication.hEdit, 
                        {itemId : folder_id, itemType : 'folder', folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId }, 
                        function(data) {
                            xajax.processResponse(data);

                            $('#edit_folder_folder_id').val(DocumentApplication.currentFolderId);
                            $('#edit_folder_owner_id').val(DocumentApplication.currentOwnerId);
                            $('#editFolderPanelErrorContainer').hide();                                    
                            $('#edit_folder_itemId').val(folder_id);
                            $('#edit_folder_itemType').val('folder'); 
                            $('#edit_folder_name').focus();                               
                            
                            $('#btnEditFolderFormSubmit').unbind('click').bind('click', function() {
                                var callback = {
                                    success: function(oResponse) {
                                        xajax.processResponse(oResponse.responseXML);
                                        $('#checkAll').attr('checked', false);
                                        DocumentApplication.initContent();
                                    }
                                }
                                var oForm = YAHOO.util.Dom.get('editFolderForm');
                                YAHOO.util.Connect.setForm(oForm);
                                var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hEdit, callback);
                                return false;
                            })
                    }, 'xml');
                } else {
                    var document_id = this.getCheckedAsString();
                    /* edit document */
                    if (0 != $(".item-checkbox[value='" + document_id + "']:checked").filter(".item-checkbox[weblink='0']").size()) {
                        $.post(cfgDocumentApplication.hEdit, 
                            {itemId : document_id, itemType : 'document', folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId }, 
                            function(data) {
                                xajax.processResponse(data);

                                $('#edit_file_folder_id').val(DocumentApplication.currentFolderId);
                                $('#edit_file_owner_id').val(DocumentApplication.currentOwnerId);
                                $('#editFilePanelErrorContainer').hide();                                    
                                $('#edit_file_itemId').val(document_id);
                                $('#edit_file_itemType').val('document');  
                                $('#edit_file_description').focus();                              
                                
                                $('#btnEditFileFormSubmit').unbind('click').bind('click', function() {
                                    var callback = {
                                        success: function(oResponse) {
                                            xajax.processResponse(oResponse.responseXML);
                                            $('#checkAll').attr('checked', false);
                                            DocumentApplication.initContent();
                                        }
                                    }
                                    var oForm = YAHOO.util.Dom.get('editFileForm');
                                    YAHOO.util.Connect.setForm(oForm);
                                    var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hEdit, callback);
                                    return false;
                                })
                        }, 'xml');
                    }
                    /* edit weblink */ 
                    else {
                        $.post(cfgDocumentApplication.hEdit, 
                            {itemId : document_id, itemType : 'document', folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId }, 
                            function(data) {
                                xajax.processResponse(data);

                                $('#edit_weblink_folder_id').val(DocumentApplication.currentFolderId);
                                $('#edit_weblink_owner_id').val(DocumentApplication.currentOwnerId);
                                $('#editWebLinkPanelErrorContainer').hide();                                    
                                $('#edit_weblink_itemId').val(document_id);
                                $('#edit_weblink_itemType').val('document');  
                                $('#edit_weblink').focus();                              
                                
                                $('#btnEditWebLinkFormSubmit').unbind('click').bind('click', function() {
                                    var callback = {
                                        success: function(oResponse) {
                                            xajax.processResponse(oResponse.responseXML);
                                            $('#checkAll').attr('checked', false);
                                            DocumentApplication.initContent();
                                        }
                                    }
                                    var oForm = YAHOO.util.Dom.get('editWebLinkForm');
                                    YAHOO.util.Connect.setForm(oForm);
                                    var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hEdit, callback);
                                    return false;
                                })
                        }, 'xml');
                    }
                }
                return false;
			},
    
            /* delete documents or folders */
			deleteGroup : function () {
                if (!this.isAnyItemChecked() && !this.isAnyFolderChecked()) { this.showInfo('You must choose at least one item'); return; }
                
                $('#groupdelete_groups').val(this.getCheckedAsString());
                $('#groupdelete_fgroups').val(this.getCheckedFoldersAsString());
                $('#groupdelete_folder_id').val(DocumentApplication.currentFolderId);
                $('#groupdelete_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('deleteGroupPanel'); popup_window.title("Delete document(s) or folder(s)");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();            

                $('#btnGroupDeleteFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('groupDeleteForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hDeleteGroup, callback);
                    return false;
                })
			},
			deleteGroupAccept : function () {
                $('#groupdeleteaccept_folder_id').val(DocumentApplication.currentFolderId);
                $('#groupdeleteaccept_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('deleteGroupAcceptPanel'); popup_window.title("Information");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();            

                $('#btnGroupDeleteAcceptFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('groupDeleteAcceptForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hDeleteGroup, callback);
                    return false;
                })
                $('#btnGroupDeleteAcceptFormCancel').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
                    var fgroups_accept = $('#groupdeleteaccept_fgroups_accept').val();
                    $('#groupdeleteaccept_fgroups_accept').val('');                    
                    if ($('#groupdeleteaccept_fgroups_cancel').val() == '') {
                        $('#groupdeleteaccept_fgroups_cancel').val(fgroups_accept);
                    } else {
                        $('#groupdeleteaccept_fgroups_cancel').val( $('#groupdeleteaccept_fgroups_cancel').val() + ',' +fgroups_accept);
                    }
                    
    				var oForm = YAHOO.util.Dom.get('groupDeleteAcceptForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hDeleteGroup, callback);
                    return false;
                })
			},
            /* move documents or folders */
			moveGroup : function () {
                if (!this.isAnyItemChecked() && !this.isAnyFolderChecked()) { this.showInfo('You must choose at least one item'); return; }
                
                /* reset choosed folder */
				DocumentApplication.moveCurrentFolder = null;
				DocumentApplication.selectedFolder    = null;
				DocumentApplication.selectedOwner     =  null;
                $('#movegroup_groups').val(this.getCheckedAsString());
                $('#movegroup_fgroups').val(this.getCheckedFoldersAsString());
                $('#movegroup_folder_id').val(DocumentApplication.currentFolderId);
                $('#movegroup_owner_id').val(DocumentApplication.currentOwnerId);
                $('#movegroup_to_folder_id').val(null);
                $('#movegroup_to_owner_id').val(null);

                $.post(cfgDocumentApplication.hMoveGroup, 
                    {groups : this.getCheckedAsString(), fgroups : this.getCheckedFoldersAsString(), folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId}, 
                    function(data) {
                        xajax.processResponse(data);
                        $('#btnMoveGroupFormSubmit').unbind('click').bind('click', function() {
            				var callback = {success: function(oResponse){
                                xajax.processResponse(oResponse.responseXML);
                                $('#checkAll').attr('checked', false);
                                DocumentApplication.initContent();
                            }}
            				if ( DocumentApplication.selectedFolder === null || DocumentApplication.selectedOwner === null ) return false;
                            $('#movegroup_to_folder_id').val(DocumentApplication.selectedFolder);
                            $('#movegroup_to_owner_id').val(DocumentApplication.selectedOwner);

            				var oForm = YAHOO.util.Dom.get('moveGroupForm');
            				YAHOO.util.Connect.setForm(oForm);
            				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hMoveGroup, callback);
                            return false;
                        })
                }, 'xml');
			},
            /* choose folder to move, apply cless to active folder */
			selectMoveGroup : function (currNode) {
				if ( DocumentApplication.moveCurrentFolder != null ) {
					YAHOO.util.Dom.get(DocumentApplication.moveCurrentFolder).className = 'tree-documents-folder-inactive';
				}
				if ( currNode.data.oType == 'main_folder' ) {
					YAHOO.util.Dom.get('moveTree_main_node_label_' + currNode.data.ownerId).className = 'tree-documents-folder-active';
					DocumentApplication.moveCurrentFolder = 'moveTree_main_node_label_' + currNode.data.ownerId;
				} else {
					YAHOO.util.Dom.get('moveTree_node_label_' + currNode.data.id).className = 'tree-documents-folder-active';
					DocumentApplication.moveCurrentFolder = 'moveTree_node_label_' + currNode.data.id;
				}
				DocumentApplication.selectedFolder = currNode.data.id;
				DocumentApplication.selectedOwner =  currNode.data.ownerId;
			},

            /* check in all choosed */
            checkIn : function() {
                if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                if (!this.isOneItemChecked()) { this.showInfo('You must choose one item only'); return; }
                
                $('#checkInPanelErrorContainer').hide();
                $('#checkin_file').val('');
                $('#checkin_reason').val('');
                $('#checkin_groups').val(this.getCheckedAsString());
                $('#checkin_folder_id').val(DocumentApplication.currentFolderId);
                $('#checkin_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('checkInPanel'); popup_window.title("Check In");
                popup_window.width(500); popup_window.height(350);
                popup_window.open();    
                $('#checkin_reason').focus();
                
                $('#btnCheckInFormSubmit').unbind('click').bind('click', function(){
    				var callback = {upload: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); })
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('checkInForm');
    				YAHOO.util.Connect.setForm(oForm, true);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hCheckIn, callback);
                    return false;
                })       
            },
            /* check out all choosed */
            checkOut : function () {
                if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                
                $('#checkout_reason').val('');
                $('#checkout_groups').val(this.getCheckedAsString());
                $('#checkout_folder_id').val(DocumentApplication.currentFolderId);
                $('#checkout_owner_id').val(DocumentApplication.currentOwnerId);
                
                popup_window.target('checkOutPanel'); popup_window.title("Check Out");
                popup_window.width(500); popup_window.height(350);
                popup_window.open();
                $('#checkout_reason').focus();
                
                $('#btnCheckOutFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('checkOutForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hCheckOut, callback);
                    return false;
                })
            },
            /* cancel check out all choosed */
            cancelCheckOut : function () {
                if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                
                $('#cancelcheckout_groups').val(this.getCheckedAsString());
                $('#cancelcheckout_folder_id').val(DocumentApplication.currentFolderId);
                $('#cancelcheckout_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('cancelCheckOutPanel'); popup_window.title("Cancel Check Out");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();            

                $('#btnCancelCheckOutFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('cancelCheckOutForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hCancelCheckOut, callback);
                    return false;
                })
            },
            /* revision history */
            revisionHistory : function () {                
                if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                if (!this.isOneItemChecked()) { this.showInfo('You must choose one item only'); return; }

                if ( arguments.length == 0 ) current_page = 1
                else current_page = arguments[0];
                
                $.post(cfgDocumentApplication.hRevisions, {groups:this.getCheckedAsString(), page : current_page}, function(data){
                    xajax.processResponse(data);
                    $('#checkAll').attr('checked', false);
                    $('.folder-checkbox').each(function(){ $(this).attr('checked', false); })
                }, 'xml');
            },
            /* revert revision */
            revertRevision : function(revision, page) {

                $('#revertrevision_revision').val(revision);
                $('#revertrevision_page').val(page);
                $('#revertrevision_folder_id').val(DocumentApplication.currentFolderId);
                $('#revertrevision_owner_id').val(DocumentApplication.currentOwnerId);
                
                popup_window.target('revertRevisionPanel'); popup_window.title("Revert Revision");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();
                
                $('#btnRevertRevisionFormCancel').unbind().bind('click', function() {
                    DocumentApplication.revisionHistory(page);
                    return false;
                })
                $('#btnRevertRevisionFormSubmit').unbind().bind('click', function() {
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        $('.item-checkbox').each(function(){ $(this).attr('checked', false); })
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); })
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('revertRevisionForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hRevertRevision, callback);
                    return false;
                })
            },
            /* share document */
            shareDocument : function (documentId, ownerType, ownerId)
            {
                if ( !documentId ) {
                    if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                    if (!this.isOneItemChecked()) { this.showInfo('You must choose one item only'); return; }
                    documentId = this.getCheckedAsString();
                    ownerType = null;
                    ownerId = null;
                }                
                $.post(cfgDocumentApplication.hShare, 
                    {documentId : documentId, folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId, ownerType : ownerType, ownerId : ownerId}, 
                    function(data) {
                        xajax.processResponse(data);
                        $('.folder-checkbox').each(function(){ $(this).attr('checked', false); })
                        $('#btnShareDocumentToGroup').unbind('click').bind('click', function() {
                            DocumentApplication.shareDocument(documentId, 'group', $('#sharefile_group_id').val());
                        })
                        $('#btnShareDocumentToUser').unbind('click').bind('click', function() {
                            DocumentApplication.shareDocument(documentId, 'user', $('#sharefile_friend_id').val());
                        })
                        $('#lnkManageSharing').unbind('click').bind('click', function() {
                            DocumentApplication.manageSharing(documentId);
                        })
                }, 'xml');                
            },
            /* manage sharing for document */
            manageSharing : function (documentId, ownerType, ownerId) {
                if ( !documentId ) return false;
                if ( !ownerType ) ownerType = null;
                if ( !ownerId ) ownerId = null;
                $.post(cfgDocumentApplication.hManageSharing, 
                    {documentId : documentId, folder_id : DocumentApplication.currentFolderId, owner_id : DocumentApplication.currentOwnerId, ownerType : ownerType, ownerType : ownerType, ownerId : ownerId}, 
                    function(data) {                
                        xajax.processResponse(data);
                }, 'xml');
            },
            /* unshare documents */
            unShareDocument : function () {
                if (!this.isAnyItemChecked()) { this.showInfo('You must choose at least one item'); return; }
                                
                $('#unshare_groups').val(this.getCheckedAsString());
                $('#unshare_folder_id').val(DocumentApplication.currentFolderId);
                $('#unshare_owner_id').val(DocumentApplication.currentOwnerId);

                popup_window.target('unshareFilePanel'); popup_window.title("Unshare document(s)");
                popup_window.width(350); popup_window.height(100);
                popup_window.open();            

                $('#btnUnshareFilesFormSubmit').unbind('click').bind('click', function(){
    				var callback = {success: function(oResponse){
                        xajax.processResponse(oResponse.responseXML);
                        $('#checkAll').attr('checked', false);
                        DocumentApplication.initContent();
                    }}
    				var oForm = YAHOO.util.Dom.get('unshareFilesForm');
    				YAHOO.util.Connect.setForm(oForm);
    				var cObj = YAHOO.util.Connect.asyncRequest('POST', cfgDocumentApplication.hUnShare, callback);
                    return false;
                })
            }            
		}
	}();
};