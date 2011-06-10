	var initSearchTxt = false;
	function searchSubmit()
	{
	    if ( !initSearchTxt ||  YAHOO.util.Dom.get('keywordsStr').value == '' ) {
	       alert('Enter Keyword Search');
	       return false;
	    }
	    document.getElementById('searchForm').submit();
	}
	function initSearch()
	{
	    if ( !initSearchTxt ) {
	        YAHOO.util.Dom.get('keywordsStr').value = '';
            initSearchTxt = true;
	    }
	}