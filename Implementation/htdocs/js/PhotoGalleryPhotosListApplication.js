var ResizeMinHeight = 300;
var ResizeMinWidth = 400;

var PGPLApplication = null;
    if ( !PGPLApplication ) {
        PGPLApplication = function () {
            return {
                galleryId : null,
                photoId : null,
                oShareMenu : null,
                shareMenuPanel : null,
                oAddMenu : null,
                addMenuPanel : null,
                previewPanel : null,
                currentComment : null,
                currentDelComment : null,
                currentDelGallery : null,
                currentDelPhoto : null,
                deleteCommentPanel : null,
                editPhotoPanel : null,
                deletePhotoPanel : null,
				unsharePanel : null,
                publishGallery : null,
                publishGroup : null,
                imageW: null,
                imageH: null,
                init : function () {
                    /* share menu initialization */
                    var handlerShowShareGroup = {
                        fn: PGPLApplication.showShareGroup,
                        obj: PGPLApplication,
                        scope: null
                    }
                    var handlerShowShareFriends = {
                        fn: PGPLApplication.showShareFriends,
                        obj: PGPLApplication,
                        scope: null
                    }
                    var handlerShowShareHistory = {
                        fn: PGPLApplication.showShareHistory,
                        obj: PGPLApplication,
                        scope: null
                    }
                    PGPLApplication.oShareMenu = new YAHOO.widget.Menu("basicmenuShareMenu");
                    PGPLApplication.oShareMenu.addItems([
                            { text: "Share with Group or Group Family",  onclick: handlerShowShareGroup },
                            { text: "Share with Friend",  onclick: handlerShowShareFriends },
                            { text: "Share History",  onclick: handlerShowShareHistory }
                        ]);
                    PGPLApplication.oShareMenu.render("shareMenuTarget");

                    /* Add Photo, Gallery to My Photos menu initialisation */
                    var handlerShowAddGallery = {
                        fn: PGPLApplication.showAddGallery,
                        obj: PGPLApplication,
                        scope: null
                    }
                    var handlerShowAddPhoto = {
                        fn: PGPLApplication.showAddPhoto,
                        obj: PGPLApplication,
                        scope: null
                    }
                    PGPLApplication.oAddMenu = new YAHOO.widget.Menu("basicmenuAddMenu");
                    PGPLApplication.oAddMenu.addItems([
                            { text: "Add entire gallery",  onclick: handlerShowAddGallery },
                            { text: "Add selected photo",  onclick: handlerShowAddPhoto }
                        ]);
                    PGPLApplication.oAddMenu.render("addMenuTarget");

                    /**/
                    PGPLApplication.shareMenuPanel = new YAHOO.widget.Panel('shareMenuPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.shareMenuPanel.render();

                    /**/
                    PGPLApplication.deletePanel = new YAHOO.widget.Panel('deletePanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.deletePanel.render();

                    /**/
                    PGPLApplication.addMenuPanel = new YAHOO.widget.Panel('addMenuPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.addMenuPanel.render();

                    /**/
                    PGPLApplication.previewPanel = new YAHOO.widget.Panel('previewPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:false, x:230, y:40, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.previewPanel.render();
                    /**/
                    PGPLApplication.deleteCommentPanel = new YAHOO.widget.Panel('deleteCommentPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.deleteCommentPanel.render();
                    /**/
                    PGPLApplication.editPhotoPanel = new YAHOO.widget.Panel('editPhotoPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.editPhotoPanel.render();
                    /**/
                    PGPLApplication.deletePhotoPanel = new YAHOO.widget.Panel('deletePhotoPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.deletePhotoPanel.render();
                    /**/
                    PGPLApplication.unsharePanel = new YAHOO.widget.Panel('unsharePanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGPLApplication.unsharePanel.render();
                },
				/**
				 * Share Methods
				 */
				disableSaherHistoryView : function () {
					var item = PGPLApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", true);
				},
				enableSaherHistoryView : function () {
					var item = PGPLApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", false);
				},
                showShareMenu : function (obj, galleryId, groupId) {
                    PGPLApplication.galleryId = galleryId;
					PGPLApplication.showShareNew(groupId);
                },
                showShareGroup : function(e, a, obj) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    if ( groupsForShare ) groupsForShare.selectedIndex = 0;
                    xajax_share_group(PGPLApplication.galleryId, 'PGPLApplication');
                },
                showShareGroupHandle : function(galleryId) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    var groupsForShareValue = groupsForShare.options[groupsForShare.selectedIndex].value;
                    if ( groupsForShareValue == 0 ) return false;
                    PGPLApplication.shareMenuPanel.hide();
                    xajax_share_group_do(galleryId, groupsForShareValue, 'PGPLApplication');
                },
                showShareFriends : function(e, a, obj) {
                    xajax_share_friend(PGPLApplication.galleryId, 'PGPLApplication');
                },
                showShareNew : function(groupId) {
                	xajax_share_group(PGPLApplication.galleryId, groupId, 'PGPLApplication');
                },
                showShareFriendsHandle : function (galleryId) {
                    if ( YAHOO.util.Dom.get('shareFriendMode1').checked == true ) {
                        var request = {};
                        request.users = 0;
                        request.subject = YAHOO.util.Dom.get('shareFriendSubject').value;
                        request.message = YAHOO.util.Dom.get('shareFriendMessage').value;
                        PGPLApplication.shareMenuPanel.hide();
                        xajax_share_friend_do(galleryId, request, 'PGPLApplication');
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
                        PGPLApplication.shareMenuPanel.hide();
                        xajax_share_friend_do(galleryId, request, 'PGPLApplication');
                    }
                    return false;
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
					xajax_show_share_history(PGPLApplication.galleryId, 'PGPLApplication');
                },
				showShareHistoryHandle : function () {
					PGPLApplication.shareMenuPanel.hide();
					var callback = {
						success: PGPLApplication.handleUnShareResponse
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
                 *
                 */
                showAddMenu : function (obj, galleryId, photoId, isAdult) {
					if (isAdult === true) {
						if(PGPLApplication.oAddMenu.getItems()[1])
							PGPLApplication.oAddMenu.removeItem(1);
					}else{
                        if(!PGPLApplication.oAddMenu.getItems()[1])
							PGPLApplication.oAddMenu.addItem([{ text: "Add selected photo",  onclick: handlerShowAddPhoto }]);
					}
                    PGPLApplication.galleryId = galleryId;
                    PGPLApplication.photoId = photoId;
                    YAHOO.util.Dom.get('addMenuTarget').style.display = '';
                    var region = YAHOO.util.Dom.getRegion(obj);
                    PGPLApplication.oAddMenu.cfg.setProperty("x", region.left);
                    PGPLApplication.oAddMenu.cfg.setProperty("y", region.top + 20);
                    PGPLApplication.oAddMenu.cfg.setProperty("width", "");
                    PGPLApplication.oAddMenu.show();
                  
                },
                showAddVideoMenu : function(obj, galleryId, videoId) {
                    PGPLApplication.galleryId = galleryId;
                    PGPLApplication.photoId = videoId;
                    PGPLApplication.showAddPhoto();
                },
                showAddGallery : function () {
                    xajax_add_gallery(PGPLApplication.galleryId, PGPLApplication.photoId, 'PGPLApplication');
                },
                showAddGalleryHandle : function (galleryId, photoId) {
                    if ( YAHOO.util.Dom.get('addGalleryMode1').checked == true ) {
                        var addGalleryExist = YAHOO.util.Dom.get('addGalleryExist');
                        if ( addGalleryExist.selectedIndex == -1 ) return false;
                        var request = {};
                        request.mode = 1;
                        request.galleryId = addGalleryExist.options[addGalleryExist.selectedIndex].value;
                        popup_window.close();
                        xajax_add_gallery_do(galleryId, photoId, request);
                    } else if ( YAHOO.util.Dom.get('addGalleryMode2').checked == true ) {
                        var galName = YAHOO.util.Dom.get('addGalleryNew').value;
                        //if ( galName.trim() == '' ) return false;
                        var request = {};
                        request.mode = 2;
                        request.galleryName = galName;
                        //popup_window.close();
                        xajax_add_gallery_do(galleryId, photoId, request);
                    } else if ( YAHOO.util.Dom.get('addGalleryMode3').checked == true ) {
                        var request = {};
                        request.mode = 3;
                        popup_window.close();
                        xajax_add_gallery_do(galleryId, photoId, request);
                    }
                    return false;
                },
                showAddPhoto : function () {
                    xajax_add_photo(PGPLApplication.galleryId, PGPLApplication.photoId, 'PGPLApplication');
                },
                showAddPhotoHandle : function (galleryId, photoId) {
                    if ( YAHOO.util.Dom.get('addPhotoMode1').checked == true ) {
                        var addPhotoGalleryExist = YAHOO.util.Dom.get('addPhotoGalleryExist');
                        if ( addPhotoGalleryExist.selectedIndex == -1 ) return false;
                        var request = {};
                        request.mode = 1;
                        request.galleryId = addPhotoGalleryExist.options[addPhotoGalleryExist.selectedIndex].value;
                        popup_window.close();
                        xajax_add_photo_do(galleryId, photoId, request);
                    } else if ( YAHOO.util.Dom.get('addPhotoMode2').checked == true ) {
                        var galName = YAHOO.util.Dom.get('addPhotoGalleryNew').value;
                        //if ( galName.trim() == '' ) return false;
                        var request = {};
                        request.mode = 2;
                        request.galleryName = galName;
                        //popup_window.close();
                        xajax_add_photo_do(galleryId, photoId, request);
                    }
                    return false;
                },
                hideAddPanel : function () {
                	popup_window.close();
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
                	PGPLApplication.imageW = width;
                	PGPLApplication.imageH = height;
        			popup_window.target('previewPanel');
        			popup_window.fixed(true);
                    YAHOO.util.Dom.get('previewPanelImg').src = src;
                    PGPLApplication.resizePopup(this.imageW,this.imageH,'previewPanelImg');
                    window.onresize = function() {PGPLApplication.resizePopup(PGPLApplication.imageW,PGPLApplication.imageH,'previewPanelImg');};
                    popup_window.open();
                },
                hidePreviewPanel : function () {
                	popup_window.close();
                },
                /**
                 *
                 */
                addComment : function (galleryId, photoId) {
                    var commentContent = YAHOO.util.Dom.get('newComment').value
                    if ( commentContent.trim() == '' ) return false;
                    xajax_add_comment_do(galleryId, photoId, commentContent, 'PGPLApplication');
                },
                editComment : function (commentId) {
                    PGPLApplication.cancelEditComment();
                    if ( YAHOO.util.Dom.get('commentContentTextarea'+commentId) ) {
                        PGPLApplication.currentComment = commentId;
                        YAHOO.util.Dom.get('commentContent'+commentId).style.display = 'none';
                        YAHOO.util.Dom.get('commentActions'+commentId).style.display = 'none';
                        YAHOO.util.Dom.get('commentEdit1'+commentId).style.display = '';
                        YAHOO.util.Dom.get('commentEdit2'+commentId).style.display = '';
                    }

                },
                cancelEditComment : function () {
                    if ( PGPLApplication.currentComment != null ) {
                        YAHOO.util.Dom.get('commentContent'+PGPLApplication.currentComment).style.display = '';
                        YAHOO.util.Dom.get('commentActions'+PGPLApplication.currentComment).style.display = '';
                        YAHOO.util.Dom.get('commentEdit1'+PGPLApplication.currentComment).style.display = 'none';
                        YAHOO.util.Dom.get('commentEdit2'+PGPLApplication.currentComment).style.display = 'none';
                        PGPLApplication.currentComment = null;
                    }
                },
                saveComment : function (galleryId, photoId) {
                    if ( PGPLApplication.currentComment != null ) {
                        var newContent = YAHOO.util.Dom.get('commentContentTextarea'+PGPLApplication.currentComment).value;
                        if ( newContent.trim() == '' ) return false;
                        xajax_update_comment_do(galleryId, photoId, PGPLApplication.currentComment, newContent, 'PGPLApplication');
                        //PGPLApplication.cancelEditComment();
                    }
                },
                showDeleteCommentPanel : function (galleryId, photoId, commentId) {
                    PGPLApplication.currentDelGallery = galleryId;
                    PGPLApplication.currentDelPhoto = photoId;
                    PGPLApplication.currentDelComment = commentId;
                                        
                    popup_window.target('deleteCommentPanel');
                    popup_window.width(450);
                    popup_window.height(350);
                    popup_window.open();
                },
                showDeleteCommentPanelHandle : function () {
                    xajax_delete_comment_do(PGPLApplication.currentDelGallery, PGPLApplication.currentDelPhoto, PGPLApplication.currentDelComment, 'PGPLApplication');
                    popup_window.close();
                },
                hideDeleteCommentPanel : function () {
                	popup_window.close();
                },
                /**
                 * Edit Photo Methods
                 */
                showEditPhotoPanel : function (galleryId, photoId) {
                    xajax_edit_photo(galleryId, photoId, 'PGPLApplication');
                },
                showEditPhotoPanelHandle : function () {
					var callback = {
						success: PGPLApplication.handleEditPhotoPanelResponse
					}
					var oForm = YAHOO.util.Dom.get('editPhotoForm');
					YAHOO.util.Connect.setForm(oForm);
					var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
                },
                handleEditPhotoPanelResponse : function (oResponse) {
                    xajax.processResponse(oResponse.responseXML);
                },
                hideEditPhotoPanel : function () {
                	popup_window.close();
                },
                /**
                 * Delete Photo Methods
                 */
                showDeletePhotoPanel : function (galleryId, photoId/*, pWidth, pHeight*/) {
                    PGPLApplication.currentDelGallery = galleryId;
                    PGPLApplication.currentDelPhoto = photoId;
                    var pWidth = null;
                    var pHeight = null;

                    if ( typeof arguments[2] !== 'undefined' )
                        pWidth = arguments[2];
                    if ( typeof arguments[3] !== 'undefined' )
                        pHeight = arguments[3];

                    popup_window.target('deletePhotoPanel');
                    popup_window.width((pWidth != null) ? pWidth : 450);
                    popup_window.height((pHeight != null) ? pHeight :350);
                    popup_window.open(); 
                },
                showDeletePhotoPanelHandle : function () {
                    xajax_delete_photo(PGPLApplication.currentDelGallery, PGPLApplication.currentDelPhoto, 'PGPLApplication');
                    popup_window.close();
                },
                hideDeletePhotoPanel : function () {
                	popup_window.close();
                },
                /**
                 * Gallery Unshare Methods
                 */
                showPublishPanel : function (galleryId) {
                    PGPLApplication.publishGallery = galleryId;
                    xajax_publish(galleryId);
                },
                showPublishPanelHandle : function (groupId) {
                    PGPLApplication.publishGroup = groupId;
                    xajax_publish_do(PGPLApplication.publishGallery, PGPLApplication.publishGroup, 'PGPLApplication');
                    PGPLApplication.hidePublishPanel();
                },
                hidePublishPanel : function () {
                	popup_window.close();
                },
                /**
                 * Gallery Move to Methods
                 */
                showMoveToPanel : function (mediaId) {
                    PGPLApplication.mediaMoveTo = mediaId;
                    xajax_moveto(mediaId);
                },
                showMoveToHandle : function (galleryId) {
                    PGPLApplication.galleryMoveTo = galleryId;
                    xajax_moveto_do(PGPLApplication.mediaMoveTo, PGPLApplication.galleryMoveTo, 'PGPLApplication');
                    PGPLApplication.hideMoveToPanel();
                },
                hideMoveToPanel : function () {
                	popup_window.close();
                },
                /**
                 * Gallery Unshare Methods
                 */
                showUnsharePanel : function (galleryId) {
                    PGPLApplication.galleryId = galleryId;
                                        
                    popup_window.target('unsharePanel');
                    popup_window.width(450);
                    popup_window.height(350);
                    popup_window.open(); 
                },
                showUnsharePanelHandle : function () {
                    xajax_unshare_do(PGPLApplication.galleryId, 'PGPLApplication');
                    popup_window.close();
                },
                hideUnSharePanel : function () {
                	popup_window.close();
                }
            }
        }();
    };
