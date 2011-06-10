	function turnAllSubscriptionChecked(obj) 
	{
		if ( obj.checked == true ) {
			YAHOO.util.Dom.get('digest_type_value_all').disabled = 'disabled';
			//YAHOO.util.Dom.get('group_as_one').disabled = 'disabled';
			var block = YAHOO.util.Dom.get('DiscussionsSubscriptionBlock');
			var selectColl = block.getElementsByTagName('select');
			if ( selectColl.length > 0 ) {
				for ( var i = 0; i < selectColl.length; i++ ) {
					selectColl[i].disabled = 'disabled';
				}	
			}
			var block = YAHOO.util.Dom.get('TopicsSubscriptionBlock');
			if ( block ) {
				var selectColl = block.getElementsByTagName('select');
				if ( selectColl.length > 0 ) {
					for ( var i = 0; i < selectColl.length; i++ ) {
						selectColl[i].disabled = 'disabled';
					}	
				}
			}		
		}
	};
	
	function allowAllSubscriptionChecked(obj)
	{
		if ( obj.checked == true ) {
			YAHOO.util.Dom.get('digest_type_value_all').disabled = false;
			//YAHOO.util.Dom.get('group_as_one').disabled =  false;
			var block = YAHOO.util.Dom.get('DiscussionsSubscriptionBlock');
			var selectColl = block.getElementsByTagName('select');
			if ( selectColl.length > 0 ) {
				for ( var i = 0; i < selectColl.length; i++ ) {
					selectColl[i].disabled = 'disabled';
				}	
			}
			var block = YAHOO.util.Dom.get('TopicsSubscriptionBlock');
			if ( block ) {
				var selectColl = block.getElementsByTagName('select');
				if ( selectColl.length > 0 ) {
					for ( var i = 0; i < selectColl.length; i++ ) {
						selectColl[i].disabled = 'disabled';
					}	
				}
			}
		}
	};
	
	function allowCustomSubscriptionChecked(obj)
	{
		if ( obj.checked == true ) {
			YAHOO.util.Dom.get('digest_type_value_all').disabled = 'disabled';
			//YAHOO.util.Dom.get('group_as_one').disabled = 'disabled';
			var block = YAHOO.util.Dom.get('DiscussionsSubscriptionBlock');
			var selectColl = block.getElementsByTagName('select');
			if ( selectColl.length > 0 ) {
				for ( var i = 0; i < selectColl.length; i++ ) {
					selectColl[i].disabled = false;
				}	
			}
			var block = YAHOO.util.Dom.get('TopicsSubscriptionBlock');
			if ( block ) {
			var selectColl = block.getElementsByTagName('select');
				if ( selectColl.length > 0 ) {
					for ( var i = 0; i < selectColl.length; i++ ) {
						selectColl[i].disabled = false;
					}	
				}
			}
		}
	};