var ResizeMinHeight = 300;
var ResizeMinWidth = 400;
    

var PGEApplication = null;
    if ( !PGEApplication ) {
        PGEApplication = function () {
            return {
				photosIds : null,
                galleryId : null,
				previewPanel : null,
				deletePhotoPanel : null,
                currentDelGallery : null,
                currentDelPhoto : null,
				uploadPanel : null,
                imageW: null,
                imageH: null,
				init : function () {
                    var handlerShowShareGroup = {
                        fn: PGEApplication.showShareGroup,
                        obj: PGEApplication,
                        scope: null
                    }
                    var handlerShowShareFriends = {
                        fn: PGEApplication.showShareFriends,
                        obj: PGEApplication,
                        scope: null
                    }
                    var handlerShowShareHistory = {
                        fn: PGEApplication.showShareHistory,
                        obj: PGEApplication,
                        scope: null
                    }
                    PGEApplication.oShareMenu = new YAHOO.widget.Menu("ShareMenu");
                    PGEApplication.oShareMenu.addItems([
                            { text: "Share with Group or Group Family",  onclick: handlerShowShareGroup },
                            { text: "Share with Friend",  onclick: handlerShowShareFriends },
                            { text: "Share History",  onclick: handlerShowShareHistory },
                        ]);
                    PGEApplication.oShareMenu.render("shareMenuTarget");
                    /**/
                    PGEApplication.shareMenuPanel = new YAHOO.widget.Panel('shareMenuPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGEApplication.shareMenuPanel.render();

                    /**/
                    PGEApplication.previewPanel = new YAHOO.widget.Panel('previewPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:false, x:230, y:40, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGEApplication.previewPanel.render();
                    /**/
                    PGEApplication.deletePhotoPanel = new YAHOO.widget.Panel('deletePhotoPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGEApplication.deletePhotoPanel.render();
                    /**/
                    PGEApplication.uploadPanel = new YAHOO.widget.Panel('uploadPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, underlay: "none", effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
					//PGEApplication.uploadPanel.beforeHideEvent.subscribe (PGEApplication.onHideUploadPanel);
                    PGEApplication.uploadPanel.render();
                },
				/**
				 * Share Methods
				 */
				disableSaherHistoryView : function () {
					var item = PGEApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", true);
				},
				enableSaherHistoryView : function () {
					var item = PGEApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", false);
				},
                showShareMenu : function (obj, galleryId, groupId) {
					PGEApplication.galleryId = galleryId;
				    PGEApplication.showShareNew(groupId);

                },
                showShareGroup : function(e, a, obj) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    if ( groupsForShare ) groupsForShare.selectedIndex = 0;
                    xajax_share_group(PGEApplication.galleryId, 'PGEApplication');
                },
                showShareGroupHandle : function(galleryId) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    var groupsForShareValue = groupsForShare.options[groupsForShare.selectedIndex].value;
                    if ( groupsForShareValue == 0 ) return false;
                    popup_window.close();
                    xajax_share_group_do(galleryId, groupsForShareValue, 'PGEApplication');
                },
                showShareFriends : function(e, a, obj) {
                    xajax_share_friend(PGEApplication.galleryId, 'PGEApplication');
                },
                showShareNew : function(groupId) {
                    //xajax_share_friend(PGLApplication.galleryId, 'PGLApplication');
                	xajax_share_group(PGEApplication.galleryId, groupId, 'PGEApplication');
                },
                showShareFriendsHandle : function (galleryId) {
                    if ( YAHOO.util.Dom.get('shareFriendMode1').checked == true ) {
                        var request = {};
                        request.users = 0;
                        request.subject = YAHOO.util.Dom.get('shareFriendSubject').value;
                        request.message = YAHOO.util.Dom.get('shareFriendMessage').value;
                        popup_window.close();
                        xajax_share_friend_do(galleryId, request, 'PGEApplication');
                    } else if ( YAHOO.util.Dom.get('shareFriendMode2').checked == true ) {
                        var request = {};
                        request.users = new Array();
                        var shareFriendUsers = YAHOO.util.Dom.get('shareFriendUsers');
                        for ( var i = 0; i < shareFriendUsers.options.length; i++ ) {
                            if ( shareFriendUsers.options[i].selected == true ) {
                                request.users[request.users.length] = shareFriendUsers.options[i].value;
                            }
                        }
                        if ( request.users.length == 0 ) return false;
                        request.subject = YAHOO.util.Dom.get('shareFriendSubject').value;
                        request.message = YAHOO.util.Dom.get('shareFriendMessage').value;
                        popup_window.close();
                        xajax_share_friend_do(galleryId, request, 'PGEApplication');
                    } else {
                        return false;
                    }

                },
                shareFriendModeChanged : function (value) {
                    if ( value == 1 ) YAHOO.util.Dom.get("shareFriendMode2AddFields").style.display = "none";
                    else YAHOO.util.Dom.get("shareFriendMode2AddFields").style.display = "";
                },
                showShareHistory : function(e, a, obj) {
					if ( YAHOO.util.Dom.get('unshareForm') ) {
						var oForm = YAHOO.util.Dom.get('unshareForm');
						if ( oForm.length != 0 ) {
							for (var i = 0; i < oForm.length; i++) {
								if ( oForm.elements[i].type == 'checkbox' ) oForm.elements[i].checked = false;
							}
						}
					}
					xajax_show_share_history(PGEApplication.galleryId, 'PGEApplication');
                },
				showShareHistoryHandle : function () {
					popup_window.close();
					var callback = {
						success: PGEApplication.handleUnShareResponse
					}
					var oForm = YAHOO.util.Dom.get('unshareForm');
					YAHOO.util.Connect.setForm(oForm);
					var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
				},
				handleUnShareResponse : function (oResponse) {
					xajax.processResponse(oResponse.responseXML);
				},
                hideSharePanel : function () {
                    popup_window.close();
                },
				/**
				 * Photo edit methods
				 */
                 saveGallery : function () {
                    PGEApplication.photosIds = new Array();
                    for (i = 0; i < document.forms.length; i++) {
                        form_id = document.forms[i].id;
                        if (form_id.indexOf('editPhotoForm', 0) == 0) {
                            PGEApplication.photosIds.push(form_id.replace(/editPhotoForm/, ''));
                        }
                    }
                    PGEApplication.saveGalleryHandle();
                },
				saveGalleryHandle: function() {
                    if (PGEApplication.photosIds.length > 0) {
                        photoId = PGEApplication.photosIds.pop();
                    } else {
                        document.galleryEditForm.submit();
                        return false;
                    }
                    var callback = {
                        success: PGEApplication.handleGallerySaveResponse
                    }
                    var oForm = YAHOO.util.Dom.get('editPhotoForm'+photoId);
                    YAHOO.util.Connect.setForm(oForm);
                    var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
                },
                handleGallerySaveResponse : function (oResponse) {
                    PGEApplication.saveGalleryHandle();
                },
                editPhotoHandle : function (photoId) {
					var callback = {
						success: PGEApplication.handleEditPhotoResponse
					}
					var oForm = YAHOO.util.Dom.get('editPhotoForm'+photoId);
					YAHOO.util.Connect.setForm(oForm);
					var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
				},
				handleEditPhotoResponse : function (oResponse) {
					xajax.processResponse(oResponse.responseXML);
				},
                /**
                 * Photo preview methods
                 */
                resizePopup: function(width,height,targetDom) {
        			var imageWidth = parseInt(width);
        			var imageHeight = parseInt(height);
                	//Copypast from thickbox.js
        			// Resizing large images - orginal by Christian Montoya edited by me.
        			var pagesize = tb_getPageSize();
        			var x = pagesize[0] - 90;
        			var y = pagesize[1] - 150;
        			if (x < ResizeMinWidth && imageWidth > ResizeMinWidth) x = ResizeMinWidth;
        			if (y < ResizeMinHeight && imageHeight > ResizeMinHeight) y = ResizeMinHeight;
        			
        			if (imageWidth > x) {
        				imageHeight = imageHeight * (x / imageWidth);
        				imageWidth = x;
        				if (imageHeight > y) {
        					imageWidth = imageWidth * (y / imageHeight);
        					imageHeight = y;
        				}
        			} else if (imageHeight > y) {
        				imageWidth = imageWidth * (y / imageHeight);
        				imageHeight = y;
        				if (imageWidth > x) {
        					imageHeight = imageHeight * (x / imageWidth);
        					imageWidth = x;
        				}
        			}
                    YAHOO.util.Dom.get(targetDom).style.width = imageWidth + 'px';
                    YAHOO.util.Dom.get(targetDom).style.height = imageHeight + 'px';
                    popup_window.width(imageWidth + 30);
                    popup_window.height(imageHeight + 60);
                    popup_window.reload();
                },
                showPreviewPanel : function(src, width, height) {
                	PGEApplication.imageW = width;
                	PGEApplication.imageH = height;
        			popup_window.target('previewPanel');
        			popup_window.fixed(true);
                    YAHOO.util.Dom.get('previewPanelImg').src = src;
                    PGEApplication.resizePopup(this.imageW,this.imageH,'previewPanelImg');
                    window.onresize = function() {PGEApplication.resizePopup(PGEApplication.imageW,PGEApplication.imageH,'previewPanelImg');};
                    popup_window.open();
                },
                hidePreviewPanel : function () {
                	popup_window.close();
                },
                /**
                 * Delete Photo Methods
                 */
                showDeletePhotoPanel : function (galleryId, photoId/*, pWidth, pHeight*/) {
                    PGEApplication.currentDelGallery = galleryId;
                    PGEApplication.currentDelPhoto = photoId;
                    var pWidth = null;
                    var pHeight = null;
                    if ( typeof arguments[2] != 'undefined' && parseInt(arguments[2], 10) )
                        pWidth = parseInt(arguments[2], 10);
                    if ( typeof arguments[3] != 'undefined' && parseInt(arguments[3], 10) )
                        pHeight = parseInt(arguments[3], 10);

                    popup_window.target('deletePhotoPanel');
                    popup_window.width((pWidth != null) ? pWidth : 450);
                    popup_window.height((pHeight !=  null) ? pHeight : 350);
                    popup_window.open();
                },
                showDeletePhotoPanelHandle : function () {
                    xajax_delete_photo(PGEApplication.currentDelGallery, PGEApplication.currentDelPhoto, 'PGEApplication');
                    popup_window.close();
                },
                hideDeletePhotoPanel : function () {
                    popup_window.close();
                },
				/**
				 * Upload Photos Methods
				 */
				showUploadPanel : function (galleryId) {
					xajax_upload_photo(galleryId);
				},
				showUploadPanelHandle : function () {
					//PGEApplication.uploadPanel.hide();
					var callback = {
						upload: PGEApplication.handleUploadPhotoResponse
					}
					var oForm = YAHOO.util.Dom.get('uploadPhotosForm');
					YAHOO.util.Connect.setForm(oForm, true);
                    try {
                        var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
                    } catch (ex) {
                        alert("Incorrect file name");
                    }
				},
				handleUploadPhotoResponse : function (oResponse) {
                    xajax.processResponse(oResponse.responseXML);
				},
				hideUploadPanel : function () {
					popup_window.close();
				},
				onHideUploadPanel : function () {
					cancelupload(function (){
						cancelClick();
					});
				}
            }
        }();
    };
