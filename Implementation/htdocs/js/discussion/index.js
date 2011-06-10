
    var DynamicContentDivsId = new Array();
	DynamicContentDivs = {};
	DynamicContentDiv = function(discussion_id) {
		if ( !YAHOO.util.Dom.get("Discussion" + discussion_id + "TopicList") ) return;
		this.id = "Discussion" + discussion_id + "TopicList";
		this.discussion_id = discussion_id;
		container = YAHOO.util.Dom.get(this.id);
		container.scrollTop = 0;
		YAHOO.util.Event.onAvailable(this.id, this.eventScrollListener, this);
		DynamicContentDivs[this.id] = this;
	};
	DynamicContentDiv.prototype = {
		id : null,
		discussion_id : null,
		position : 0,
		currentPage : 1,
		ready : true,
		eventScrollListener : function (obj) {
			if ( this.ready == true ) {
				var prevst = this.position;
				this.position = YAHOO.util.Dom.get(this.id).scrollTop;
				if (prevst != this.position) {
					container = YAHOO.util.Dom.get(this.id);
					containerDiv = YAHOO.util.Dom.get(this.id + 'Div');
					containerDivHeight = (containerDiv.offsetHeight) ? containerDiv.offsetHeight : containerDiv.clientHeight;
					var delta1 = containerDivHeight - container.clientHeight;
					var top = container.scrollTop;
					if ( delta1 <= container.scrollTop) {
						this.ready = false;
						obj.onScrollToEnd();
						//container.scrollTop = top;
					}
				}
			}
			setTimeout((function() { obj.eventScrollListener(obj); }), "200");
		},
		onScrollToEnd : function() {
			xajax_load_topics(this.discussion_id, this.currentPage);
		},
		onRequestEnd : function() {
			this.currentPage++;
			this.ready = true;
		}
	}
	YAHOO.util.Event.onDOMReady(initDynamicContentDivs);
	function initDynamicContentDivs() {
		if ( DynamicContentDivsId.length > 0 ) {
			for (var i = 0; i < DynamicContentDivsId.length; i++) {
				new DynamicContentDiv(DynamicContentDivsId[i]);
			}
		}
	}
	/*****************************************
	*
	*
	******************************************/
	var TooltipObj = null;
	var TooltipObjShowTimer = null;
	var TooltipObjHideTimer = null;
	function showTooltip(e, obj) {
		clearTimeout(TooltipObjShowTimer);
		TooltipObjShowTimer = setTimeout(function(){DoShowTooltip(e,obj);}, 1000);
	}
	function DoShowTooltip(e, obj) {
		clearTimeout(TooltipObjHideTimer);
		if ( !TooltipObj ) {
			TooltipObj = document.createElement("div");
			TooltipObj.innerHTML = "************************";
			YAHOO.util.Dom.setStyle(TooltipObj, "border", "1px solid gray");
			YAHOO.util.Dom.setStyle(TooltipObj, "width", "400px");
			YAHOO.util.Dom.setStyle(TooltipObj, "height", "200px");
			YAHOO.util.Dom.setStyle(TooltipObj, "position", "absolute");
			YAHOO.util.Dom.setStyle(TooltipObj, "backgroundColor", "#FFFFFF");
			document.body.appendChild(TooltipObj);
			YAHOO.util.Event.addListener(TooltipObj, "mouseover", showTooltipContent);
			YAHOO.util.Event.addListener(TooltipObj, "mousemove", showTooltipContent);
			YAHOO.util.Event.addListener(TooltipObj, "mouseout", hideTooltip, obj);
			YAHOO.util.Dom.setStyle(TooltipObj, "display", "");
		} else {
			YAHOO.util.Dom.setStyle(TooltipObj, "display", "");
		}
		var pos = YAHOO.util.Dom.getRegion(obj);
		//YAHOO.util.Dom.setStyle(TooltipObj, "top", parseInt(getMouseCoordinateY(e) + 5) + "px");
		//YAHOO.util.Dom.setStyle(TooltipObj, "left", parseInt(getMouseCoordinateX(e)) + "px");
		YAHOO.util.Dom.setStyle(TooltipObj, "top", parseInt(pos.bottom + 10) + "px");
		YAHOO.util.Dom.setStyle(TooltipObj, "left", parseInt(pos.left) + "px");

	}
	function showTooltipContent() {
		clearTimeout(TooltipObjHideTimer);
		TooltipObjHideTimer = setTimeout(doHideTooltip, 1500);
	}
	function hideTooltip(e, obj) {
		clearTimeout(TooltipObjShowTimer);
		clearTimeout(TooltipObjHideTimer);
		TooltipObjHideTimer = setTimeout(doHideTooltip, 300);
	}
	function doHideTooltip() {
		if ( TooltipObj ) {
			YAHOO.util.Dom.setStyle(TooltipObj, "display", "none");
		}
	}
	function show_hideDiscussionContent(discussion_id)
	{
		var discussionContent = YAHOO.util.Dom.get('Discussion'+discussion_id+'Content');
		if ( discussionContent ) {
			if ( discussionContent.style.display == 'none' ) {
				discussionContent.style.display = '';
				//YAHOO.util.Dom.get('Discussion'+discussion_id+'ContentLink').innerHTML = 'Hide';
				xajax_show_discussion(discussion_id, 1);
			} else {
				discussionContent.style.display = 'none';
				//YAHOO.util.Dom.get('Discussion'+discussion_id+'ContentLink').innerHTML = 'Show';
				xajax_show_discussion(discussion_id, 0);
			}
		}
	}
	function show_hideGroupContent(group_id)
	{
		var subgroupContent = YAHOO.util.Dom.get('SUBGROUP'+group_id);
		if ( subgroupContent ) {
			if ( subgroupContent.style.display == 'none' ) {
				subgroupContent.style.display = '';
				xajax_show_subgroup(group_id, 1);
			} else {
				subgroupContent.style.display = 'none';
				xajax_show_subgroup(group_id, 0);
			}
		}
	}

	var topicTooltipTimer = null;
	function show_topic_tooltip(topicId, discussionId, linkObj)
	{
		
		clearTimeout(topicTooltipTimer);
		linkObjRegion = YAHOO.util.Dom.getRegion(linkObj);
		DiscussionTopicList = YAHOO.util.Dom.get('Discussion'+discussionId+'TopicList');
		TopicTooltipContentObj = YAHOO.util.Dom.get('TopicTooltipContent');
		TooltipLinkHiddenContent = YAHOO.util.Dom.get('TooltipLinkHiddenContent'+topicId);
		if ( YAHOO.zanby.browser.BrowserDetect.browser == 'Explorer' ) {
			$offset = 0;
		} else {
			$offset = DiscussionTopicList.scrollTop;
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
	}
	
	function hide_topic_tooltip(topicId, discussionId, linkObj)
	{
		topicTooltipTimer = setTimeout(hideTopicTooltip, 300);
	}
	
	function hideTopicTooltip()
	{
		clearTimeout(topicTooltipTimer);
		TopicTooltipContentObj = YAHOO.util.Dom.get('TopicTooltipContent');
		TopicTooltipContentObj.style.display = 'none';
	}
	
	function onTooltipOver()
	{
		clearTimeout(topicTooltipTimer);
	}
	function onTooltipOut()
	{
		topicTooltipTimer = setTimeout(hideTopicTooltip, 300);
	}
