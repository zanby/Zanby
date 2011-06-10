$(function(){
   FB.init({
     appId  : FACEBOOK_APP_ID,
     status : true, // check login status
     cookie : true, // enable cookies to allow the server to access the session
     xfbml  : true  // parse XFBML
   });
	//FB.init(FACEBOOK_API_KEY, "/xd_receiver.htm", {"doNotUseCachedConnectState":true});
})

var FBCfg = null;
if ( !FBCfg ) {
	FBCfg = function () {
		return {
			request_permissions_on_login : false,
			url_change_connection_state : null,
			url_onlogin_ready : null,
			url_do_signup : null,
			url_onlink_ready : null,
			url_onunlink: null,
			url_onremove_permission: null,
			url_checksessionstate : null,
			url_oninvite_friends_toevent : null,
			url_onremove_from_eventinvite : null,
			url_onrsvplogin_ready : null,
			url_oncheck_rsvp_status_ready : null
		}
	}();
};

var FBApplication = null;
if ( !FBApplication ) {
	FBApplication = function () {
		return {
			connectionState : null,
			targetsToInvite : [], 
			check_seesion_state : function (callback) {
				$.ajax({ type: "POST", url: FBCfg.url_checksessionstate, dataType: 'json', async: false,
					success: function(data){
						if ( !data ) { document.location.reload(); }
						else callback();						
					}
				});
			},
			update_user_is_connected : function () {
				alert('Now user is connected!');
				/*
				$.post(FBCfg.url_change_connection_state, {state: 1}, function(data) { 
					xajax.processResponse(data); 
				}, 'xml');
				*/
			},
			update_user_is_not_connected : function () {
				alert('Now user is NOT connected!');
				/*
				$.post(FBCfg.url_change_connection_state, {state: 0}, function(data) { 
					xajax.processResponse(data); 
				}, 'xml');
				*/
			},
			do_signup : function () {
				//FB.ensureInit(function() { 
					FB.Connect.requireSession(function(){
						if ( FBCfg.request_permissions_on_login ) {
							FB.Connect.showPermissionDialog("publish_stream,email", function() {
								document.location.href = FBCfg.url_do_signup;
							}); 
						} else {
							document.location.href = FBCfg.url_do_signup;
						}
					});
				//});
			},
			onlogin_ready : function () { 
				//FB.ensureInit(function() { 
					//FB.Connect.requireSession(function(){
						if ( FBCfg.request_permissions_on_login ) {
							FB.Connect.showPermissionDialog("publish_stream,email", function() {
								document.location.href = FBCfg.url_onlogin_ready;
							}); 
						} else {
							document.location.href = FBCfg.url_onlogin_ready;
						}
					//});
				//});
			},
			onlink_ready : function () {
				//FB.ensureInit(function() { 
					//FB.Connect.requireSession(function(){
						if ( FBCfg.request_permissions_on_login ) {
							FB.Connect.showPermissionDialog("publish_stream,email", function() {
								$.post(FBCfg.url_onlink_ready, {}, function(data) { 
									xajax.processResponse(data); 
								}, 'xml');
							}); 
						} else {
							$.post(FBCfg.url_onlink_ready, {}, function(data) { 
								xajax.processResponse(data); 
							}, 'xml');
						}
					//});
				//});
			},
			facebook_logout : function() {
				FB.Bootstrap.requireFeatures(["Connect"], function() {
                   FB.init({
                     appId  : FACEBOOK_APP_ID,
                     status : true, // check login status
                     cookie : true, // enable cookies to allow the server to access the session
                     xfbml  : true  // parse XFBML
                   });
					FB.Connect.logout(function(){});
				});				
			},
			onunlink : function () {
                popup_window.target('unlinkPanel'); 
                popup_window.width(350); popup_window.height(80);
                popup_window.open();
				$("#btnUnlinkFormSubmit").unbind().bind('click', function(){
					$.post(FBCfg.url_onunlink, {}, function(data) { 
						xajax.processResponse(data); 
						popup_window.close();
					}, 'xml');
					return false;
				})
			},
			onallow_permission : function (itemID, permission) {
				FBApplication.check_seesion_state(function() {
					//FB.ensureInit(function() { 
						FB.Connect.requireSession(function(){
							FB.Connect.showPermissionDialog(permission, function(graned) {
								FBApplication.onupdate_permission_ready(itemID, permission, graned);
								$("#"+itemID).attr('disabled', false);
							}); 
						});
					});
				//});
			},
			onremove_permission : function (itemID, permission) {
				FBApplication.check_seesion_state(function() {
					//FB.ensureInit(function() { 
						FB.Connect.requireSession(function(){
							$.post(FBCfg.url_onremove_permission, {permission: permission, itemID: itemID}, function(data) { 
								xajax.processResponse(data); 
								$("#"+itemID).attr('disabled', false);
							}, 'xml');
						});
					//});
				});
			},
			onupdate_permission_ready : function (itemID, permission, graned) {
				if ( graned ) { $("#"+itemID).attr('checked', true); }
				else { $("#"+itemID).attr('checked', false); }
				FBApplication.set_permission_handler(itemID, permission);
			},
			set_permission_handler : function (itemID, permission) {
				if ( $("#"+itemID).attr('checked') ) {
					$("#"+itemID).unbind().bind('click', function() {
						$("#"+itemID).attr('disabled', true);
						FBApplication.onremove_permission(itemID, permission);
						return false;
					})		
				} else {
					$("#"+itemID).unbind().bind('click', function() {
						$("#"+itemID).attr('disabled', true);
						FBApplication.onallow_permission(itemID, permission);						
						return false;
					})		
				}
			},
			onsign_out : function () {
				FB.Bootstrap.requireFeatures(["Connect"], function() {
                   FB.init({
                     appId  : FACEBOOK_APP_ID,
                     status : true, // check login status
                     cookie : true, // enable cookies to allow the server to access the session
                     xfbml  : true  // parse XFBML
                   });
					FB.Connect.logout(function(){
						document.location.reload();
					});
				});
			},
			onpublish_stream : function (postObj) {
				th_show_overlay();
				//FB.ensureInit(function() { 
					FB.Connect.requireSession(function(){
						FB.Connect.streamPublish(postObj.message, postObj.attachment, postObj.action_links, postObj.target_id, postObj.user_message_prompt, function() {
							th_hide_overlay();
						});
					});
				//});
			},
			onpublish_feed : function (postObj) {
				th_show_overlay(); 
				//FB.ensureInit(function() { 
					FB.Connect.requireSession(function(){
						postObj.body_general = ( postObj.body_general ) ? postObj.body_general : '';
						FB.Connect.showFeedDialog(postObj.template_bundle_id, postObj.template_data, postObj.target_id, postObj.body_general, null, FB.RequireConnect.require, function(status){
							th_hide_overlay();
						}, postObj.user_message_prompt, postObj.user_message);
					});
				//});
			},
			oninvite_friends_toevent : function (mode) {
				//FBApplication.check_seesion_state(function() {
                $(document.body).css('cursor', 'wait');
				//	FB.ensureInit(function() { 
						FB.Connect.requireSession(function(){
							xajax.loadingFunction();
							$.post(FBCfg.url_oninvite_friends_toevent, {'invited[]' : FBApplication.targetsToInvite, mode: mode}, function(data) { 
								xajax.processResponse(data); 
								xajax.doneLoadingFunction();
							}, 'xml');
						});
				//	});
				//});
			},
			oninvite_friends_toevent_handle : function (formName) {
				xajax.loadingFunction();
				var callback = {success: FBApplication.oninvite_friends_toevent_ready}
				var oForm = YAHOO.util.Dom.get(formName);
				YAHOO.util.Connect.setForm(oForm);
				var cObj = YAHOO.util.Connect.asyncRequest('POST', $('#'+formName).attr('action'), callback);
				return false;
			},
			oninvite_friends_toevent_ready : function (oResponse) {
				xajax.processResponse(oResponse.responseXML);
				xajax.doneLoadingFunction();
			},
			onremove_from_eventinvite : function (uid, confirm) {
				xajax.loadingFunction();
				$.post(FBCfg.url_onremove_from_eventinvite, {uid: uid, 'invited[]' : FBApplication.targetsToInvite, confirm: confirm}, function(data) { 
					xajax.processResponse(data); 
					xajax.doneLoadingFunction();
				}, 'xml');
			},
			onrsvplogin_ready : function (eventId, eventUid, viewMode) {
				xajax_doAttendeeEventSignup(eventId, eventUid, viewMode);
			},
			onrsvplogin_ready_zccf : function (eventId, eventUid, viewMode) {
				xajax_doAttendeeEventSignup(eventId, eventUid, viewMode, true);
			},
			/*
			 FB.ConnectState.prototype = {
			   connected: 1,
			   userNotLoggedIn: 2,
			   appNotAuthorized: 3
			 }
			*/
			check_rsvp_status : function (event_id, event_uid) {
			//	FB.ensureInit(function() {
					FB.Connect.get_status().waitUntilReady(function(status) {		
						if ( status == FB.ConnectState.connected ) {
							FBApplication.oncheck_rsvp_status_session_ready(event_id, event_uid);
						} else if ( status == FB.ConnectState.appNotAuthorized ) {
							//FB.Connect.requireSession(function() { FBApplication.oncheck_rsvp_status_session_ready(event_id, event_uid); });
						}
					});
				//});
			}, 
			oncheck_rsvp_status_session_ready : function (event_id, event_uid) {
				//FB.ensureInit(function() {
					var fbu = FB.Facebook.apiClient.get_session() ?  FB.Facebook.apiClient.get_session().uid : 0;
					FBApplication.oncheck_rsvp_status_ready(fbu, event_id, event_uid);
				//});
			},
			oncheck_rsvp_status_ready : function (fbu, event_id, event_uid) {			
				if ( event_id != 0 && event_uid != 0 ) {
					$.post(FBCfg.url_oncheck_rsvp_status_ready, {fbu: fbu, event_id: event_id, event_uid: event_uid}, function(data) { 
						xajax.processResponse(data); 
					}, 'xml');
				} else {
					$.post(FBCfg.url_oncheck_rsvp_status_ready, {fbu: fbu}, function(data) { 
						xajax.processResponse(data); 
					}, 'xml');
				}
			}
		}
	}();
};
