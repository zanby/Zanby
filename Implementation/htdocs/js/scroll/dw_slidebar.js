/*************************************************************************
This code is from Dynamic Web Coding at www.dyn-web.com
Copyright 2004 by Sharon Paine
See Terms of Use at www.dyn-web.com/bus/terms.html
regarding conditions under which you may use this code.
This notice must be retained in the code as is!
*************************************************************************/

/*   dw_slidebar.js   version date: Feb 2004   requires dw_event.js   */

// model: Aaron Boodman's dom drag at www.youngpup.net
var dw_slidebar = {
    obj: null,
    slideDur: 500,  // duration of glide onclick of track
    init: function (bar, track, axis, x, y) {
        x = x || 0; y = y || 0;
        bar.style.left = x + "px"; bar.style.top = y + "px";
        bar.axis = axis; track.bar = bar;
        if (axis == "h") {
            bar.trkWd = track.offsetWidth; // hold for setBarSize
            bar.maxX = bar.trkWd - bar.offsetWidth - x;
            bar.minX = x; bar.maxY = y; bar.minY = y;
        } else {
            bar.trkHt = track.offsetHeight;
            bar.maxY = bar.trkHt - bar.offsetHeight - y;
            bar.maxX = x; bar.minX = x; bar.minY = y;
        }
        bar.on_drag_start =  bar.on_drag =   bar.on_drag_end =
        bar.on_slide_start = bar.on_slide =  bar.on_slide_end = function() {}
        bar.onmousedown = this.startDrag;
        track.onmousedown = this.startSlide;
    },

    startSlide: function(e) { // called onmousedown of track
        if ( dw_slidebar.aniTimer ) clearInterval(dw_slidebar.aniTimer);
        e = e? e: window.event;
        var bar = dw_slidebar.obj = this.bar; // i.e., track's bar
        e.offX = (typeof e.layerX != "undefined")? e.layerX: e.offsetX;
        e.offY = (typeof e.layerY != "undefined")? e.layerY: e.offsetY;
        bar.startX = parseInt(bar.style.left); bar.startY = parseInt(bar.style.top);
        if (bar.axis == "v") {
            bar.destX = bar.startX;
            bar.destY = (e.offY < bar.startY)? e.offY: e.offY - bar.offsetHeight;
            bar.destY = Math.min( Math.max(bar.destY, bar.minY), bar.maxY );
        } else {
            bar.destX = (e.offX < bar.startX)? e.offX: e.offX - bar.offsetWidth;
            bar.destX = Math.min( Math.max(bar.destX, bar.minX), bar.maxX );
            bar.destY = bar.startY;
        }
        bar.distX = bar.destX - bar.startX; bar.distY = bar.destY - bar.startY;
        dw_slidebar.per = Math.PI/(2 * dw_slidebar.slideDur);
        dw_slidebar.slideStart = (new Date()).getTime();
        bar.on_slide_start(bar.startX, bar.startY);
        dw_slidebar.aniTimer = setInterval("dw_slidebar.doSlide()",10);
    },

    doSlide: function() {
        if ( !dw_slidebar.obj ) { clearInterval(dw_slidebar.aniTimer); return; }
        var bar = dw_slidebar.obj;
        var elapsed = (new Date()).getTime() - this.slideStart;
        if (elapsed < this.slideDur) {
            var x = bar.startX + bar.distX * Math.sin(this.per*elapsed);
            var y = bar.startY + bar.distY * Math.sin(this.per*elapsed);
            bar.style.left = x + "px"; bar.style.top = y + "px";
            bar.on_slide(x, y);
        } else {	// if time's up
            clearInterval(this.aniTimer);
            bar.style.left = bar.destX + "px"; bar.style.top = bar.destY + "px";
            bar.on_slide_end(bar.destX, bar.destY);
            this.obj = null;
        }
    },

    startDrag: function (e) { // called onmousedown of bar
        e = dw_event.DOMit(e);
        if ( dw_slidebar.aniTimer ) clearInterval(dw_slidebar.aniTimer);
        var bar = dw_slidebar.obj = this;
        bar.downX = e.clientX; bar.downY = e.clientY;
        bar.startX = parseInt(bar.style.left);
        bar.startY = parseInt(bar.style.top);
        bar.on_drag_start(bar.startX, bar.startY);
        dw_event.add( document, "mousemove", dw_slidebar.doDrag, true );
        dw_event.add( document, "mouseup",   dw_slidebar.endDrag,  true );
        e.stopPropagation();
    },

    doDrag: function (e) {
        e = e? e: window.event;
        if (!dw_slidebar.obj) return;
        var bar = dw_slidebar.obj;
        var nx = bar.startX + e.clientX - bar.downX;
        var ny = bar.startY + e.clientY - bar.downY;
        nx = Math.min( Math.max( bar.minX, nx ), bar.maxX);
        ny = Math.min( Math.max( bar.minY, ny ), bar.maxY);
        bar.style.left = nx + "px"; bar.style.top  = ny + "px";
        bar.on_drag(nx,ny);
        return false;
    },

    endDrag: function () {
        dw_event.remove( document, "mousemove", dw_slidebar.doDrag, true );
        dw_event.remove( document, "mouseup",   dw_slidebar.endDrag,  true );
        if ( !dw_slidebar.obj ) return; // avoid errors in ie if inappropriate selections
        dw_slidebar.obj.on_drag_end( parseInt(dw_slidebar.obj.style.left), parseInt(dw_slidebar.obj.style.top) );
        dw_slidebar.obj = null;
    }

}

