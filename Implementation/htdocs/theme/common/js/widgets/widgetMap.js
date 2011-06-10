function wmPointFunction(point, objWM){ 
    if (!point) {
        alert('address not found');
    } else {
        objWM.map.setCenter(point, objWM.params.zoom);
    }
}

function wmZoomEndFunction(oldLevel, newLevel, objWM){
    //alert(objWM.map.getZoom());
    //markers count
    if (objWM.mgr) {
        if (window.parent.document.getElementById('markersCount')) {
            window.parent.document.getElementById('markersCount').innerHTML = objWM.mgr.getMarkerCount(objWM.map.getZoom());
        }
        $("#markersCount").attr("innerHTML", objWM.mgr.getMarkerCount(objWM.map.getZoom()));
    }
    //change zoom from national level
    if (oldLevel && oldLevel == objWM.getNationalZoomLevel() && newLevel != objWM.getNationalZoomLevel() && objWM.params.nationalChangeCallback) {
        eval('window.parent.'+objWM.params.nationalChangeCallback+'()');
    }
    //zoom less then National
    if (newLevel < objWM.getNationalZoomLevel() && objWM.params.nationalLessCallback) {
        eval('window.parent.'+objWM.params.nationalLessCallback+'()');
    }
	
    //NATIONAL
    //alert ('national');
    if (newLevel == objWM.getNationalZoomLevel()) {
        //switch to national level
        if (objWM.params.nationalCallback) {
            eval('window.parent.'+objWM.params.nationalCallback+'()');
        }
		
    //STATE
    } else if (newLevel == objWM.getStateZoomLevel()) {
    //alert ('State');
    //DISTRICT
    } else if (newLevel == objWM.getDistrictZoomLevel()) {
    //alert ('District');
    }true
	
    return false;
}

function setClusteringZoomLevel(objWM) {
    if (objWM.clusteringZoomLevel) {
        objWM.map.setZoom(objWM.clusteringZoomLevel+1);
    }
}

function WidgetMap () {}
WidgetMap.prototype.init = function (paramsObj) {
		
    this.params = paramsObj;
    this.map = null;
    this.layers = null;
    this.controls = null;
    this.clusteringZoomLevel = null;
		
    this.allmarkers = [];
    this.mgr = null;
    this.initGmap();
    this.icons = {};
        
}
	
WidgetMap.prototype.checkMarkerManager = function() {
    if (!this.mgr) {
        this.createMarkerManager();
    }
}
	
WidgetMap.prototype.createMarkerManager = function() {
    this.mgr = new MarkerManager(this.map, {
        trackMarkers: true
    });
}
    
WidgetMap.prototype.getNationalZoomLevel = function() {
    if (this.params.zoomLevelNational) {
        return this.params.zoomLevelNational;
    }
    return 0;
}
WidgetMap.prototype.getStateZoomLevel = function() {
    if (this.params.zoomLevelState) {
        return this.params.zoomLevelState;
    }
    return 0;
}
WidgetMap.prototype.getDistrictZoomLevel = function() {
    if (this.params.zoomLevelDistrict) {
        return this.params.zoomLevelDistrict;
    }
    return 0;
}
	
WidgetMap.prototype.getZoomLevel = function(value) {
    if (value == 'district') {
        return this.getDistrictZoomLevel();
    } else if (value == 'national') {
        return this.getNationalZoomLevel();
    } else if (value == 'state') {
        return this.getStateZoomLevel();
    }
    return value;
}
    
WidgetMap.prototype.initGmap = function () { //alert('start');
    if (GBrowserIsCompatible()) { //alert('compatible');
        		
        this.map = new GMap2(document.getElementById('widgetContainer'+this.params.cloneId), {
            mapTypes:[G_NORMAL_MAP]
            });
    		
    		
        if (this.params.additionalControls) { // GScaleControl
            for (i=0; i < this.params.additionalControls.length ; i++){
                if (this.params.additionalControls[i] == 'GSmallMapControl') {
                    this.map.addControl(new GSmallMapControl);
                } else {//@TODO
                    alert(this.params.additionalControls[i]);
                }
    	            	  
            }
        }
            
        this.controls = new Array();
        	
        /*KML Control*/
        if (this.params.kmlControl) {
            var wmapObj = this;
            if (this.params.kmlControlExternalId || this.params.kmlControlInternalId) {
                if (this.params.kmlControlInternalId && document.getElementById(this.params.kmlControlInternalId)) {
                    GEvent.addDomListener(document.getElementById(this.params.kmlControlInternalId), "click", function() {
                        wmapObj.getKML();
                    });
                } else if (this.params.kmlControlExternalId && parent.document.getElementById(this.params.kmlControlExternalId)) {
                    GEvent.addDomListener(parent.document.getElementById(this.params.kmlControlExternalId), "click", function() {
                        wmapObj.getKML();
                    });
                }
        		
            } else {
                function GWKMLControl() {}
                GWKMLControl.prototype = new GControl();
                GWKMLControl.prototype.initialize = function(map) {
                    var container = document.createElement("div");
                    var controlDiv = document.createElement("div");
                    GWsetKMLButtonStyle_(controlDiv);
                    container.appendChild(controlDiv);
                    controlDiv.appendChild(document.createTextNode("KML"));
                    wmapObj.controls['kml'] = controlDiv;
                    GEvent.addDomListener(controlDiv, "click", function() {
                        wmapObj.getKML();
                    });
				    		  
                    map.getContainer().appendChild(container);
                    return container;
                }
	            
                GWKMLControl.prototype.getDefaultPosition = function() {
                    return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, wmapObj.params.height-50));
                }
		        	
                GWsetKMLButtonStyle_ = function(button) {
                    button.className="WMKMLControl";
                }
		           
                this.map.addControl(new GWKMLControl());
            }
        	
        }
        	
        	
        	
        /*Groups & Events Controls*/
        if (this.params.switchGEScenario && this.params.switchGEScenario == 'nda') {
        //no controls
        } else {
            var wmapObj = this;
            //CUSTOM CONTROL
	            	
            function GWEventControl() {}
            function GWGroupControl() {}
	
            GWEventControl.prototype = new GControl();
            GWGroupControl.prototype = new GControl();
	        	
            GWEventControl.prototype.initialize = function(map) {
                var container = document.createElement("div");
                var controlDiv = document.createElement("div");
		            	  
                if (!wmapObj.params.defaultDisplayType || wmapObj.params.defaultDisplayType == 0) {
                    GWsetButtonStyle_(controlDiv);
                } else {
                    GWsetSelectedButtonStyle_(controlDiv);
                }
		            	  
                container.appendChild(controlDiv);
                controlDiv.appendChild(document.createTextNode("Events"));
		            	  
                wmapObj.controls['event'] = controlDiv;
		            	  
                if (wmapObj.params.switchGEScenario == 'search'){
                    GEvent.addDomListener(controlDiv, "click", function() {
                        parent.document.location = AppTheme.base_url+"/en/index/event/";
                    });
                } else {
                    GEvent.addDomListener(controlDiv, "click", function() {
                        GWsetSelectedButtonStyle_(wmapObj.controls['event']);
                        GWsetButtonStyle_(wmapObj.controls['group']);
                        wmapObj.clearMarkers();
                        wmapObj.getEventMarkers();
                    });
                }
		            	  
                map.getContainer().appendChild(container);
                return container;
            }
	
	            	
            GWGroupControl.prototype.initialize = function(map) {
                var container = document.createElement("div");
                var controlDiv = document.createElement("div");
	
                if (!wmapObj.params.defaultDisplayType || wmapObj.params.defaultDisplayType == 0) {
                    GWsetSelectedButtonStyle_(controlDiv);
                } else {
                    GWsetButtonStyle_(controlDiv);
                }
		        	  
                container.appendChild(controlDiv);
                controlDiv.appendChild(document.createTextNode("Groups"));
		        	 
                wmapObj.controls['group'] = controlDiv;
		        	  
                if (wmapObj.params.switchGEScenario == 'search'){
                    GEvent.addDomListener(controlDiv, "click", function() {
                        parent.document.location = AppTheme.base_url+"/en/index/groups/";
                    });
                } else {
                    GEvent.addDomListener(controlDiv, "click", function() {
                        GWsetSelectedButtonStyle_(wmapObj.controls['group']);
                        GWsetButtonStyle_(wmapObj.controls['event']);
                        wmapObj.clearMarkers();
                        wmapObj.getGroupMarkers();
                    });
                }
		
                map.getContainer().appendChild(container);
                return container;
            }
	            	
	            	  
            GWGroupControl.prototype.getDefaultPosition = function() {
                return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(87, 7));
            }
            GWEventControl.prototype.getDefaultPosition = function() {
                return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 7));
            }
	
	            	
            GWsetButtonStyle_ = function(button) {
                button.className="WMcustomControl";
            }
            GWsetSelectedButtonStyle_ = function(button) {
                button.className="WMcustomControlSelected";
            }
	            
            this.map.addControl(new GWEventControl());
            this.map.addControl(new GWGroupControl());
        //map.setCenter(new GLatLng(37.441944, -122.141944), 13);
        }
        	
        	
        	
            
        //LISTENER FOR CHANGING ZOOM LEVEL
        if (this.params.listenZoomLevelChanges) {
            objWM = this;
            GEvent.addListener(this.map, 'zoomend', function(oldLevel, newLevel){
                wmZoomEndFunction(oldLevel, newLevel, objWM);
            })
        }
            
        // END OF CUSTOM CONTROLS
            
	        
        //???  $data .= "myMap = $var;\n";
        //	mapsArray[cloneId].removeMapType(G_SATELLITE_MAP);
        // 	mapsArray[cloneId].removeMapType(G_HYBRID_MAP);
	


            	
        //WidgetMap.paramsArray['layers']  $layers = Z1SKY_GMap_Utils::getWMSLayers();
        this.layers = new Array();
        cnt = 0;
        for (i=0; i< this.params.layers.length; i++) {
            this.layers[cnt] = createWMSTileLayer(this.params.layers[i].url , this.params.layers[i].wmsLayer, null, 'image/gif', null, null, null, this.params.layers[i].opacity, this.params.layers[i].copyright, this.map);
            cnt++;
        }
	        
	        
        //@TODO
        // tdata = '';
        tdata=new Array();
        for (i = 0; i < cnt; i++) {
            //tdata = tdata + WidgetMap.layers[cloneId][i]; alert(WidgetMap.layers[cloneId][i]);
            tdata[tdata.length] = this.layers[i];
        //if (i != (cnt-1)) {tdata = tdata + ", "}
        }
        //Overlays
        var G_MAP_OVERLAY = createWMSOverlayMapType([G_NORMAL_MAP.getTileLayers()[0], this.layers[0], this.layers[1] ], 'Overlay');
        if (this.params.needDistrictLayer) {
            this.map.addMapType(G_MAP_OVERLAY);
        }
        this.map.setMapType(eval(this.params.mapType)); //G_MAP_OVERLAY
	        
        //this.map.setCenter(new GLatLng(37.441944, -122.141944), 13);
	        
	        
	        
	       
        //country
        if (this.params.country) {
            //$myPointMustBeNull = true;
            geocoder = new GClientGeocoder();
            geocoder.objWM = this;
            geocoder.getLatLng(this.params.country, function(point){
                wmPointFunction(point, geocoder.objWM);
            });
        } else if (this.params.latitude && this.params.longitude) {
            this.map.setCenter(new GLatLng(this.params.latitude, this.params.longitude), this.params.zoom);
        //myPoint = WidgetMap.paramsArray[cloneId].getCenter;
        } if (this.params.zip) {
            //$myPointMustBeNull = true;
            geocoder = new GClientGeocoder();
            geocoder.objWM = this;
            geocoder.getLatLng(this.params.zip + ', USA', function(point){
                wmPointFunction(point, geocoder.objWM);
            });
        } else {
            //crutch
            //	geocoder = new GClientGeocoder();
            //    geocoder.objWM = this;
            //    geocoder.getLatLng('United States', function(point){wmPointFunction(point, geocoder.objWM);});
            this.needRefresh = 1;
        }
	        
	        
	       
	        
        //DISPLAY Group or Event markers
        if (!this.params.defaultDisplayType || this.params.defaultDisplayType == 0) {//groups or events
            if (this.params.switchGEScenario == 'search'){
                //objWM = this;
                //setTimeout("objWM.getSearchGroupMarkers()", 10000);
                this.getSearchGroupMarkers();
            } else {
                this.getGroupMarkers();
            }
        } else {
            if (this.params.switchGEScenario == 'search'){
                //objWM = this;
                //setTimeout("objWM.getSearchEventMarkers()", 10000);
                this.getSearchEventMarkers();
            } else {
                if (this.params.switchGEScenario == 'nda'){
                    //objWM = this;
                    //setTimeout("objWM.getNdaEventMarkers()", 0);
                    this.getNdaEventMarkers();
		    		   
                } else {
                    this.getEventMarkers();
                }
            }
        }
	       
    } else {
// !GBrowserIsCompatible
}
        	
}
            
            
WidgetMap.prototype.getNdaEventMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getNdaEventMarkers';
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }
    if (this.params.eventToDisplayId) {
        url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
    }

    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.showMarkers(data);
    }, 'json');
}
	
	
WidgetMap.prototype.getEventMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getEventMarkers';
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.eventsDisplayType) {
        url = url + '/eventsDisplayType/' + this.params.eventsDisplayType;
        if (this.params.eventToDisplayId) {
            url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
        }
    }
    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.showMarkers(data);
    }, 'json');
}

            
            
WidgetMap.prototype.getGroupMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getGroupMarkers';
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.displayRange) {
        url = url + '/displayRange/' + this.params.displayRange;
    }
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }

    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.showMarkers(data);
    }, 'json');
}
	
       
	
WidgetMap.prototype.getSearchGroupMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getSearchGroupMarkers';
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.groupContext) {
        url = url + '/groupContext/' + this.params.groupContext;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }

    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.showMarkers(data);
    }, 'json');
}
    
WidgetMap.prototype.getSearchEventMarkers = function () {
    var url=AppTheme.base_url+'/en/widget/getSearchEventMarkers';
    if (this.params.width) {
        url = url + '/width/' + this.params.width;
    }
    if (this.params.height) {
        url = url + '/height/' + this.params.height;
    }
    if (this.params.eventWhen) {
        url = url + '/eventWhen/' + this.params.eventWhen;
    }
    if (this.params.eventToDisplayId) {
        url = url + '/eventToDisplayId/' + this.params.eventToDisplayId;
    }
    if (this.params.zoomLevel) {
        url = url + '/zoomLevel/' + this.params.zoomLevel;
    }

    var wmapObj = this;
    $.post(url, {}, function(data) {
        wmapObj.showMarkers(data);
    }, 'json');
}
	
WidgetMap.prototype.getKML = function () {
    if (this.lastMarkersDataResponse) {
        var url=AppTheme.base_url+'/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
        var wmapObj = this;
        $.post(url, {
            markers: JSON.toJSON(this.lastMarkersDataResponse)
            }, function(data) {
            wmapObj.showKML(data);
        }, 'json');
    }
}
	
WidgetMap.prototype.showKML = function (data) {
    if (data == 200) {
        var url=AppTheme.base_url+'/en/widget/getKML/r/'+Math.round(Math.random()*100000000)+'/';
        document.location.href = url;
    }
}
	
WidgetMap.prototype.clearMarkers = function () {
    this.mgr.clearMarkers();
}
    
      
WidgetMap.prototype.showMarkers = function (markers) {
    this.lastMarkersDataResponse = markers;
    if (markers.zoomLevel) {
        this.setGMapMarkers(markers.templates, markers.markersArray, markers.autoPosition ,markers.maxMinCoordinates, this.getZoomLevel(markers.zoomLevel), markers.customCenter, markers.clusteringZoomLevel);
    } else {
        this.setGMapMarkers(markers.templates, markers.markersArray, markers.autoPosition ,markers.maxMinCoordinates);
    }
}
    
WidgetMap.prototype. getIcon = function (image) {
    var icon = null;
    if (image) {
        if (this.icons[image]) {
            icon = this.icons[image];
        } else {
            icon = new GIcon();
            icon.image = image;
            icon.iconSize = new GSize(20, 20);
            icon.iconAnchor = new GPoint(20 >> 1, 20 >> 1);
            icon.infoWindowAnchor = new GPoint(20 >> 1, 20 >> 1);
            //icon.shadow = null;"images/" +       images[1] +      ".png";
            icon.shadowSize = new GSize(0, 0);
            this.icons[image] = icon;
        }
    }
    return icon;
}
	
WidgetMap.prototype.setGMapMarkers = function (templates, markers, autoPosition, maxMinCoordinates, zoom, customCenter, clusteringZoomLevel) {
    //map.setCenter(new GLatLng(50, -98), 3);//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    if (clusteringZoomLevel) {
        this.clusteringZoomLevel = clusteringZoomLevel;
    }

    var maxLat, maxLong, minLat, minLong;
    	
    if (autoPosition==1 && markers.length > 0) {
        if (zoom) {
            this.map.setCenter(new GLatLng(markers[0].latitude, markers[0].longitude), zoom);
        } else {
            this.map.setCenter(new GLatLng(markers[0].latitude, markers[0].longitude), 16);
        }
            
    }
        
    if (customCenter) {
        if (zoom) {
            this.map.setCenter(new GLatLng(customCenter['latitude'], customCenter['longitude']), zoom);
        } else {
            this.map.setCenter(new GLatLng(customCenter['latitude'], customCenter['longitude']), 16);
        }
    }
       
       
    this.checkMarkerManager();
    	
    this.allmarkers.length = 0;
    	
    for (var i in markers) {
        if (markers.hasOwnProperty(i)) {
            var layer = markers[i];
            var markersA = new Array();
            
            for (var j in layer["places"]) {
                if (layer["places"].hasOwnProperty(j)) {
                    var place = layer["places"][j];
            	  
                    /*if (place.address) {
                        var objWM = this;
                        var WMPlace = place;
                        var WMLayerZoom = layer["zoom"];
                        (function (objWM, WMPlace, WMLayerZoom) {
                            geocoder = new GClientGeocoder();
                            if (geocoder) {
                                geocoder.getLatLng(WMPlace.address,
                                    function (point) {
                                        if (!point) {
                                            var place = WMPlace;
                                            if(place.latitude && place.longitude) {
                                                var latlng = new GLatLng(place.latitude, place.longitude);
                                                var mOptions = {};
                                                if (place.tooltip) {
                                                    mOptions.title = place.tooltip;
                                                }
                                                if (place.icon) {
                                                    mOptions.icon = objWM.getIcon(place.icon);
                                                }
                                                var marker = new GMarker(latlng, mOptions);
                                                marker.bindInfoWindowHtml(place.html);
                                                var markersA = new Array();
                                                markersA.push(marker);
		                	                
                                                objWM.allmarkers.push(marker);
                                                objWM.mgr.addMarkers(markersA, WMLayerZoom[0], WMLayerZoom[1]);
                                                objWM.mgr.refresh();
                                            } else {
                                        //alert('error');
                                        }
                                        } else {
                                            var place = WMPlace;
                                            place.latitude = point.lat();
                                            place.longitude = point.lng();
	                                		
                                            var latlng = new GLatLng(place.latitude, place.longitude);
                                            var mOptions = {};
                                            if (place.tooltip) {
                                                mOptions.title = place.tooltip;
                                            }
                                            if (place.icon) {
                                                mOptions.icon = objWM.getIcon(place.icon);
                                            }
                                            var marker = new GMarker(latlng, mOptions);
                                            marker.bindInfoWindowHtml(place.html);
                                            var markersA = new Array();
                                            markersA.push(marker);
		                	                
                                            objWM.allmarkers.push(marker);
                                            objWM.mgr.addMarkers(markersA, WMLayerZoom[0], WMLayerZoom[1]);
                                            objWM.mgr.refresh();
	                     
		              	        		  	
                                        }
                                    }
                                    );
                            }
                        })(objWM, WMPlace, WMLayerZoom);//!!!
                	
                    } else {*/
                        var latlng = new GLatLng(place.latitude, place.longitude);
                        var mOptions = {};
                        if (place.tooltip) {
                            mOptions.title = place.tooltip;
                        }
                        if (place.icon) {
                            mOptions.icon = this.getIcon(place.icon);
                        }
                        var marker = new GMarker(latlng, mOptions);

                        marker.bindInfoWindowHtml(renderFromTemplate(templates[place.html['TT']], place.html));
                        markersA.push(marker);
                        this.allmarkers.push(marker);
	                
	        	    
                  //  }
                
                
                }
            }
            
            this.mgr.addMarkers(markersA, layer["zoom"][0], layer["zoom"][1]);
        }
          
    }
    this.mgr.refresh();
     
}
function renderFromTemplate(template, params){
    var result = template;
    for(key in params) {
        if (key != 'TT') {
            var reg = new RegExp("\\[\\*"+key+"\\*\\]", "g");
            result = result.replace(reg, params[key]);
        }
    }
    return result;
}