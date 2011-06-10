/*************************************************************************
  This code is from Dynamic Web Coding at www.dyn-web.com
  Copyright 2001-4 by Sharon Paine 
  See Terms of Use at www.dyn-web.com/bus/terms.html
  regarding conditions under which you may use this code.
  This notice must be retained in the code as is!
*************************************************************************/

/* dw_glidescroll.js  version date: June 2004 
   glide onclick scrolling for dw_scrollObj (in dw_scrollObj.js)  */

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