    var PGLApplication = null;
    if ( !PGLApplication ) {
        PGLApplication = function () {
            return {
                galleryId : null,
                oShareMenu : null,
                shareMenuPanel : null,
                //deletePanel : null,
                //unsharePanel : null,
               // stopWatchingPanel : null,
                init : function () {
                    var handlerShowShareGroup = {
                        fn: PGLApplication.showShareGroup,
                        obj: PGLApplication,
                        scope: null
                    }
                    var handlerShowShareFriends = {
                        fn: PGLApplication.showShareFriends,
                        obj: PGLApplication,
                        scope: null
                    }
                    var handlerShowShareHistory = {
                        fn: PGLApplication.showShareHistory,
                        obj: PGLApplication,
                        scope: null
                    }
                    PGLApplication.oShareMenu = new YAHOO.widget.Menu("ShareMenu");
                    PGLApplication.oShareMenu.addItems([
                            { text: "Share with Group or Group Family",  onclick: handlerShowShareGroup },
                            { text: "Share with Friend",  onclick: handlerShowShareFriends },
                            { text: "Share History",  onclick: handlerShowShareHistory },
                        ]);
                    PGLApplication.oShareMenu.render("shareMenuTarget");
                    /**/
                    PGLApplication.shareMenuPanel = new YAHOO.widget.Panel('shareMenuPanel',
                        {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    PGLApplication.shareMenuPanel.render();
                    /**/
                    //PGLApplication.deletePanel = new YAHOO.widget.Panel('deletePanel',
                    //    {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    //);
                    //PGLApplication.deletePanel.render();
                    /**/
                    //PGLApplication.unsharePanel = new YAHOO.widget.Panel('unsharePanel',
                    //    {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    //);
                    //PGLApplication.unsharePanel.render();
                    /**/
                    //PGLApplication.stopWatchingPanel = new YAHOO.widget.Panel('stopWatchingPanel',
                    //    {width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    //);
                    //PGLApplication.stopWatchingPanel.render();

                },
				/**
				 * Share and Share History Methods
				 */
				disableSaherHistoryView : function () {
					var item = PGLApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", true);
				},
				enableSaherHistoryView : function () {
					var item = PGLApplication.oShareMenu.getItem(2);
					item.cfg.setProperty("disabled", false);
				},
                showShareMenu : function (obj, galleryId, groupId) {
					/*if ( YAHOO.util.Dom.get('showHistory'+galleryId) && YAHOO.util.Dom.get('showHistory'+galleryId).value == 1 ) {
						PGLApplication.enableSaherHistoryView();
					} else {
						PGLApplication.disableSaherHistoryView();
					}
                    PGLApplication.galleryId = galleryId;
                    YAHOO.util.Dom.get('shareMenuTarget').style.display = '';
                    var region = YAHOO.util.Dom.getRegion(obj);
                    PGLApplication.oShareMenu.cfg.setProperty("x", region.left);
                    PGLApplication.oShareMenu.cfg.setProperty("y", region.top + 20);
                    PGLApplication.oShareMenu.cfg.setProperty("width", "230px");
                    PGLApplication.oShareMenu.show();*/
					PGLApplication.galleryId = galleryId;
					if(typeof arguments[3] !== 'undefined')
						PGLApplication.showShareNew(groupId, arguments[3]);
					else
					    PGLApplication.showShareNew(groupId);
					//PGLApplication.showShareGroup();
                },
                showShareGroup : function(e, a, obj) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    if ( groupsForShare ) groupsForShare.selectedIndex = 0;
                    xajax_share_group(PGLApplication.galleryId, 'PGLApplication');
                },
                showShareGroupHandle : function(galleryId) {
                    var groupsForShare = YAHOO.util.Dom.get('groupsForShare');
                    var groupsForShareValue = groupsForShare.options[groupsForShare.selectedIndex].value;
                    if ( groupsForShareValue == 0 ) return false;
                    popup_window.close();
                    xajax_share_group_do(galleryId, groupsForShareValue, 'PGLApplication');
                },
                showShareFriends : function(e, a, obj) {
                    xajax_share_friend(PGLApplication.galleryId, 'PGLApplication');
                },
                showShareNew : function(groupId) {
                    if (typeof arguments[1] !== 'undefined')
                		xajax_share_group(PGLApplication.galleryId, groupId, 'PGLApplication', '<xjxquery><q>commcontext=' + arguments[1] + '</q></xjxquery>');
					else{
					    xajax_share_group(PGLApplication.galleryId, groupId, 'PGLApplication');
					}
                },
                showShareFriendsHandle : function (galleryId) {
                    if ( YAHOO.util.Dom.get('shareFriendMode1').checked == true ) {
                        var request = {};
                        request.users = 0;
                        request.subject = YAHOO.util.Dom.get('shareFriendSubject').value;
                        request.message = YAHOO.util.Dom.get('shareFriendMessage').value;
                        popup_window.close();
                        xajax_share_friend_do(galleryId, request, 'PGLApplication');
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
                        xajax_share_friend_do(galleryId, request, 'PGLApplication');
                    } else {
                        return false;
                    }

                },
                shareFriendModeChanged : function (value) {
                    if ( value == 1 ) YAHOO.util.Dom.get("shareFriendMode2AddFields").style.display = "none";
                    else YAHOO.util.Dom.get("shareFriendMode2AddFields").style.display = "";
                },
                showShareHistory : function() {
					if ( YAHOO.util.Dom.get('unshareForm') ) {
						var oForm = YAHOO.util.Dom.get('unshareForm');
						if ( oForm.length != 0 ) {
							for (var i = 0; i < oForm.length; i++) {
								if ( oForm.elements[i].type == 'checkbox' ) oForm.elements[i].checked = false;
							}
						}
					}
					xajax_show_share_history(PGLApplication.galleryId, 'PGLApplication');
                },
				showShareHistoryHandle : function () {
                	popup_window.close();
					var callback = {
						success: PGLApplication.handleUnShareResponse
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
                 * Gallery Delete Methods
                 */
                showDeletePanel : function (galleryId/*, commContext, panelWidth, panelHeight*/) {
                    PGLApplication.commContext = null;
                    var pWidth = null;
                    var pHeight = null;

					if (typeof arguments[1] !== 'undefined')
                        PGLApplication.commContext = arguments[1];
                    if ( typeof arguments[2] !== 'undefined' )
                        pWidth = arguments[2];
                    if ( typeof arguments[3] !== 'undefined' )
                        pHeight = arguments[3];

                    PGLApplication.galleryId = galleryId;

                    popup_window.target('deletePanel');
                    popup_window.width((pWidth != null) ? pWidth : 450);
                    popup_window.height((pHeight != null) ? pHeight : 350);
                    popup_window.open();

                },
                showDeletePanelHandle : function () {
					if (PGLApplication.commContext !== null) {
                    	xajax_delete_gallery(PGLApplication.galleryId, 'PGLApplication', '<xjxquery><q>commcontext=' + PGLApplication.commContext + '</q></xjxquery>');
					}else{
                    xajax_delete_gallery(PGLApplication.galleryId, 'PGLApplication');
					}
					popup_window.close();
                },
                hideDeletePanel : function() {
                    PGLApplication.commContext = null;
                    popup_window.close();
                },
                /**
                 * Gallery Unshare Methods
                 */
                showUnsharePanel : function (galleryId) {
                    PGLApplication.commContext = null;
					if (typeof arguments[1] !== 'undefined') {
                        PGLApplication.commContext = arguments[1];
					}
                    PGLApplication.galleryId = galleryId;

                	popup_window.target('unsharePanel');
                    popup_window.width(450);
                    popup_window.height(350);
                    popup_window.open();

                },
                showUnsharePanelHandle : function () {
					if (PGLApplication.commContext !== null) {
                    	xajax_unshare_do(PGLApplication.galleryId, 'PGLApplication', '<xjxquery><q>commcontext=' + PGLApplication.commContext + '</q></xjxquery>');
					}else{
                    xajax_unshare_do(PGLApplication.galleryId, 'PGLApplication');
					}
					popup_window.close();
                },
                hideUnSharePanel : function () {
                	PGLApplication.commContext = null;
                	popup_window.close();
                },
                /**
                 * Gallery stop watching methods
                 */
                showStopWatchingPanel : function (galleryId) {

                	PGLApplication.galleryId = galleryId;

                	popup_window.target('stopWatchingPanel');
                    popup_window.width(450);
                    popup_window.height(350);
                    popup_window.open();

                },
                showStopWatchingPanelHandle : function (galleryId) {
                    xajax_stop_watching_do(PGLApplication.galleryId, 'PGLApplication');
                    popup_window.close();
                },
                hideStopWatchingPanel : function () {
                	popup_window.close();
                }
            }
        }();
    };
