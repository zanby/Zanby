    function DynamicDiv(divId)
    {
        if ( !YAHOO.util.Dom.get(divId) ) return;
		this.galleryId = null;
        this.response = function() {
            this.currentPage = this.currentPage + 1;
            this.container = YAHOO.util.Dom.get(this.divId);
            this.dataContainer = YAHOO.util.Dom.get(this.divId + 'Div');
            obj = this; 
            timeout = setTimeout('obj.Listen();', "2000"); 
        };
        this.currentPage = 1;
        this.divId = divId;        
        this.container = YAHOO.util.Dom.get(divId);
        this.dataContainer = YAHOO.util.Dom.get(this.divId + 'Div');      
        if (!this.container) return;      
        this.Listen = eventScrollListener;
    }
    
    function eventScrollListener()
    {
        container = this.container;
        dataContainer = this.dataContainer;

        height = (dataContainer.offsetHeight)?dataContainer.offsetHeight:dataContainer.clientHeight;
        if (height <= container.clientHeight) return;
        if (container.scrollTop + container.clientHeight >= height) {
            clearTimeout(timeout);
			xajax_show_tmb_page(this.currentPage + 1, this.galleryId);                              
            return;
        }	   
        obj = this;
        timeout = setTimeout('obj.Listen();', "2000");         
    }
