/**
  Properties: 
    this.params -  params received from query string
    this.map - GMap object
    this.layers - layers storage
    this.controls - controls storage                                             
    this.currentURL - URL for receiving markers 
        
    this.mgr - MarkerManager object;
*/

/**
*   Init function which required for init required objects before GMap 
*/
WidgetMap.prototype.userInit = function() {
    //alert('userInitControls');
   
}

/**
*   User function for managing controls
*/
WidgetMap.prototype.userInitControls = function() {
    //alert('userInitControls');
  /*
    wmapObj = this;  
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
                    parent.document.location = "/en/index/event/";
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
                    parent.document.location = "/en/index/groups/";
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
   */      
   
}

/**
*   User function for managing layers
*/
WidgetMap.prototype.userInitLayers = function () {
    //alert('userInitLayers');

}

/**
*   User function for managing position
*/
WidgetMap.prototype.userInitPosition = function () { 
    //this.map.setCenter(new GLatLng(0, 0), 2);
    //alert('userInitPosition');

}

/**
*   User function for processing listeners
*/
WidgetMap.prototype.userInitListeners = function () {
    //alert('userInitListeners');

}