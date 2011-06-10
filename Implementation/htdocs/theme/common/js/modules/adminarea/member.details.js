$(function(){
    MDApplication.init();
})

var MDApplication = null;
if ( !MDApplication ) {
	MDApplication = function () {
		return {
			init : function () {
				MDApplication.initAutocomplete();
			},
			/**
			 * City Autocomplete
			 */
		    m_city_fCallBackToAutocomplete : null,
		    m_city_oParent : null,
		    m_city_sQuery : null,
		    myDataSourceCity : null,
		    itemCitySelectHandler : function(sType, aArgs) {
		        YAHOO.util.Dom.get('acCity').style.display = 'none';
		    },
		    containerCityCollapseEvent : function(oSelf) {
		        YAHOO.util.Dom.get('acCity').style.display = '';
		    },
		    doQueryXajaxCity : function(fCallBack, oParent, sQuery) {
		    	MDApplication.m_city_fCallBackToAutocomplete  = fCallBack;
		    	MDApplication.m_city_oParent                  = oParent;
		    	MDApplication.m_city_sQuery                   = sQuery;
		        var country = YAHOO.util.Dom.get('countryId');
		        country = country.options[country.selectedIndex].value;
		        xajax_autoCompleteCity(country, decodeURIComponent(sQuery), "MDApplication.autocompleteCallbackCity");
		    },
		    autocompleteCallbackCity : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        MDApplication.m_city_fCallBackToAutocomplete(MDApplication.m_city_sQuery, sResponse, MDApplication.m_city_oParent);
		    },
		    /**
		     * Zip Autocomplete
		     */
		    m_zip_fCallBackToAutocomplete : null,
		    m_zip_oParent : null,
		    m_zip_sQuery : null,
		    myDataSourceZip : null,
		    itemZipSelectHandler : function(sType, aArgs) {
		        YAHOO.util.Dom.get('acZip').style.display = 'none';
		    },
		    containerZipCollapseEvent : function(oSelf) {
		        YAHOO.util.Dom.get('acZip').style.display = '';
		    },
		    doQueryXajaxZip : function(fCallBack, oParent, sQuery) {
		    	MDApplication.m_zip_fCallBackToAutocomplete   = fCallBack;
		    	MDApplication.m_zip_oParent                   = oParent;
		    	MDApplication.m_zip_sQuery                    = sQuery;
		        var country = YAHOO.util.Dom.get('countryId');
		        country = country.options[country.selectedIndex].value;
		        xajax_autoCompleteZip(country, decodeURIComponent(sQuery), "MDApplication.autocompleteCallbackZip");
		    },                           
		    autocompleteCallbackZip : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        MDApplication.m_zip_fCallBackToAutocomplete(MDApplication.m_zip_sQuery, sResponse, MDApplication.m_zip_oParent);
		    },		 
		    //
			initAutocomplete : function() {
			    YAHOO.widget.DS_XAJAX_CITY = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_CITY.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_CITY.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { MDApplication.doQueryXajaxCity(oCallbackFn, oParent, sQuery); return; }
			    MDApplication.myDataSourceCity = new YAHOO.widget.DS_XAJAX_CITY();
		        var cityAutoComp = new YAHOO.widget.AutoComplete("city", "acCity", MDApplication.myDataSourceCity);
		        cityAutoComp.maxResultsDisplayed    = 1000;
		        cityAutoComp.minQueryLength         = 1; 
		        cityAutoComp.animSpeed              = 0.01;
		        cityAutoComp.itemSelectEvent.subscribe(MDApplication.itemCitySelectHandler);
		        cityAutoComp.containerCollapseEvent.subscribe(MDApplication.containerCityCollapseEvent);
			    //
			    YAHOO.widget.DS_XAJAX_ZIP = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_ZIP.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_ZIP.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { MDApplication.doQueryXajaxZip(oCallbackFn, oParent, sQuery); return; }
			    MDApplication.myDataSourceZip = new YAHOO.widget.DS_XAJAX_ZIP();
		        var zipAutoComp = new YAHOO.widget.AutoComplete("zipId", "acZip", MDApplication.myDataSourceZip);
		        zipAutoComp.maxResultsDisplayed     = 1000;
		        zipAutoComp.minQueryLength          = 3; 
		        zipAutoComp.animSpeed               = 0.01;
		        zipAutoComp.itemSelectEvent.subscribe(MDApplication.itemZipSelectHandler);
		        zipAutoComp.containerCollapseEvent.subscribe(MDApplication.containerZipCollapseEvent);
			}
		}
	}();
};