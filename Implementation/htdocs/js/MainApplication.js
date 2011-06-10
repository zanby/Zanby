    var MainApplication = null;
    if ( !MainApplication ) {
        MainApplication = function () {
            return {
                ajaxMessagePanel : null,
                ajaxMessageAlert : null,
                showAjaxAlertProperty : {},
                ajaxMessageAlertTimer : null,
                checkScrollTimeout : null,
                init : function () {
                    MainApplication.ajaxMessagePanel = new YAHOO.widget.Panel('ajaxMessagePanel',
                        {width : "450px", underlay:"none", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
                    );
                    MainApplication.ajaxMessagePanel.render();

                    MainApplication.ajaxMessageAlert = YAHOO.util.Dom.get('ajaxMessageAlert');             
                },
                showAjaxAlertFromPhp : function () {
                    MainApplication.showAjaxAlert(this);
                },
                showAjaxMessage : function () {
                    if ( arguments.length != 0 ) var property = arguments[0];                            
                    else var property = {};            
                    YAHOO.util.Dom.get('ajaxMessagePanel').style.display = "";
                    MainApplication.ajaxMessagePanel.cfg.setProperty("close",true);
                    if ( property.width !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("width", property.width + 'px');
                    if ( property.height !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("height", property.height + 'px');
                    if ( property.fixedcenter !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("fixedcenter", property.fixedcenter);
                    if ( property.x !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("x", property.x);
                    if ( property.y !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("y", property.y);
                    if ( property.constraintoviewport !== undefined ) MainApplication.ajaxMessagePanel.cfg.setProperty("constraintoviewport", property.constraintoviewport);                    
                    MainApplication.ajaxMessagePanel.render();
                    MainApplication.ajaxMessagePanel.show();
                },
                showConfirmAjaxMessage : function () {
                    YAHOO.util.Dom.get('ajaxMessagePanel').style.display = "";
                    MainApplication.ajaxMessagePanel.cfg.setProperty("close",false);
                    MainApplication.ajaxMessagePanel.show();
                },
                hideAjaxMessage : function () {
                    MainApplication.ajaxMessagePanel.hide();
                },
                showAjaxAlert : function (property) {
                    if ( MainApplication.ajaxMessageAlert.style.display == '' ) return;
                
                    MainApplication.showAjaxAlertProperty = {};
                    if ( property.width === undefined ) property.width          = 250;
                    if ( property.height === undefined ) property.height        = 50;
                    if ( property.timeout === undefined ) property.timeout      = 1500;
                    if ( property.content === undefined ) property.content      = 'Saved';
                    MainApplication.showAjaxAlertProperty = property;
                    
                    MainApplication.ajaxMessageAlert.style.width = property.width + 'px';
                    MainApplication.ajaxMessageAlert.style.height = property.height + 'px';            

					var popup = document.getElementById('ajaxMessageAlert');
					var currentHeight = MainApplication.ajaxMessageAlert.style.height.replace('px', '');
					popup.getElementsByTagName('h5')[0].style.marginTop = (currentHeight/2 - 9) + 'px'; /*9 is half of own height of text*/

					
                    var MainNavigation = YAHOO.util.Dom.get('MainNavigation');
                    var MainNavigationRegion = YAHOO.util.Dom.getRegion(MainNavigation);
                    var positionTop = ( YAHOO.zanby.browser.BrowserDetect.browser == 'Safari' )? document.body.scrollTop : parseInt(document.documentElement.scrollTop);

                    MainApplication.ajaxMessageAlert.style.top = parseInt(positionTop - MainApplication.showAjaxAlertProperty.height - 5) + "px";
                    if (MainNavigationRegion) {
                    	MainApplication.ajaxMessageAlert.style.left = (MainNavigationRegion.right - MainApplication.showAjaxAlertProperty.width - 5) + 'px';
                    }
                    
                    if ( YAHOO.util.Dom.get(MainApplication.showAjaxAlertProperty.content) ) {
                        YAHOO.util.Dom.get('ajaxMessageAlertContent').innerHTML = YAHOO.util.Dom.get(MainApplication.showAjaxAlertProperty.content).innerHTML;
                    } else {
                        YAHOO.util.Dom.get('ajaxMessageAlertContent').innerHTML = MainApplication.showAjaxAlertProperty.content;
                    }
                    
                    MainApplication.ajaxMessageAlert.style.display = '';
                    MainApplication.ajaxMessageAlert.style.visibility = 'visible';

                    var showAjaxAlertAnim = new YAHOO.util.Anim('ajaxMessageAlert');
                    showAjaxAlertAnim.attributes.top = { to: parseInt(positionTop) + 5 };
                    showAjaxAlertAnim.duration = 0.5;
                    showAjaxAlertAnim.method = YAHOO.util.Easing.easeOut;
                    showAjaxAlertAnim.onComplete.subscribe(MainApplication.showAjaxAlertAnimComplete);
                    showAjaxAlertAnim.animate();
                },
                hideAjaxAlert : function () {
                    var positionTop = ( YAHOO.zanby.browser.BrowserDetect.browser == 'Safari' )? document.body.scrollTop : parseInt(document.documentElement.scrollTop);

                    MainApplication.ajaxMessageAlertTimer = clearTimeout(MainApplication.ajaxMessageAlertTimer);
                    MainApplication.checkScrollTimeout = clearTimeout(MainApplication.checkScrollTimeout);
                    var hideAjaxAlertAnim = new YAHOO.util.Anim('ajaxMessageAlert');
                    hideAjaxAlertAnim.attributes.top = { to: parseInt(positionTop - MainApplication.showAjaxAlertProperty.height - 5) };
                    hideAjaxAlertAnim.duration = 0.5;
                    hideAjaxAlertAnim.method = YAHOO.util.Easing.easeOut;
                    hideAjaxAlertAnim.onComplete.subscribe(MainApplication.hideAjaxAlertAnimComplete);
                    hideAjaxAlertAnim.animate();                                  
                },
                showAjaxAlertAnimComplete : function () {
                    MainApplication.ajaxMessageAlertTimer = setTimeout('MainApplication.hideAjaxAlert()', MainApplication.showAjaxAlertProperty.timeout);
                    MainApplication.checkScrollTimeout = setTimeout("MainApplication.checkScroll();", 1);
                },
                hideAjaxAlertAnimComplete : function () {
                    MainApplication.ajaxMessageAlert.style.display = 'none';
                    MainApplication.ajaxMessageAlert.style.visibility = 'hidden'; 
                },
                checkScroll : function () {
                    if ( MainApplication.checkScrollTimeout ) {
                        var positionTop = ( YAHOO.zanby.browser.BrowserDetect.browser == 'Safari' )? document.body.scrollTop : parseInt(document.documentElement.scrollTop);
                        
                        if ( parseInt(MainApplication.ajaxMessageAlert.style.top) != parseInt(positionTop + 5) ) {
                            MainApplication.ajaxMessageAlertTimer = clearTimeout(MainApplication.ajaxMessageAlertTimer);
                            MainApplication.checkScrollTimeout = clearTimeout(MainApplication.checkScrollTimeout);
                            MainApplication.hideAjaxAlertAnimComplete();
                            MainApplication.ajaxMessageAlertTimer = null;
                            MainApplication.checkScrollTimeout = null;
                        } else {
                            MainApplication.checkScrollTimeout = setTimeout("MainApplication.checkScroll();",1);
                        }                       
                    }
                }
            }
        }();
    };
