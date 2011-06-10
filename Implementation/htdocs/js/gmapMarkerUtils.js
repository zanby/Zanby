function fordelay() {
    return 0;
}

function setGMapMarkers(map, markers, autoPosition, maxMinCoordinates, zoom, customCenter) {

	var mCount = markers.length;
	
	var maxLat, maxLong, minLat, minLong;

    if (autoPosition==1 && mCount > 0) {
    	if (zoom) {
    		map.setCenter(new GLatLng(markers[0].latitude, markers[0].longitude), zoom);
    	} else {
    		map.setCenter(new GLatLng(markers[0].latitude, markers[0].longitude), 16);
    	}
        
    }
    
    if (customCenter) {
    	if (zoom) {
    		map.setCenter(new GLatLng(customCenter['latitude'], customCenter['longitude']), zoom);
    	} else {
    		map.setCenter(new GLatLng(customCenter['latitude'], customCenter['longitude']), 16);
    	}
    }
	
	for (var i = 0; i < mCount; i++) {
		if (i == 0) {
			maxLat = minLat = new Number(markers[i].latitude);
			maxLong = minLong = new Number(markers[i].longitude);
		} else {
			if (markers[i].latitude > maxLat) {
				maxLat = new Number(markers[i].latitude);
			}
			if (markers[i].latitude < minLat) {
				minLat = new Number(markers[i].latitude);
			}
			if (markers[i].longitude > maxLong) {
				maxLong = new Number(markers[i].longitude);
			}
			if (markers[i].longitude < minLong) {
				minLong = new Number(markers[i].longitude);
			}
		}
		var latlng = new GLatLng(markers[i].latitude, markers[i].longitude);
		
		mOptions = {};
		if (markers[i].title) {
			mOptions.title = markers[i].title;
		}
		
		var marker = new GMarker(latlng, mOptions);
	    if (i == 0) {
	       defaultIcon = marker.getIcon().image;
	    }
		if (markers[i].icon) {
			marker.getIcon().image = markers[i].icon;
		} else {
		    marker.getIcon().image = defaultIcon;
		}
		marker.bindInfoWindowHtml(markers[i].html);
		map.addOverlay(marker);
	}
	
    var maxMinIsSet = false;
    if (typeof maxMinCoordinates.minlatitude!='undefined' || typeof maxMinCoordinates.maxlatitude!='undefined'
        || typeof maxMinCoordinates.minlongitude!='undefined' || typeof maxMinCoordinates.maxlongitude!='undefined') {
       minLat  = new Number(maxMinCoordinates.minlatitude);
       maxLat  = new Number(maxMinCoordinates.maxlatitude);
       minLong = new Number(maxMinCoordinates.minlongitude);
       maxLong = new Number(maxMinCoordinates.maxlongitude);
       maxMinIsSet = true;
    }
	
	if (!zoom && (mCount > 1 || maxMinIsSet)) {
		var miles = (3958.75 * Math.acos(Math.sin(minLat / 57.2958) * Math.sin(maxLat / 57.2958) + Math.cos(minLat / 57.2958) * Math.cos(maxLat / 57.2958) * Math.cos(maxLong / 57.2958 - minLong / 57.2958)));
		var zoom = 0;
		if (miles < 0.2) {
			zoom = 16;
		} else if (miles < 0.5) {
			zoom = 15;
		} else if (miles < 1) {
			zoom = 14;
		} else if (miles < 2) {
			zoom = 13;
		} else if (miles < 3) {
			zoom = 12;
		} else if (miles < 7) {
			zoom = 11;
		} else if (miles < 15) {
			zoom = 10;
		} else if (miles < 30) {
			zoom = 9;
		} else if (miles < 50) {
			zoom = 8;
		} else if (miles < 100) {
			zoom = 7;
		} else if (miles < 250) {
			zoom = 6;
		} else if (miles < 500) {
			zoom = 5;
		} else if (miles < 1000) {
			zoom = 4;
		} else if (miles < 2500) {
			zoom = 3;
		} else if (miles < 5000) {
			zoom = 2;
		} else if (miles < 10000) {
			zoom = 1;
		} else {
			zoom = 0;
		}
		
		var centerLat = minLat + (maxLat - minLat)/2;
		var centerLong = minLong + (maxLong - minLong)/2;
        if (autoPosition==1) {		
	    	map.setCenter(new GLatLng(centerLat, centerLong), zoom);
	    }
	}
}

var markerManagers = new Object();

function createMarker2(map, x, y, s, d) {
	var icon = new GIcon();
    icon.image = "/znimages/gmapicons/" + s + "_0" + d + ".png";
    icon.iconSize = new GSize(34, 19);
	icon.iconAnchor = new GPoint(17, 10);

	var marker = new GMarker(new GPoint(x, y), icon);

	var mmgr = markerManagers[s];
	if (mmgr == null) {
		mmgr = new MarkerManager(map, { borderPadding: 25, trackMarkers: false });
		markerManagers[s] = mmgr;
	}
	mmgr.addMarker(marker, 6)
}

function removeDistrictMarkers() {
	for (var s in markerManagers) {
		markerManagers[s].clearMarkers();
	}
}
