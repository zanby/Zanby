/***************************************************************************
This code is from Dynamic Web Coding at www.dyn-web.com
Copyright 2004 by Sharon Paine
See Terms of Use at www.dyn-web.com/bus/terms.html
regarding conditions under which you may use this code.
This notice must be retained in the code as is!
****************************************************************************/

/*
dw_scroll_aux.js    version date: May 2004
integrates scrolling layers code with scrollbar code (dw_scrollbar.js)
*/

// Size dragBar according to layer size?
dw_scrollObj.prototype.bSizeDragBar = true;

dw_scrollObj.prototype.setUpScrollbar = function(id, trkId, axis, offx, offy) {

    if (!document.getElementById) return;
    var bar = document.getElementById(id);
    var trk = document.getElementById(trkId);
    dw_slidebar.init(bar, trk, axis, offx, offy);
    // connect dw_slidebar with dw_scrollObj
    bar.wn = dw_scrollObjs[this.id]; // scroll area object this bar connected to
    if (axis == "v") this.vBarId = id; else this.hBarId = id;
    // also called on_load (i.e., when layer loaded), but in case h and v scrollbars, need to call here too
    if (this.bSizeDragBar) this.setBarSize();
    bar.on_drag_start = bar.on_slide_start = dw_scrollObj.getWndoLyrRef;
    bar.on_drag_end =   bar.on_slide_end =   dw_scrollObj.tossWndoLyrRef;
    bar.on_drag =       bar.on_slide =       dw_scrollObj.UpdateWndoLyrPos;
}

// for these 3 functions (assigned to bar.on_drag/slide...) "this" refers to bar
// get/discard ref to layer visible in scroll area
dw_scrollObj.getWndoLyrRef = function()  { this.wnLyr = document.getElementById(this.wn.lyrId); }
dw_scrollObj.tossWndoLyrRef = function() { this.wnLyr = null; }
// keep position of scrolling layer in synch with slide/drag of bar
dw_scrollObj.UpdateWndoLyrPos = function(x, y) {
    var nx, ny;
    if (this.axis == "v") {
        nx = this.wn.x; // floating point values for loaded layer's position held in shiftTo method
        ny = -(y - this.minY) * ( this.wn.maxY / (this.maxY - this.minY) ) || 0;
    } else {
        ny = this.wn.y;
        nx = -(x - this.minX) * ( this.wn.maxX / (this.maxX - this.minX) ) || 0;
    }
    this.wn.shiftTo(this.wnLyr, nx, ny);
}

// Keep position of dragBar in sync with position of layer onscroll
dw_scrollObj.prototype.updateScrollbar = function(x, y) {
    var nx, ny;
    if ( this.vBarId ) {
        if (!this.maxY) return;
        ny = -( y * ( (this.vbar.maxY - this.vbar.minY) / this.maxY ) - this.vbar.minY );
        ny = Math.min( Math.max(ny, this.vbar.minY), this.vbar.maxY);
        nx = parseInt(this.vbar.style.left);
        this.vbar.style.left = nx + "px"; this.vbar.style.top = ny + "px";
    } if ( this.hBarId ) {
        if (!this.maxX) return;
        nx = -( x * ( (this.hbar.maxX - this.hbar.minX) / this.maxX ) - this.hbar.minX );
        nx = Math.min( Math.max(nx, this.hbar.minX), this.hbar.maxX);
        ny = parseInt(this.hbar.style.top);
        this.hbar.style.left = nx + "px"; this.hbar.style.top = ny + "px";
    }

}

// Restore dragBar to start position when loading new layer
dw_scrollObj.prototype.restoreScrollbars = function() {
    var bar;
    if (this.vBarId) {
        bar = document.getElementById(this.vBarId);
        bar.style.left = bar.minX + "px"; bar.style.top = bar.minY + "px";
    }
    if (this.hBarId) {
        bar = document.getElementById(this.hBarId);
        bar.style.left = bar.minX + "px"; bar.style.top = bar.minY + "px";
    }
}

// Size dragBar in proportion to size of content in layer
// called on_load of layer if bSizeDragBar prop true
dw_scrollObj.prototype.setBarSize = function() {
    var bar;
    var lyr = document.getElementById(this.lyrId);
    var wn = document.getElementById(this.id);
    if (this.vBarId) {
        bar = document.getElementById(this.vBarId);
        bar.style.height = (lyr.offsetHeight > wn.offsetHeight)? bar.trkHt / ( lyr.offsetHeight / wn.offsetHeight ) + "px": bar.trkHt - 2*bar.minY + "px";
        bar.maxY = bar.trkHt - bar.offsetHeight - bar.minY;
    }
    if (this.hBarId) {
        bar = document.getElementById(this.hBarId);
        bar.style.width = (this.wd > wn.offsetWidth)? bar.trkWd / ( this.wd / wn.offsetWidth ) + "px": bar.trkWd - 2*bar.minX + "px";
        bar.maxX = bar.trkWd - bar.offsetWidth - bar.minX;
    }
}

// called from load method
dw_scrollObj.prototype.on_load = function() {
    this.restoreScrollbars();
    if (this.bSizeDragBar) this.setBarSize();
}

dw_scrollObj.prototype.on_scroll = dw_scrollObj.prototype.on_slide = function(x,y) { this.updateScrollbar(x,y); }

// obtain and discard references to relevant dragBar
dw_scrollObj.prototype.on_scroll_start = dw_scrollObj.prototype.on_slide_start = function() {
    if ( this.vBarId ) this.vbar = document.getElementById(this.vBarId);
    if ( this.hBarId ) this.hbar = document.getElementById(this.hBarId);
}

dw_scrollObj.prototype.on_scroll_end = dw_scrollObj.prototype.on_slide_end = function(x, y) {
    this.updateScrollbar(x,y);
    this.lyr = null; this.bar = null;
}

