	
	function show_hideGroupContent(group_id)
	{
		var groupContent = YAHOO.util.Dom.get('GroupDiscussions'+group_id+'Content');
		if ( groupContent ) {
			if ( groupContent.style.display == 'none' ) {
				groupContent.style.display = '';
				YAHOO.util.Dom.get('GroupDiscussions'+group_id+'ContentLink').innerHTML = 'Hide';
				YAHOO.util.Dom.addClass('GroupDiscussions'+group_id+'ContentLink', 'prArrow-down'); 
			} else {
				groupContent.style.display = 'none';
				YAHOO.util.Dom.get('GroupDiscussions'+group_id+'ContentLink').innerHTML = 'Show';
				YAHOO.util.Dom.removeClass('GroupDiscussions'+group_id+'ContentLink', 'prArrow-down');
			}
		}
	};
	
	function show_hideDiscussionContent(discussion_id)
	{
		var discussionContent = YAHOO.util.Dom.get('Discussion'+discussion_id+'Content');
		if ( discussionContent ) {
			if ( discussionContent.style.display == 'none' ) {
				discussionContent.style.display = '';
				//YAHOO.util.Dom.get('Discussion'+discussion_id+'ContentLink').innerHTML = 'Hide';
				YAHOO.util.Dom.addClass('Discussion'+discussion_id+'ContentLink', 'prArrow-down');
			} else {
				discussionContent.style.display = 'none';
				//YAHOO.util.Dom.get('Discussion'+discussion_id+'ContentLink').innerHTML = 'Show';
				YAHOO.util.Dom.removeClass('Discussion'+discussion_id+'ContentLink', 'prArrow-down');
			}
		} else {
            xajax_show_topics(discussion_id);
        }
	};
    
    function changeSortMode(value, url)
    {
        document.location.replace(url + 'sortMode/' + value + '/');
    };
    
    function exclude_topic(topic_id) {
        xajax_exclude_topic(topic_id);
    };
    
    function exclude_topic_do() {
        var topic_id = YAHOO.util.Dom.get("topic_id").value;
        xajax_exclude_topic_do(topic_id);
        popup_window.close();
    };
    
	var topicTooltipTimer = null;
	function show_topic_tooltip(topicId, discussionId, linkObj)
	{
		
		clearTimeout(topicTooltipTimer);
		linkObjRegion = YAHOO.util.Dom.getRegion(linkObj);
		DiscussionTopicList = YAHOO.util.Dom.get('Discussion'+discussionId+'TopicList');
        DiscussionCommentedTopicList = YAHOO.util.Dom.get('DiscussionCommentedTopicList');
		TopicTooltipContentObj = YAHOO.util.Dom.get('TopicTooltipContent');
		TooltipLinkHiddenContent = YAHOO.util.Dom.get('TooltipLinkHiddenContent'+topicId);
		if ( YAHOO.zanby.browser.BrowserDetect.browser == 'Explorer' ) {
			$offset = 0;
		} else {
            if ( DiscussionTopicList) $offset = DiscussionTopicList.scrollTop;
            else if ( DiscussionCommentedTopicList ) $offset = DiscussionCommentedTopicList.scrollTop;
            else $offset = 0;
		}

        var reg = /msie\s+6/i;
        if ( typeof(navigator.appVersion) != 'undefined' && reg.test(navigator.appVersion) ) {
            TopicTooltipContentObj.style.top = (linkObjRegion.bottom - $offset - 190) + 'px';
            TopicTooltipContentObj.style.left = (linkObjRegion.left - 200) + 'px';
            TopicTooltipContentObj.innerHTML = TooltipLinkHiddenContent.innerHTML;
            TopicTooltipContentObj.style.display = '';
        } else {
            TopicTooltipContentObj.style.top = (linkObjRegion.bottom - $offset + 5) + 'px';
            TopicTooltipContentObj.style.left = linkObjRegion.left + 'px';
            TopicTooltipContentObj.innerHTML = TooltipLinkHiddenContent.innerHTML;
            TopicTooltipContentObj.style.display = '';
        }
	};
	
	function hide_topic_tooltip(topicId, discussionId, linkObj)
	{
		topicTooltipTimer = setTimeout(hideTopicTooltip, 300);
	};
	
	function hideTopicTooltip()
	{
		clearTimeout(topicTooltipTimer);
		TopicTooltipContentObj = YAHOO.util.Dom.get('TopicTooltipContent');
		TopicTooltipContentObj.style.display = 'none';
	};
	
	function onTooltipOver()
	{
		clearTimeout(topicTooltipTimer);
	}
	function onTooltipOut()
	{
		topicTooltipTimer = setTimeout(hideTopicTooltip, 300);
	}
