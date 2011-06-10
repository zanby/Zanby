

if (!xajaxRequestManager) {
    var xajaxRequestManager = function () {
        return {
            OverlayManager : null,
            showProcessMessage : function (message, property) {
                if ( !this.OverlayManager ) this.OverlayManager = new YAHOO.widget.OverlayManager();
                if ( property.id !== undefined ) var id = property.id;
                else var id = "dialog";
                if ( property.fixedcenter === undefined )           property.fixedcenter = true;
                if ( property.visible === undefined )               property.visible = false;
                if ( property.width === undefined )                 property.width = "500px";
                if ( property.constraintoviewport === undefined )   property.constraintoviewport = true;
                if ( property.duration === undefined )              property.duration = 0.4;
                if ( property.showtitle === undefined )             property.showtitle = true;
                if ( property.underlay === undefined )              property.underlay = "none";
                var maxIndex = 0;
                for(var i=0; i<this.OverlayManager.overlays.length; i++) {
                    maxIndex = Math.max(maxIndex, this.OverlayManager.overlays[i].cfg.getProperty("zIndex"));
                }
                maxIndex = maxIndex + 100;

                if ( xajaxRequestManager.OverlayManager.find(id) ) {
                    var dialog = xajaxRequestManager.OverlayManager.find(id);
                    dialog.cfg.setProperty("zIndex", maxIndex);
                    if (message) {
                        dialog.setBody(message);
                        if ( property.showtitle == true ) {
                            if ( property.title === undefined ) {
                                dialog.setHeader('<div class="tl"></div>&nbsp;<div class="tr"></div>');
                            } else {
                                dialog.setHeader('<div class="tl"></div>'+property.title+'<div class="tr"></div>');
                            }
                        }
                    }
                    dialog.render();
                } else {
                    var dialog = new YAHOO.widget.Dialog(id, property);
                    dialog.setBody(message);
                    if ( property.showtitle == true ) {
                        if ( property.title === undefined ) {
                            dialog.setHeader('<div class="tl"></div>&nbsp;<div class="tr"></div>');
                        } else {
                            dialog.setHeader('<div class="tl"></div>'+property.title+'<div class="tr"></div>');
                        }
                    }
                    dialog.cfg.setProperty("zIndex", maxIndex);
                    dialog.render(document.body);
                    this.OverlayManager.register(dialog);
                    
                }
                dialog.show();
            },
            showProcessMessage1 : function (message, property) {
                /* overlay
                api = $("#overlay").overlay();
                api.getContent().html('<div class="close"/>'+message);
                $("#overlay .close").bind("click", function(){ api.close();});
                $("#overlay #closeOverlay").bind("click", function(){ api.close(); return false; });
                api.load(null, null, {width:400, heigth:300});
                */
                
                /* tickbox */
                if (!document.getElementById('aaaaTB')) {
                    var div = document.createElement('DIV');
                    div.id = 'aaaaTB';
                    document.body.appendChild(div);
                    alert(111);
                }
                document.getElementById('aaaaTB').innerHTML = message;

                $("#TB_ajaxContent #closeOverlay").bind("click", function(){ tb_remove(); return false; });
                //url = "#TB_inline?modal=true&height=300&width=500&inlineId=aaaaTB";
                url = "#TB_inline?height=300&width=500&inlineId=aaaaTB";
                tb_show("Caption1111", url);
            }
        };
    }();
}
