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
	var lat = {/literal}{$group_lat}{literal};
	var lng = {/literal}{$group_lng}{literal};


	function update_coord(){
	document.getElementById("lat").value = lat;
	document.getElementById("lng").value = lng;
	}

	function createMarker(point, login, avatar) {
	  var marker = new GMarker(point);
	  GEvent.addListener(marker, "click", function() {
	  	marker.openInfoWindowHtml("<b>" + login + "</b><img src='" + avatar + "' border=0>");
  	  });
  	  return marker;
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

var center = new GLatLng({/literal}{$group_lat}{literal}, {/literal}{$group_lng}{literal});
map.setCenter(center, {/literal}{$zoom}{literal});


		{/literal}
		{foreach item=m name='members' from=$members_list}
		{literal}

			var point = new GLatLng({/literal}{$m->getLatitude()}{literal},{/literal}{$m->getLongitude()}{literal});
			map.addOverlay(createMarker(point, '{/literal}{$m->getLogin()}{literal}', '{/literal}{$m->getAvatar()->getSmall()}{literal}'));

		{/literal}
		{/foreach}
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