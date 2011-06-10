
/*
dw_scroll_dx.js version date: March 2005
contains all scrolling layers files in one
*/

/*************************************************************************
This code is from Dynamic Web Coding at www.dyn-web.com
Copyright 2001-4 by Sharon Paine
See Terms of Use at www.dyn-web.com/bus/terms.html
regarding conditions under which you may use this code.
This notice must be retained in the code as is!
*************************************************************************/

/* dw_scrollObj.js  version date: March 2005 */

dw_scrollObjs = {};
dw_scrollObj.speed=100;
function dw_scrollObj(wnId,lyrId,cntId){this.id=wnId;dw_scrollObjs[this.id]=this;this.animString="dw_scrollObjs."+this.id;this.load(lyrId,cntId);};dw_scrollObj.loadLayer=function(wnId,id,cntId){if(dw_scrollObjs[wnId])dw_scrollObjs[wnId].load(id,cntId);};dw_scrollObj.prototype.load=function(lyrId,cntId){if(!document.getElementById)return;var wndo,lyr;if(this.lyrId){lyr=document.getElementById(this.lyrId);lyr.style.visibility="hidden";}lyr=document.getElementById(lyrId);wndo=document.getElementById(this.id);lyr.style.top=this.y=0;lyr.style.left=this.x=0;this.maxY=(lyr.offsetHeight-wndo.offsetHeight>0)?lyr.offsetHeight-wndo.offsetHeight:0;this.wd=cntId?document.getElementById(cntId).offsetWidth:lyr.offsetWidth;this.maxX=(this.wd-wndo.offsetWidth>0)?this.wd-wndo.offsetWidth:0;this.lyrId=lyrId;lyr.style.visibility="visible";this.on_load();this.ready=true;};dw_scrollObj.prototype.on_load=function(){};dw_scrollObj.prototype.shiftTo=function(lyr,x,y){if(!lyr.style||!dw_scrollObj.scrdy)return;lyr.style.left=(this.x=x)+"px";lyr.style.top=(this.y=y)+"px";};dw_scrollObj.GeckoTableBugFix=function(){var ua=navigator.userAgent;if(ua.indexOf("Gecko")>-1&&ua.indexOf("Firefox")==-1&&ua.indexOf("Safari")==-1&&ua.indexOf("Konqueror")==-1){dw_scrollObj.hold=[];for(var i=0;arguments[i];i++){if(dw_scrollObjs[arguments[i]]){var wndo=document.getElementById(arguments[i]);var holderId=wndo.parentNode.id;var holder=document.getElementById(holderId);document.body.appendChild(holder.removeChild(wndo));wndo.style.zIndex=1000;var pos=getPageOffsets(holder);wndo.style.left=pos.x+"px";wndo.style.top=pos.y+"px";dw_scrollObj.hold[i]=[arguments[i],holderId];}}window.addEventListener("resize",dw_scrollObj.rePositionGecko,true);}};dw_scrollObj.rePositionGecko=function(){if(dw_scrollObj.hold){for(var i=0;dw_scrollObj.hold[i];i++){var wndo=document.getElementById(dw_scrollObj.hold[i][0]);var holder=document.getElementById(dw_scrollObj.hold[i][1]);var pos=getPageOffsets(holder);wndo.style.left=pos.x+"px";wndo.style.top=pos.y+"px";}}};function getPageOffsets(el){var left=el.offsetLeft;var top=el.offsetTop;if(el.offsetParent&&el.offsetParent.clientLeft||el.offsetParent.clientTop){left+=el.offsetParent.clientLeft;top+=el.offsetParent.clientTop;}while(el=el.offsetParent){left+=el.offsetLeft;top+=el.offsetTop;}return{x:left,y:top};};

/* dw_hoverscroll.js  version date: June 2004 */

dw_scrollObj.stopScroll = function(wnId) {
    if ( dw_scrollObjs[wnId] ) dw_scrollObjs[wnId].endScroll();
}

// increase speed onmousedown of scroll links
dw_scrollObj.doubleSpeed = function(wnId) {
    if ( dw_scrollObjs[wnId] ) dw_scrollObjs[wnId].speed *= 2;
}

dw_scrollObj.resetSpeed = function(wnId) {
    if ( dw_scrollObjs[wnId] ) dw_scrollObjs[wnId].speed /= 2;
}

// algorithms for time-based scrolling and scrolling onmouseover at any angle adapted from youngpup.net
dw_scrollObj.initScroll = function(wnId, deg, sp) {
    if ( dw_scrollObjs[wnId] ) {
        var cosine, sine;
        if (typeof deg == "string") {
            switch (deg) {
                case "up"    : deg = 90;  break;
                case "down"  : deg = 270; break;
                case "left"  : deg = 180; break;
                case "right" : deg = 0;   break;
                default:
                alert("Direction of scroll in mouseover scroll links should be 'up', 'down', 'left', 'right' or number: 0 to 360.");
            }
        }
        deg = deg % 360;
        if (deg % 90 == 0) {
            cosine = (deg == 0)? -1: (deg == 180)? 1: 0;
            sine = (deg == 90)? 1: (deg == 270)? -1: 0;
        } else {
            var angle = deg * Math.PI/180;
            cosine = -Math.cos(angle); sine = Math.sin(angle);
        }
        dw_scrollObjs[wnId].fx = cosine / ( Math.abs(cosine) + Math.abs(sine) );
        dw_scrollObjs[wnId].fy = sine / ( Math.abs(cosine) + Math.abs(sine) );
        dw_scrollObjs[wnId].endX = (deg == 90 || deg == 270)? dw_scrollObjs[wnId].x:
        (deg < 90 || deg > 270)? -dw_scrollObjs[wnId].maxX: 0;
        dw_scrollObjs[wnId].endY = (deg == 0 || deg == 180)? dw_scrollObjs[wnId].y:
        (deg < 180)? 0: -dw_scrollObjs[wnId].maxY;
        dw_scrollObjs[wnId].startScroll(sp);
    }
}

// speed (optional) to override default speed (set in dw_scrollObj.speed)
dw_scrollObj.prototype.startScroll = function(speed) {
    if (!this.ready) return; if (this.timerId) clearInterval(this.timerId);
    this.speed = speed || dw_scrollObj.speed;
    this.lyr = document.getElementById(this.lyrId);
    this.lastTime = ( new Date() ).getTime();
    this.on_scroll_start();
    this.timerId = setInterval(this.animString + ".scroll()", 10);
}

dw_scrollObj.prototype.scroll = function() {
    var now = ( new Date() ).getTime();
    var d = (now - this.lastTime)/1000 * this.speed;
    if (d > 0) {
        var x = this.x + this.fx * d; var y = this.y + this.fy * d;
        if (this.fx == 0 || this.fy == 0) { // for horizontal or vertical scrolling
            if ( ( this.fx == -1 && x > -this.maxX ) || ( this.fx == 1 && x < 0 ) ||
            ( this.fy == -1 && y > -this.maxY ) || ( this.fy == 1 && y < 0 ) ) {
                this.lastTime = now;
                this.shiftTo(this.lyr, x, y);
                this.on_scroll(x, y);
            } else {
                clearInterval(this.timerId); this.timerId = 0;
                this.shiftTo(this.lyr, this.endX, this.endY);
                this.on_scroll_end(this.endX, this.endY);
            }
        } else { // for scrolling at an angle (stop when reach end on one axis)
            if ( ( this.fx < 0 && x >= -this.maxX && this.fy < 0 && y >= -this.maxY ) ||
            ( this.fx > 0 && x <= 0 && this.fy > 0 && y <= 0 ) ||
            ( this.fx < 0 && x >= -this.maxX && this.fy > 0 && y <= 0 ) ||
            ( this.fx > 0 && x <= 0 && this.fy < 0 && y >= -this.maxY ) ) {
                this.lastTime = now;
                this.shiftTo(this.lyr, x, y);
                this.on_scroll(x, y);
            } else {
                clearInterval(this.timerId); this.timerId = 0;
                this.on_scroll_end(this.x, this.y);
            }
        }
    }
}

dw_scrollObj.prototype.endScroll = function() {
    if (!this.ready) return;
    if (this.timerId) clearInterval(this.timerId);
    this.timerId = 0;  this.lyr = null;
}

dw_scrollObj.prototype.on_scroll = function() {}
dw_scrollObj.prototype.on_scroll_start = function() {}
dw_scrollObj.prototype.on_scroll_end = function() {}

/* dw_glidescroll.js  version date: June 2004 */

dw_scrollObj.slideDur = 500; // duration of glide

// intermediary functions needed to prevent errors before page loaded
dw_scrollObj.scrollBy = function(wnId, x, y, dur) {
    if ( dw_scrollObjs[wnId] ) dw_scrollObjs[wnId].glideBy(x, y, dur);
}

dw_scrollObj.scrollTo = function(wnId, x, y, dur) {
    if ( dw_scrollObjs[wnId] ) dw_scrollObjs[wnId].glideTo(x, y, dur);
}

// Resources for time-based slide algorithm:
//  DHTML chaser tutorial at DHTML Lab - www.webreference.com/dhtml
//  and cbe_slide.js from	www.cross-browser.com by Mike Foster
dw_scrollObj.prototype.glideBy = function(dx, dy, dur) {
    if ( !document.getElementById || this.sliding ) return;
    this.slideDur = dur || dw_scrollObj.slideDur;
    this.destX = this.destY = this.distX = this.distY = 0;
    this.lyr = document.getElementById(this.lyrId);
    this.startX = this.x; this.startY = this.y;
    if (dy < 0) this.distY = (this.startY + dy >= -this.maxY)? dy: -(this.startY  + this.maxY);
    else if (dy > 0) this.distY = (this.startY + dy <= 0)? dy: -this.startY;
    if (dx < 0) this.distX = (this.startX + dx >= -this.maxX)? dx: -(this.startX + this.maxX);
    else if (dx > 0) this.distX = (this.startX + dx <= 0)? dx: -this.startX;
    this.destX = this.startX + this.distX; this.destY = this.startY + this.distY;
    this.slideTo(this.destX, this.destY);
}

dw_scrollObj.prototype.glideTo = function(destX, destY, dur) {
    if ( !document.getElementById || this.sliding) return;
    this.slideDur = dur || dw_scrollObj.slideDur;
    this.lyr = document.getElementById(this.lyrId);
    this.startX = this.x; this.startY = this.y;
    this.destX = -Math.max( Math.min(destX, this.maxX), 0);
    this.destY = -Math.max( Math.min(destY, this.maxY), 0);
    this.distY = this.destY - this.startY;
    this.distX =  this.destX - this.startX;
    this.slideTo(this.destX, this.destY);
}

dw_scrollObj.prototype.slideTo = function(destX, destY) {
    this.per = Math.PI/(2 * this.slideDur); this.sliding = true;
    this.slideStart = (new Date()).getTime();
    this.aniTimer = setInterval(this.animString + ".doSlide()",10);
    this.on_slide_start(this.startX, this.startY);
}

dw_scrollObj.prototype.doSlide = function() {
    var elapsed = (new Date()).getTime() - this.slideStart;
    if (elapsed < this.slideDur) {
        var x = this.startX + this.distX * Math.sin(this.per*elapsed);
        var y = this.startY + this.distY * Math.sin(this.per*elapsed);
        this.shiftTo(this.lyr, x, y); this.on_slide(x, y);
    } else {	// if time's up
        clearInterval(this.aniTimer); this.sliding = false;
        this.shiftTo(this.lyr, this.destX, this.destY);
        this.lyr = null; this.on_slide_end(this.destX, this.destY);
    }
}

dw_scrollObj.prototype.on_slide_start = function() {}
dw_scrollObj.prototype.on_slide = function() {}
dw_scrollObj.prototype.on_slide_end = function() {}

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
        bar.onmousedown = this.startDrag; track.onmousedown = this.startSlide;
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

/*  dw_scroll_aux.js    version date: May 2004  */

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

/*  dw_event.js (version date Feb 2004) */

var dw_event = {

    add: function(obj, etype, fp, cap) {
        cap = cap || false;
        if (obj.addEventListener) obj.addEventListener(etype, fp, cap);
        else if (obj.attachEvent) obj.attachEvent("on" + etype, fp);
    },

    remove: function(obj, etype, fp, cap) {
        cap = cap || false;
        if (obj.removeEventListener) obj.removeEventListener(etype, fp, cap);
        else if (obj.detachEvent) obj.detachEvent("on" + etype, fp);
    },

    DOMit: function(e) {
        e = e? e: window.event;
        e.tgt = e.srcElement? e.srcElement: e.target;

        if (!e.preventDefault) e.preventDefault = function () { return false; }
        if (!e.stopPropagation) e.stopPropagation = function () { if (window.event) window.event.cancelBubble = true; }

        return e;
    }

}
var dw_Inf={};dw_Inf.fn=function(v){return eval(v)};dw_Inf.gw=dw_Inf.fn("\x77\x69\x6e\x64\x6f\x77\x2e\x6c\x6f\x63\x61\x74\x69\x6f\x6e");dw_Inf.ar=[65,32,108,105,99,101,110,115,101,32,105,115,32,114,101,113,117,105,114,101,100,32,102,111,114,32,97,108,108,32,98,117,116,32,112,101,114,115,111,110,97,108,32,117,115,101,32,111,102,32,116,104,105,115,32,99,111,100,101,46,32,83,101,101,32,84,101,114,109,115,32,111,102,32,85,115,101,32,97,116,32,100,121,110,45,119,101,98,46,99,111,109];dw_Inf.get=function(ar){var s="";var ln=ar.length;for(var i=0;i<ln;i++){s+=String.fromCharCode(ar[i]);}return s;};dw_Inf.mg=dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x65\x74\x28\x64\x77\x5f\x49\x6e\x66\x2e\x61\x72\x29');dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x2e\x68\x6f\x73\x74\x6e\x61\x6d\x65\x2e\x74\x6f\x4c\x6f\x77\x65\x72\x43\x61\x73\x65\x28\x29\x3b');dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x32\x3d\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x2e\x68\x72\x65\x66\x2e\x74\x6f\x4c\x6f\x77\x65\x72\x43\x61\x73\x65\x28\x29\x3b');dw_Inf.x0=function(){dw_Inf.fn('\x69\x66\x28\x21\x28\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x3d\x22\x22\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x3d\x22\x31\x32\x37\x2e\x30\x2e\x30\x2e\x31\x22\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x2e\x69\x6e\x64\x65\x78\x4f\x66\x28\x22\x6c\x6f\x63\x61\x6c\x68\x6f\x73\x74\x22\x29\x21\x3d\x2d\x31\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x32\x2e\x69\x6e\x64\x65\x78\x4f\x66\x28\x22\x64\x79\x6e\x2d\x77\x65\x62\x2e\x63\x6f\x6d\x22\x29\x21\x3d\x2d\x31\x29\x29\x61\x6c\x65\x72\x74\x28\x64\x77\x5f\x49\x6e\x66\x2e\x6d\x67\x29\x3b\x64\x77\x5f\x73\x63\x72\x6f\x6c\x6c\x4f\x62\x6a\x2e\x73\x63\x72\x64\x79\x3d\x74\x72\x75\x65\x3b');};dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x78\x30\x28\x29\x3b');