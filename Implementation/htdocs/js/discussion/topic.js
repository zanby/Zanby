
	function reply_post(post_id, currentPage, sortmode) {
		var post = YAHOO.util.Dom.get("Post"+post_id)
		var req = YAHOO.util.Dom.getRegion(post);
		xajax_reply_post(req.left, req.bottom, post_id, currentPage, sortmode);
	}
	function reply_post_do() {
		var post_id = YAHOO.util.Dom.get("post_id").value;
		var content = YAHOO.util.Dom.get("content").value;
		var currentPage = YAHOO.util.Dom.get("currentPage").value;
		var sortmode = YAHOO.util.Dom.get("sortmode").value;
		/**
        * Changed according Bug #3042
		var subscription = YAHOO.util.Dom.get("subscription").options[YAHOO.util.Dom.get("subscription").selectedIndex].value;
        */
        var subscription = -1;
		xajax_reply_post_do(post_id, content, subscription, currentPage, sortmode);
	}
    function save_post_reply(post_id) {
		var content = YAHOO.util.Dom.get("content").value;
		xajax_save_post_reply(post_id, content);
	}
	function edit_post(post_id) {
		var post = YAHOO.util.Dom.get("Post"+post_id)
		var req = YAHOO.util.Dom.getRegion(post);
		xajax_edit_post(req.left, req.bottom, post_id);
	}
	function edit_post_do() {
		var post_id = YAHOO.util.Dom.get("post_id").value;
		var content = YAHOO.util.Dom.get("content").value;
		/**
        * Changed according Bug #3042
		var subscription = YAHOO.util.Dom.get("subscription").options[YAHOO.util.Dom.get("subscription").selectedIndex].value;
        */
        var subscription = -1;
		xajax_edit_post_do(post_id, content, subscription);
	}
	function delete_post(post_id) {
		var post = YAHOO.util.Dom.get("Post"+post_id)
		var req = YAHOO.util.Dom.getRegion(post);
		xajax_delete_post(req.left, req.bottom, post_id);
	}
	function delete_post_do() {
		var post_id = YAHOO.util.Dom.get("post_id").value;
		xajax_delete_post_do(post_id);
		popup_window.close();
	}
	function email_author(post_id) {
		var post = YAHOO.util.Dom.get("Post"+post_id)
		var req = YAHOO.util.Dom.getRegion(post);
		xajax_email_author(req.left, req.bottom, post_id);
	}
	function email_author_do() {
		var post_id = YAHOO.util.Dom.get("post_id").value;
		var content = YAHOO.util.Dom.get("content").value;
		xajax_email_author_do(post_id, content);
	}
	function report_post(post_id) {
		var post = YAHOO.util.Dom.get("Post"+post_id)
		var req = YAHOO.util.Dom.getRegion(post);
		xajax_report_post(req.left, req.bottom, post_id);
	}
	function report_post_do() {
		var post_id = YAHOO.util.Dom.get("post_id").value;
		xajax_report_post_do(post_id);
		popup_window.close();
	}
	function notify_topic(topic_id) {
		var link = YAHOO.util.Dom.get("notify_topic")
		var req = YAHOO.util.Dom.getRegion(link);
		xajax_notify_topic(req.left, req.bottom, topic_id);
	}
	function notify_topic_do(topic_id) {
		var subscription = YAHOO.util.Dom.get("subscription").options[YAHOO.util.Dom.get("subscription").selectedIndex].value;
		xajax_notify_topic_do(topic_id, subscription);
		popup_window.close();
	}

	function showMenuListSize(srcItem, direct) {
		var menuListSize = YAHOO.util.Dom.get('menuListSize');
		if ( YAHOO.util.Dom.getStyle(menuListSize, 'display', '') == 'none' ) {
			if ( direct == 'bottom' ) {
				var region = YAHOO.util.Dom.getRegion(YAHOO.util.Dom.get(srcItem));
				YAHOO.util.Dom.setStyle(menuListSize, 'top', (region.bottom + 5) + 'px');
				YAHOO.util.Dom.setStyle(menuListSize, 'left', (region.right - 30) + 'px');
				YAHOO.util.Dom.setStyle(menuListSize, 'position', 'absolute');
				YAHOO.util.Dom.setStyle(menuListSize, 'display', 'block');
			}
			if ( direct == 'top' ) {
				var region = YAHOO.util.Dom.getRegion(YAHOO.util.Dom.get(srcItem));
				YAHOO.util.Dom.setStyle(menuListSize, 'top', (region.top - 205) + 'px');
				YAHOO.util.Dom.setStyle(menuListSize, 'left', (region.right - 30) + 'px');
				YAHOO.util.Dom.setStyle(menuListSize, 'position', 'absolute');
				YAHOO.util.Dom.setStyle(menuListSize, 'display', 'block');					
			}
		} else {
			YAHOO.util.Dom.setStyle(menuListSize, 'display', 'none');
		}
	}
	function hideMenuListSize() {
		var menuListSize = YAHOO.util.Dom.get('menuListSize');
		YAHOO.util.Dom.setStyle(menuListSize, 'display', 'none');
	}
	function selectMenuListSize(count, mode, topic_id)
	{
		hideMenuListSize();
		xajax_change_list_size(count, mode, topic_id);
	}
	function changeSortMode(url, topicId, sortMode)
	{
		document.location.replace(url+'topicid/'+topicId+'/sortmode/'+sortMode);
	}
	var close_dialog = function(name) {
		var dialog = xajaxRequestManager.OverlayManager.find(name);
		if ( dialog ) {
			dialog.cancel();
		}
	};
	function move_topic(topicId) {
		xajax_move_topic(topicId);
	}
	function move_topic_do(topicId) {
		var dis_id = YAHOO.util.Dom.get("discussion_id").value;
		xajax_move_topic_do(topicId, dis_id);
		popup_window.close();
	}
	function close_topic(topicId) {
		xajax_close_topic(topicId);
	}
	function reopen_topic(topicId) {
		xajax_reopen_topic(topicId);
	}
	function remove_topic(topicId) {
		xajax_remove_topic(topicId);
	}
	function remove_topic_do() {
		var topic_id = YAHOO.util.Dom.get("topic_id").value;
		xajax_remove_topic_do(topic_id);
		popup_window.close();
	}