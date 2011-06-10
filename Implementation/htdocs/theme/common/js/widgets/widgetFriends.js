var WidgetFriends = null;
if ( !WidgetFriends ) {
    WidgetFriends = function () {
        return {
            property1: null,
            property2: null,
            property3: {},
            init : function () {
                WidgetFriends.property1 = 'map';
            },   
            
            factory : function (paramsObj) {
            	document.getElementById('widgetContainer'+paramsObj.cloneId).innerHTML = paramsObj.count;
            }
        }
    }();
};
