var DDMenu = null;
if ( DDMenu == null ) {
    DDMenu = function(){
        return {
            menu : null,
            event : null,
            timeoutID : null,
            init : function () {
                DDMenu.menu = $('#groupsListMenu').menu({
                    content: $('#groupsListMenuContainer').html(),
                    width : 180,
                    positionOpts: {posX: 'left', posY: 'bottom', offsetX: -75, offsetY: 12, directionH: 'right',directionV: 'down', detectH: true, detectV: false, linkToFront: false}
                });
            },
            showDDMenu : function () {
                if ( DDMenu.timeoutID != null ) {
                    window.clearTimeout(DDMenu.timeoutID);
                    DDMenu.timeoutID = null;
                }
                if ( DDMenu.menu == null ) return;
                DDMenu.menu.showMenu();
            },
            hideDDMenu : function (event) {
                if ( DDMenu.timeoutID != null ) {
                    window.clearTimeout(DDMenu.timeoutID);
                    DDMenu.timeoutID = null;
                }
                var containerArea = YAHOO.util.Dom.getRegion(DDMenu.menu.container);
                if ( parseInt(YAHOO.util.Event.getPageX(event), 10)-10 < DDMenu.extractPosition('left', containerArea.toString())  ||
                     parseInt(YAHOO.util.Event.getPageX(event), 10)+10 > DDMenu.extractPosition('right', containerArea.toString()) ||
                     parseInt(YAHOO.util.Event.getPageY(event), 10)-10 < DDMenu.extractPosition('top', containerArea.toString())   ||
                     parseInt(YAHOO.util.Event.getPageY(event), 10)+10 > DDMenu.extractPosition('bottom', containerArea.toString())
                ) {
                    DDMenu.event = null;
                    DDMenu.timeoutID = window.setTimeout('DDMenu.softKillDDMenu()', 400);
                }
            },
            extractPosition : function (pos, string) {
                var reg = new RegExp(pos+"[^0-9]+([0-9]+)", "i");
                var myArr = reg.exec(string);
                return (myArr != null && myArr[1] != null) ? myArr[1] : 0;
            },
            softKillDDMenu : function () {
                var containerArea = YAHOO.util.Dom.getRegion(DDMenu.menu.container);
                if ( DDMenu.event != null && !(
                     parseInt(YAHOO.util.Event.getPageX(DDMenu.event), 10)-10 < DDMenu.extractPosition('left', containerArea.toString())  ||
                     parseInt(YAHOO.util.Event.getPageX(DDMenu.event), 10)+10 > DDMenu.extractPosition('right', containerArea.toString()) ||
                     parseInt(YAHOO.util.Event.getPageY(DDMenu.event), 10)-10 < DDMenu.extractPosition('top', containerArea.toString())   ||
                     parseInt(YAHOO.util.Event.getPageY(DDMenu.event), 10)+10 > DDMenu.extractPosition('bottom', containerArea.toString())
                )) {
                    DDMenu.event = null;
                    return true;
                }
                DDMenu.event = null;
                DDMenu.menu.kill();
                return true;
            },
            killDDMenu : function () {
                DDMenu.menu.kill();
            },
            underDDMenu : function (event) {
                DDMenu.event = event;
            }
        }
    }();
}

$( DDMenu.init );
