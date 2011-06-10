$(function(){
    GDApplication.init();
})

var GDApplication = null;
if ( !GDApplication ) {
	GDApplication = function () {
		return {
			init : function () {
				$('#lnkChangeHost').unbind().bind('click', function(){ GDApplication.onChangeHost(); return false; });
				$('#lnkOnChangeHostSubmit').unbind().bind('click', function(){ GDApplication.onChangeHostSubmit(); return false; });
				$('#lnkOnChangeHostCancel').unbind().bind('click', function(){ GDApplication.onChangeHostCancel(); return false; });
				$('#lnkOnAddCoHostSubmit').unbind().bind('click', function(){ GDApplication.onAddCoHostSubmit(); return false; });
				$('#lnkOnDeleteGroup').unbind().bind('click', function(){ GDApplication.onDeleteGroup(); return false; });
				GDApplication.initAutocomplete();
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
		    	GDApplication.m_city_fCallBackToAutocomplete  = fCallBack;
		    	GDApplication.m_city_oParent                  = oParent;
		    	GDApplication.m_city_sQuery                   = sQuery;
		        var country = YAHOO.util.Dom.get('countryId');
		        country = country.options[country.selectedIndex].value;
		        xajax_autoCompleteCity(country, decodeURIComponent(sQuery), "GDApplication.autocompleteCallbackCity");
		    },
		    autocompleteCallbackCity : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        GDApplication.m_city_fCallBackToAutocomplete(GDApplication.m_city_sQuery, sResponse, GDApplication.m_city_oParent);
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
		    	GDApplication.m_zip_fCallBackToAutocomplete   = fCallBack;
		    	GDApplication.m_zip_oParent                   = oParent;
		    	GDApplication.m_zip_sQuery                    = sQuery;
		        var country = YAHOO.util.Dom.get('countryId');
		        country = country.options[country.selectedIndex].value;
		        xajax_autoCompleteZip(country, decodeURIComponent(sQuery), "GDApplication.autocompleteCallbackZip");
		    },                           
		    autocompleteCallbackZip : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        GDApplication.m_zip_fCallBackToAutocomplete(GDApplication.m_zip_sQuery, sResponse, GDApplication.m_zip_oParent);
		    },		 
		    /**
		     * New Host Autocomplete
		     */
		    m_newhost_fCallBackToAutocomplete : null,
		    m_newhost_oParent : null,
		    m_newhost_sQuery : null,
		    myDataSourceNewHost : null,
		    itemNewHostSelectHandler : function(sType, aArgs) {
		        YAHOO.util.Dom.get('acNewHost').style.display = 'none';
		    },
		    containerNewHostCollapseEvent : function(oSelf) {
		        YAHOO.util.Dom.get('acNewHost').style.display = '';
		    },
		    doQueryXajaxNewHost : function(fCallBack, oParent, sQuery) {
		    	GDApplication.m_newhost_fCallBackToAutocomplete   = fCallBack;
		    	GDApplication.m_newhost_oParent                   = oParent;
		    	GDApplication.m_newhost_sQuery                    = sQuery;
		        xajax_autoCompleteGroupMembers($('#groupID').val(), decodeURIComponent(sQuery), "GDApplication.autocompleteCallbackNewHost", ['member','cohost']);
		    },                           
		    autocompleteCallbackNewHost : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        GDApplication.m_newhost_fCallBackToAutocomplete(GDApplication.m_newhost_sQuery, sResponse, GDApplication.m_newhost_oParent);
		    },
		    /**
		     * New CoHost Autocomplete
		     */
		    m_newcohost_fCallBackToAutocomplete : null,
		    m_newcohost_oParent : null,
		    m_newcohost_sQuery : null,
		    myDataSourceNewCoHost : null,
		    itemNewCoHostSelectHandler : function(sType, aArgs) {
		        YAHOO.util.Dom.get('acNewCoHost').style.display = 'none';
		    },
		    containerNewCoHostCollapseEvent : function(oSelf) {
		        YAHOO.util.Dom.get('acNewCoHost').style.display = '';
		    },
		    doQueryXajaxNewCoHost : function(fCallBack, oParent, sQuery) {
		    	GDApplication.m_newcohost_fCallBackToAutocomplete   = fCallBack;
		    	GDApplication.m_newcohost_oParent                   = oParent;
		    	GDApplication.m_newcohost_sQuery                    = sQuery;
		        xajax_autoCompleteGroupMembers($('#groupID').val(), decodeURIComponent(sQuery), "GDApplication.autocompleteCallbackNewCoHost", ['member']);
		    },                           
		    autocompleteCallbackNewCoHost : function(sResponse)  {
		        if (sResponse.constructor != Array) sResponse = new Array();
		        GDApplication.m_newcohost_fCallBackToAutocomplete(GDApplication.m_newcohost_sQuery, sResponse, GDApplication.m_newcohost_oParent);
		    },
		    //
			initAutocomplete : function() {
			    YAHOO.widget.DS_XAJAX_CITY = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_CITY.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_CITY.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { GDApplication.doQueryXajaxCity(oCallbackFn, oParent, sQuery); return; }
			    GDApplication.myDataSourceCity = new YAHOO.widget.DS_XAJAX_CITY();
		        var cityAutoComp = new YAHOO.widget.AutoComplete("city", "acCity", GDApplication.myDataSourceCity);
		        cityAutoComp.maxResultsDisplayed    = 1000;
		        cityAutoComp.minQueryLength         = 1; 
		        cityAutoComp.animSpeed              = 0.01;
		        cityAutoComp.itemSelectEvent.subscribe(GDApplication.itemCitySelectHandler);
		        cityAutoComp.containerCollapseEvent.subscribe(GDApplication.containerCityCollapseEvent);
			    //
			    YAHOO.widget.DS_XAJAX_ZIP = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_ZIP.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_ZIP.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { GDApplication.doQueryXajaxZip(oCallbackFn, oParent, sQuery); return; }
			    GDApplication.myDataSourceZip = new YAHOO.widget.DS_XAJAX_ZIP();
		        var zipAutoComp = new YAHOO.widget.AutoComplete("zipId", "acZip", GDApplication.myDataSourceZip);
		        zipAutoComp.maxResultsDisplayed     = 1000;
		        zipAutoComp.minQueryLength          = 3; 
		        zipAutoComp.animSpeed               = 0.01;
		        zipAutoComp.itemSelectEvent.subscribe(GDApplication.itemZipSelectHandler);
		        zipAutoComp.containerCollapseEvent.subscribe(GDApplication.containerZipCollapseEvent);
			    //
			    YAHOO.widget.DS_XAJAX_NEWHOST = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_NEWHOST.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_NEWHOST.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { GDApplication.doQueryXajaxNewHost(oCallbackFn, oParent, sQuery); return; }
			    GDApplication.myDataSourceNewHost = new YAHOO.widget.DS_XAJAX_NEWHOST();
		        var newhostAutoComp = new YAHOO.widget.AutoComplete("newHost", "acNewHost", GDApplication.myDataSourceNewHost);
		        newhostAutoComp.maxResultsDisplayed     = 1000;
		        newhostAutoComp.minQueryLength          = 1; 
		        newhostAutoComp.animSpeed               = 0.01;
		        newhostAutoComp.itemSelectEvent.subscribe(GDApplication.itemNewHostSelectHandler);
		        newhostAutoComp.containerCollapseEvent.subscribe(GDApplication.containerNewHostCollapseEvent);
			    //
			    YAHOO.widget.DS_XAJAX_NEWCOHOST = function() { this._init(); };
			    YAHOO.widget.DS_XAJAX_NEWCOHOST.prototype = new YAHOO.widget.DataSource();
			    YAHOO.widget.DS_XAJAX_NEWCOHOST.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { GDApplication.doQueryXajaxNewCoHost(oCallbackFn, oParent, sQuery); return; }
			    GDApplication.myDataSourceNewCoHost = new YAHOO.widget.DS_XAJAX_NEWCOHOST();
		        var newcohostAutoComp = new YAHOO.widget.AutoComplete("newCoHost", "acNewCoHost", GDApplication.myDataSourceNewCoHost);
		        newcohostAutoComp.maxResultsDisplayed     = 1000;
		        newcohostAutoComp.minQueryLength          = 1; 
		        newcohostAutoComp.animSpeed               = 0.01;
		        newcohostAutoComp.itemSelectEvent.subscribe(GDApplication.itemNewCoHostSelectHandler);
		        newcohostAutoComp.containerCollapseEvent.subscribe(GDApplication.containerNewCoHostCollapseEvent);
			},
			/**
			 * 
			 */
			onChangeHost : function () {
				if ( $('#plhChangeHost').css('display') == 'none' ) $('#plhChangeHost').show();
				else GDApplication.onChangeHostCancel();
				return false;
			},
			onChangeHostCancel : function () {
				$('#plhChangeHost').hide();
				$('#newHost').removeClass('prFormErrors');
				$('#newHost').val('');
				$('#plhChangeHostErrors').hide();
				return false;
			},
			onChangeHostSubmit : function () {
            	$.post(cfgGDApplication.urlOnChangeHost, 
            		{ajax_mode: 'change_host', group: $('#groupID').val(), newHost: $('#newHost').val()},
            		function (data) {
            			xajax.processResponse(data);
            	},'xml');
			},
			onRemoveCoHost : function ( cohost ) {				
            	$.post(cfgGDApplication.urlOnRemoveCoHost, 
            		{ajax_mode: 'remove_cohost', group: $('#groupID').val(), cohost: cohost},
            		function (data) {
            			xajax.processResponse(data);
            			$('#plhCoHost_'+cohost).hide();
            	},'xml');
				return false;
			},
			onAddCoHostSubmit : function () {
            	$.post(cfgGDApplication.urlOnAddCoHost, 
            		{ajax_mode: 'add_co_host', group: $('#groupID').val(), newCoHost: $('#newCoHost').val()},
            		function (data) {
            			xajax.processResponse(data);
            	},'xml');
			},
			onDeleteGroup : function () {
                $('#btnConfirmDeleteFormSubmit').unbind().bind('click', function() {
                	$.post(cfgGDApplication.urlOnDeleteGroup, 
                		{ajax_mode: 'delete_group', group: $('#groupID').val()},
                		function (data) {
                			xajax.processResponse(data);
                	},'xml');
                })
                
	            popup_window.target('confirmDeletePanel');
	            popup_window.width(350); popup_window.height(80);
	            popup_window.open();
	            /*
	            $('#btnConfirmDeleteFormSubmit').unbind().bind('click', function() {
	            	$.post(cfgDGListApplication.urlOnDeleteChecked, 
	            		{ajax_mode: 'delete', groups: GListApplication.getCheckedAsString()},
	            		function (data) {
	            			xajax.processResponse(data);
	            	},'xml');
	            })
	            */
            }
		}
	}();
};