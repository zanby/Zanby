/**************************************************
* NOTES:
*    All JS strings are sent to gettext function for translation.
*    Strings in the [strings] array can be edited as needed.  All other
*    JS strings can not be edited.
*
*    For staging and production deployment...
*    1) stylize CSS and HTML as needed
*    2) edit the following JS variables:
*    - dataURL: endpoint of full marker list XML feed
*    - dataDetailsBaseURL: base endpoint of for action details XML feed
*    - detailsBaseURL: base URL for action details page
*    - imagesBaseURL: base URL for map icon images
*    3) search for 'todo' in this file and follow relevant instructions
*
**/

jQuery(function($){  
  var dataURL = HM_APIURL+'/en/widget/getXML/';//'test/data.xml';//HM_APIURL='http://z1sky-cpp.komarovski.buick';
  var dataDetailsBaseURL = HM_APIURL+'/en/widget/getXMLDetails';//'test/details.xml';//HM_APIURL='http://z1sky-cpp.komarovski.buick';
  var detailsBaseURL = '/';
  var imagesBaseURL = 'images/';
  var mapURL = 'http://hivemaps.googlecode.com/svn/trunk/index.html';
  var embedURL = 'http://hivemaps.googlecode.com/svn/trunk/embed.html';

  /**************************************************
   * JS strings to translate.  Strings can be modified but DO NOT reorder. */
  var strings = [
    'Location',             // infoWindow tab header
    'Info',                 // infoWindow tab header
    'zoom here',            // infoWindow text
    'go to event page',     // infowWindow text
    'actions not shown',    // marker list text
    'Zoom in on the map',   // marker list text
    'Actions',              // marker list text
    'Search by City, Country, Continent, or Zip',  // search bar default text
    'Loading',
    'View Actions at 350.org'
  ];
  
  /**************************************************
   * Additional JS strings to translate.  Can not be modified.

   [hivemaps.js]
   - 'Failed loading data.',           // loading error
   - 'Unable to find this location.'   // map search error
   
   **************************************************
   * Additional strings to translate in HTML..
            
   [mapPanel]
   - Map of Actions
   - Search
   - Zoom
   - World
   - Africa
   - Asia
   - Europe
   - North America
   - South America
   - Oceania
   
   [listPanel]
   - Action Name
   - City
   - Country
   
   **/
  
  var map = null;
  var embedded = false;
  
  var zoomRegions = {
    world: {zoom:1, lat:25.8, lon:13.359},
    africa: {zoom:2, lat:9.796, lon:23.203},
    asia: {zoom:2, lat:29.228, lon:78.398},
    europe: {zoom:3, lat:50.513, lon:13.535},
    northamerica: {zoom:2, lat:49.382, lon:-101.25},
    oceania: {zoom:2, lat:-28.921, lon:140.273},
    southamerica: {zoom:2, lat:-20.303, lon:-62.578}
  };
  
  function gettext(str) {
    // todo: call string translation function; e.g. Drupla.t(str)
    //return Drupal.t(str);
    return str;
  }
  function initMap(map){
    map.options.showLoading(map);
    map.map.setCenter(new GLatLng(map.options.defaultCenter.lat, map.options.defaultCenter.lon), map.options.defaultZoom);
    map.map.setUIToDefault();
    map.map.disableScrollWheelZoom();


var WMS_URL = HM_WMSURL; //komarovski
var G_MAP_LAYER_FILLED = createWMSTileLayer(WMS_URL, "cd-filled", null, "image/gif", null, null, null, .25);
var G_MAP_LAYER_OUTLINES = createWMSTileLayer(WMS_URL, "cd-outline", null, "image/gif", null, null, null, .01, "Data from GovTrack.us");
var G_MAP_OVERLAY = createWMSOverlayMapType([G_NORMAL_MAP.getTileLayers()[0], G_MAP_LAYER_FILLED, G_MAP_LAYER_OUTLINES], "Overlay");

map.map.addMapType(G_MAP_OVERLAY);


    map.map.setMapType(G_MAP_OVERLAY); 
  }
  function getDetailsURL(data) {
    return detailsBaseURL+data.id;
  }
  function getLinkTarget() {
    return (embedded) ? '_top' : '_self';
  }
  function showInfo(map, marker) {
    if (!marker.data.address)
      marker.data.address = marker.data.desc = gettext(strings[8])+'...';
    var locTab = new GInfoWindowTab(gettext(strings[0]), getLocTabHtml(marker.data));
    var infoTab = new GInfoWindowTab(gettext(strings[1]), getInfoTabHtml(marker.data));
    setTimeout(function(){loadDetails(marker.data.id, locTab, infoTab);}, 0);
    map.map.openInfoWindowTabs(marker.getLatLng(), [locTab, infoTab]);
  }
  function loadDetails(id, locTab, infoTab) {
    // todo: uncomment below line for staging or production deployment
    //$.ajax({url: dataDetailsBaseURL+id, type: 'GET', dataType: 'xml',
    $.ajax({url: dataDetailsBaseURL+'/id/'+id, type: 'GET', dataType: 'xml',
    //$.ajax({url: 'test/detail.xml', type: 'GET', dataType: 'xml',
      success: function(data) {
        var data = SOCIALHIVE.utils.xml2js(data, 'xml');
        // todo: enable id checking on production
        //if (data.id != id)
        //  return;
//alert(1);
//alert(data[0]);
//locTab.contentElem.innerHTML = getLocTabHtml(data);
       // infoTab.contentElem.innerHTML = getInfoTabHtml(data);
//for (var is=0;data.length;is++){
 // if (data[is] && data[is].id == id) {
   locTab.contentElem.innerHTML = getLocTabHtml(data[0]);
        infoTab.contentElem.innerHTML = getInfoTabHtml(data[0]);
 // }
//}
       
      },


      error: function(xhr, status, err) {
        alert(status);
      }



    });
  }
  function mapZoom(zoom, lat, lon) {
    map.zoom(14, lat, lon);
    return false;
  }
  function getLocTabHtml(data) {
    return '<div id="infoTabLocation"><div class="title">'+data.title+'</div><div class="address">'+data.address+'</div><div class="nav"><a href="#" onclick="return CUSTOMMAP.mapZoom(14,'+data.lat+','+data.lon+');">'+gettext(strings[2])+'</a><a target="'+getLinkTarget()+'" href="'+getDetailsURL(data)+'">'+gettext(strings[3])+'</a></div></div>';
  }
  function getInfoTabHtml(data) {
    return '<div id="infoTabInfo"><div class="desc">'+data.desc+'</div><div class="nav"><a target="'+getLinkTarget()+'" href="'+getDetailsURL(data)+'">'+gettext(strings[3])+'</a></div></div>';
  }
  var listCnt = 0;
  function initList(map, list, max) {
    listCnt = 0;
    $('#actionsCount')[0].innerHTML = gettext(strings[6])+'('+list.length+')';
  }
  function getListItemHTML(map, marker) {
    listCnt++;
    return '<div class="row row'+(listCnt%2)+'"><div class="title"><a target="'+getLinkTarget()+'" href="'+getDetailsURL(marker.data)+'">'+marker.data.title+'</a></div><div class="city">'+marker.data.city+'</div><div class="country">'+ISO3166.codes[marker.data.cntry]+'</div><div class="clear"></div></div>';
  }
  function getMoreListItemsHTML(map, count, list) {
    return '<div class="footer">'+(list.length-count)+' '+gettext(strings[4])+'. <a href="#mapPanel">'+gettext(strings[5])+'.</a></div>';
  }
  function updateEmbedCode() {return false;//komarovski
    var history = ($('#mapEmbedOptionsZoom')[0].checked) ? '#'+map.getLinkQuery() : '';
    if (history.length <= 2)
      history = '';
    $('#mapEmbedCode')[0].value = '<iframe width="100%" height="425" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+embedURL+history+'"></iframe><br /><small><a href="'+mapURL+'">'+strings[9]+'</a></small>';
  }
  function embedCodeClick() {
    updateEmbedCode();
    $('#mapEmbedCode')[0].select();
    $('#mapEmbedOptions').show('normal');
  }
    
  function init() {//komarovski
    /*$('#mapSearch')[0].value = strings[7];
    $('#mapSearch').click(function(){
      if (this.value == strings[7])
        this.value = '';
    });
    $('#mapSearchForm').submit(function(){
      map.search(this.search.value);
      return false;
    });*/
    $('#mapZoomPanel a').click(function(){
      var z = zoomRegions[this.rel];
      map.zoom(z.zoom, z.lat, z.lon);
    });
  }
    
  function createMap(mapElem) {
    map = SOCIALHIVE.map.createMap(mapElem, {
      enableHistory: true,
      historyName: 'map',
      imagesBase: imagesBaseURL,
      defaultZoom: zoomRegions['world'].zoom,
      defaultCenter: {lat:zoomRegions['world'].lat, lon:zoomRegions['world'].lon},
      dataFeed: dataURL,
      dataType: 'xml', 
      listDiv: 'markerList', 
      onMarkerClick: showInfo,
      onMapInit: initMap,
      onListInit: initList,
      listItemHTML: getListItemHTML, 
      moreListItemsHTML: getMoreListItemsHTML, 
      gettext: gettext,
      maxMarkers: 5000,
      maxListSize: 100
    });
    init();
    updateEmbedCode();
    return map;  
  }
   
  function createEmbedMap(mapElem) {
    embedded = true;
    map = SOCIALHIVE.map.createMap(mapElem, {
      historyName: 'map',
      imagesBase: imagesBaseURL,
      defaultZoom: zoomRegions['world'].zoom,
      defaultCenter: {lat:zoomRegions['world'].lat, lon:zoomRegions['world'].lon},
      dataFeed: dataURL,
      dataType: 'xml', 
      onMarkerClick: showInfo,
      gettext: gettext,
      maxMarkers: 5000
    });
    init();
    return map;  
  }   
    
  if (!window.CUSTOMMAP)
    window.CUSTOMMAP = {};
  window.CUSTOMMAP.createMap = createMap;
  window.CUSTOMMAP.createEmbedMap = createEmbedMap;
  window.CUSTOMMAP.mapZoom = mapZoom;
  window.CUSTOMMAP.embedCodeClick = embedCodeClick;
  window.CUSTOMMAP.updateEmbedCode = updateEmbedCode;
});