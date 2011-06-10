{literal}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://1maps.google.com/maps?file=api&amp;v=2&amp;key={/literal}{$key}{literal}"
      type="text/javascript"></script>

<script type="text/javascript">
	var lat = {/literal}{$user_lat}{literal};
	var lng = {/literal}{$user_lng}{literal};


	function update_coord(){
	document.getElementById("lat").value = lat;
	document.getElementById("lng").value = lng;
	}

	function load() {
      if (GBrowserIsCompatible()) {

		var map = new GMap2(document.getElementById("map"));
{/literal}
{if $show_tools}
{literal}
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
{/literal}
{/if}
{literal}

var center = new GLatLng({/literal}{$user_lat}{literal}, {/literal}{$user_lng}{literal});
map.setCenter(center, {/literal}{$zoom}{literal});

	   var point = new GLatLng({/literal}{$user_lat}{literal}, {/literal}{$user_lng}{literal});

	   marker = new GMarker(point, {draggable: {/literal}{if $dragable}true{else}false{/if}{literal}});
	   map.addOverlay(marker);
	   {/literal}
	   {if $dragable}
	   {literal}
	   GEvent.addListener(marker, "dragstart", function() {
		  //map.closeInfoWindow();
  		});

		GEvent.addListener(marker, "dragend", function() {
			var curr = marker.getPoint();
			lat = curr.lat();
			lng = curr.lng();
			update_coord();
		});
		{/literal}
		{/if}
		{literal}
      }
    }
	</script>

  </head>
  <body onload="load()" onunload="GUnload()">
    <div id="map" style="width: {/literal}{$size_x}{literal}px; height: {/literal}{$size_y}{literal}px"></div>

<input type="hidden" name="lat" id="lat">
<input type="hidden" name="lng" id="lng">
<script> update_coord();</script>
  </body>
</html>
{/literal}