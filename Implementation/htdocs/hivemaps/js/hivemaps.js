/**
 * @name HiveMaps
 * @version 0.9
 * @author Joe Johnston <joe@socialhive.org>
 * @copyright (c) 2009 Joe Johnston
 * http://socialhive.org
 * http://code.google.com/p/hivemaps
 */

/*
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

(function($) {
  function map(mapElem, options) {
    this.options = $.extend(HiveMap.defaultOptions, options);
    this.mapDiv = (typeof(mapElem) == 'string') ? $('#'+mapElem)[0] : mapElem;
    this.map = new GMap2(this.mapDiv);





    this.data = null;
    this.markers = [];
    this.markersLoaded = false;
    this.markersInView = [];
    this.markerClusterer = null;
    
    if (this.options.listDiv && typeof(this.options.listDiv)=='string')
      this.options.listDiv = $('#'+this.options.listDiv)[0];
                
    this.loadData = function(feed, type){
      return loadData(this, feed, type);
    };
    this.initMarkers = function(data){
      return initMarkers(this, data);
    };
    this.search = function(query){
      return searchMap(this, query);
    };
    this.zoom = function(zoom, lat, lon){
			return updateMap(this, lat, lon, zoom);
    };
    this.pan = function(lat, lon, zoom){
			return updateMap(this, lat, lon, zoom);
    };
    this.getLinkQuery = function(){
      return $.address.value();
    };
    this.getLink = function(){
      return $.address.baseURL()+'#'+$.address.value();
    };
    
    if (this.options.onMapInit)
      this.options.onMapInit(this);
    if (this.options.enableHistory)
      this.options.initHistory(this);
    var map = this;
    GEvent.addListener(this.map, 'moveend', function(){map.options.onMapMoved(map);});
    if (this.options.dataFeed)
      this.loadData(this.options.dataFeed, this.options.dataType);
  }

  var HiveMap = {
    defaultOptions: {
      enableHistory: true,
      initHistory: initHistory,
      historyName: 'hivemap',
      defaultZoom: 1,
      defaultCenter: {lat:25.8, lon:13.36},
      maxMarkers: 5000,
      maxListSize: 500,
      checkMarkerOverlap: checkMarkerOverlap,
      loadingBar: LoadingBar,
      markerClusterer: 'MarkerClusterer',
      imagesBase: 'images/',
      markerClustererOptions: {
        gridSize: 60,
        maxZoom: 11,
        styles: [
          {url:'marker2.png', width:53, height:53, opt_textColor:'#FFF'},
          {url:'marker3.png', width:56, height:56, opt_textColor:'#FFF'},
          {url:'marker4.png', width:66, height:66, opt_textColor:'#FFF'},
          {url:'marker4.png', width:66, height:66, opt_textColor:'#FFF'},
          {url:'marker4.png', width:66, height:66, opt_textColor:'#FFF'}
        ]
      },
      // options object or GIcon
      markerIcon: {
        icon: {url:'marker.png', width:18, height:18},
        shadow: null,
        iconAnchor: {x:9, y:9},
        infoWindowAnchor: {x:9, y:9},
        transparent: 'marker_trans.png'
      },
      dataXmlRoot: 'xml',
      listDiv: null,
      gettext: function(str) {
        return str;
      },
      listItemHTML: function(map, marker){
        return '<div><a href="'+marker.data.id+'">'+marker.data.title+'</a></div>';
      },
      moreListItemsHTML: function(map, count, list){
        return '<div>'+(list.length-count)+' '+map.options.gettext('points not shown')+'.  '+map.options.gettext('Zoom in on the map')+'.</div>';
      },
      showLoading: function(map){
        if (map.options.loadingBar) {
          if (typeof(map.options.loadingBar) != 'object')
            map.options.loadingBar = new map.options.loadingBar;
          map.map.addControl(map.options.loadingBar);
        }
      },
      hideLoading: function(map){
        if (typeof(map.options.loadingBar) == 'object')
          map.map.removeControl(map.options.loadingBar);
      },
      raiseError: function(map, msg, code) {
        alert(map.options.gettext('Error')+': '+map.options.gettext(msg));
      },
      onDataLoad: function(map, data){
        if (map.options.dataType == 'xml')
          data = SOCIALHIVE.utils.xml2js(data, map.options.dataXmlRoot);
        map.data = data;
        map.initMarkers(data);
      },
      onMapInit: function(map){
        map.options.showLoading(map);
        map.map.setCenter(new GLatLng(map.options.defaultCenter.lat, map.options.defaultCenter.lon), map.options.defaultZoom);
        map.map.setUIToDefault();
        map.map.disableScrollWheelZoom();
      },
      onListInit: function(map, list, max){
      },
      onMapMoved: function(map) {
        if (map.options.listDiv && map.markersLoaded)
          setTimeout(function(){initList(map);}, 10);
      },
      onMarkerClick: function(map, marker) {
        map.map.openInfoWindowHtml(marker.getLatLng(), marker.data.title);
      },
      onMarkerClickOverlap: function(map, marker, list) {
        list.unshift(marker);
        var node = $(document.createElement('ol')).attr('id', 'infoMarkerList');
        $(list).each(function(i, m){
          $('<li><a href="#">'+m.data.title+'</a></li>').click(function(){map.options.onMarkerClick(map, m); return false;}).appendTo(node);
        });
        map.map.openInfoWindow(marker.getLatLng(), node[0]);        
      }
    },
    createMap: function(mapID, options) {
      return new map(mapID, options);  
    },
    geoCoder: geoCoder
  };

  var geoCoder = {
  	geocoder: null,
  	geocodeAccuracy: ['Unknown', 'Country', 'Region', 'Sub-region', 'Town', 'Post code', 'Street', 'Intersection', 'Address'],
  	geocodeAccuracyZoom: [3, 4, 6, 7, 11, 13, 14, 15, 16],
  	geocodeMessages: {
  		en: {
  			200 /*G_GEO_SUCCESS*/: 'Success',
  			601 /*G_GEO_MISSING_ADDRESS, G_GEO_MISSING_QUERY*/: 'Missing Address: The address was either missing or had no value.',
  			602 /*G_GEO_UNKNOWN_ADDRESS*/: 'Sorry ... we could not find the address.  Try another search.',
  			603 /*G_GEO_UNAVAILABLE_ADDRESS*/: 'Unavailable Address:  The geocode for the given address cannot be returned due to legal or contractual reasons.',
  			604 /*G_GEO_UNKNOWN_DIRECTIONS*/: 'Unable to find directions.',
  			610 /*G_GEO_BAD_KEY*/: 'Bad Key: The API key is either invalid or does not match the domain for which it was given',
  			620 /*G_GEO_TOO_MANY_QUERIES*/: 'Too Many Queries: The daily geocoding quota for this site has been exceeded.',
  			500 /*G_GEO_SERVER_ERROR*/: 'Server error: The geocoding request could not be successfully processed.',
  			400 /*G_GEO_BAD_REQUEST*/: 'Oops! ... An error occurred.  Please reload this page and try again.'
  		}
  	},
  	init: function() {
  		this.geocoder = new GClientGeocoder();
  	},
  	getLatLng: function(address, f) {
  		this.geocoder.getLatLng(address, f);
  	},
  	getLocations: function(address, f) {
  		this.geocoder.getLocations(address, f);
  	},
  	getZoomFromResult: function(result) {
  		return this.geocodeAccuracyZoom[result.Placemark[0].AddressDetails.Accuracy];
  	},
  	getErrorMessage: function(result) {
  		return (this.geocodeMessages.en[result.Status.code]) ? this.geocodeMessages.en[result.Status.code] : this.geocodeMessages.en[400];
  	}
  };

  
  function LoadingBar() {
    this.container = null;
  }
  LoadingBar.prototype = new GControl();
  LoadingBar.prototype.initialize = function(map) {
    this.container = $(document.createElement('div')).attr('class', 'loading').appendTo(map.getContainer());
    return this.container[0];
  };
  LoadingBar.prototype.getDefaultPosition = function() {
    return new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(this.container.width(), this.container.height()));
  };


  function initMarkerClusterer(map) {
    if (!map.options.markerClustererOptions.imagesBaseAdded) {
      var styles = map.options.markerClustererOptions.styles;
      for (var i=0; i<styles.length; i++)
        styles[i].url = map.options.imagesBase+styles[i].url;
      map.options.markerClustererOptions.imagesBaseAdded = true;
    }
    map.markerClusterer = (typeof(map.options.markerClusterer)=='function') ? 
      map.options.markerClusterer(map.map, map.markers, map.options.markerClustererOptions) :
      new window[map.options.markerClusterer](map.map, map.markers, map.options.markerClustererOptions);
  }
  
  function initList(map) { return;//komarovski
    list = getMarkersInView(map);
    var max = (map.options.maxListSize < list.length) ? map.options.maxListSize : list.length;
    map.options.onListInit(map, list, max);
    ma = [];
    for (var i = 0; i < max; i++) {
      if (!list[i].data.title)
        continue;
      ma.push(map.options.listItemHTML(map, list[i]));
    }
    if (i < list.length)
      ma.push(map.options.moreListItemsHTML(map, i, list));
    setTimeout(function(){map.options.listDiv.innerHTML = ma.join('');}, 0);
    setTimeout(function(){map.options.hideLoading(map);}, 500);
  }
  
  function initHistory(map) {
    GEvent.addListener(map.map, 'moveend', function(){updateHistory(map);});
    $.address.change(function(e){
      var history = e.pathNames;
      if (history[0] != map.options.historyName || history.length < 4)
        return false;
      var center = map.map.getCenter();
      if (center.lat()!=history[1] || center.lng()!=history[2] || map.map.getZoom()!=history[3]) {
        updateMap(map, history[1], history[2], history[3]);
      }
    });
  }

  function initMarkers(map, data) {
    map.map.clearOverlays();
    map.markers = [];
    var icon = getMarkerIcon(map);
    
    if (map.markerClusterer)
      map.markerClusterer.clearMarkers();
      
    var max = (map.options.maxMarkers < data.length) ? map.options. maxMarkers : data.length;
    for (var i=0; i<max; i++) {
      if (!data[i].title || !data[i].lat || !data[i].lon)
        continue;
      var latlng = new GLatLng(data[i].lat, data[i].lon);
      var marker = new GMarker(latlng, {icon: icon});
      marker.data = data[i];
      GEvent.addListener(marker, 'click', _getMarkerClickFn(map, marker));
      map.markers.push(marker);
    }
    
    function _getMarkerClickFn(map, marker) {
      return function() {
        (map.options.checkMarkerOverlap) ? map.options.checkMarkerOverlap(map, marker) : map.options.onMarkerClick(map, marker);
      };
    }
    
    (map.options.markerClusterer) ? setTimeout(function(){initMarkerClusterer(map);}, 0) : addMarkers(map, map.markers);
    (map.options.listDiv) ? setTimeout(function(){initList(map);}, 0) : setTimeout(function(){map.options.hideLoading(map);}, 100);   
  }
  
  function updateHistory(map) {
    var center = map.map.getCenter();
    $.address.value(map.options.historyName+'/'+center.lat()+'/'+center.lng()+'/'+map.map.getZoom());
  }

  function loadData(map, feed, type) {
    $.ajax({url: feed, type: 'GET', dataType: type,
      success: function(data) {
        map.markersLoaded = true;
        map.options.onDataLoad(map, data);
      },
      error: function(xhr, status, err) {
        map.options.raiseError(map, 'Failed loading data.');
      }
    });
  }
    
  function addMarkers(map, markers) {
    for (var i = 0; i < markers.length; i++) {
      map.map.addOverlay(markers[i]);
    }
  }
  
  function getMarkerIcon(map) {
    var opt = map.options.markerIcon;
    if (typeof(opt) == 'function')
      return opt;
    var  icon = new GIcon(G_DEFAULT_ICON, map.options.imagesBase+opt.icon.url);
    icon.iconSize = new GSize(opt.icon.width, opt.icon.height);
    if (opt.shadow) {
      icon.shadow = map.options.imagesBase+opt.shadow.url;
      icon.shadowSize = new GSize(opt.shadow.width, opt.shadow.height);
    } else {
      icon.shadow = null;
    }
    icon.iconAnchor = new GPoint(opt.iconAnchor.x, opt.iconAnchor.y);
    icon.infoWindowAnchor = new GPoint(opt.infoWindowAnchor.x, opt.infoWindowAnchor.y);
    icon.transparent = opt.transparent;
    return icon;
  }

  function checkMarkerOverlap(map, marker) {
    var ll = marker.getLatLng();
    var sw = map.map.fromDivPixelToLatLng(new GPoint(0, 0));
    var ne = map.map.fromDivPixelToLatLng(new GPoint(map.options.markerIcon.icon.width, map.options.markerIcon.icon.height));
    var width = Math.abs(sw.lat()-ne.lat());
    var height = Math.abs(sw.lng()-ne.lng());
    var factor = 1.5;
    var bounds = new GLatLngBounds( // bounds of marker icon plus a little extra
      new GLatLng(ll.lat()-width/factor, ll.lng()-height/factor),
      new GLatLng(ll.lat()+width/factor, ll.lng()+height/factor)
    );
    var list = [];
    for (var i=0; i<map.markers.length; i++) {
      if (bounds.containsLatLng(map.markers[i].getLatLng()) && (map.markers[i] != marker))
        list.push(map.markers[i]);
    }
    (list.length) ? map.options.onMarkerClickOverlap(map, marker, list) : map.options.onMarkerClick(map, marker);
  }
  
  function getMarkersInView(map) {
  		if (map.markersLoaded) {
  			map.markersInView = getMarkersInBounds(map.markers, map.map.getBounds()); 
  		}
  		return map.markersInView;
  }
  
  function getMarkersInBounds(markers, bounds) {
		var list = [];
		for (var i=0; i < markers.length; i++) {
			if (bounds.containsLatLng(markers[i].getPoint()))
				list.push(markers[i]);
		}
		return list;
	}
    
  function updateMap(map, lat, lon, zoom) {
		lat = parseFloat(lat);
		lon = parseFloat(lon);
		zoom = parseInt(zoom);
  	map.map.closeInfoWindow();
  	if (!isNaN(lat) && !isNaN(lon)) {
  		map.map.setCenter(new GLatLng(lat, lon), (zoom)?zoom:null);
    } else if (!isNaN(zoom) && zoom!=null) {
      map.map.setZoom(zoom);
    }
  }
  
  function searchMap(map, query) {
  	geoCoder.getLocations(query, function(result) {
  		if (result.Status.code == G_GEO_SUCCESS) {
  			var p = result.Placemark[0].Point.coordinates;
  			updateMap(map, p[1], p[0], geoCoder.getZoomFromResult(result));
  		} else {
  		  map.options.raiseError(map, 'Unable to find this location.');
  		}
  	});
  }
  
  
  // init
  jQuery(function($) {
    geoCoder.init();
    $(window).unload(function(){
      GUnload();
    });
  });
  
  if (!window.SOCIALHIVE)
    window.SOCIALHIVE = {};
  window.SOCIALHIVE.map = HiveMap;
  window.SOCIALHIVE.geocoder = geoCoder;
})(jQuery);

