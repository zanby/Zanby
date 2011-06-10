/*************************************************************************
This code is from Dynamic Web Coding at dyn-web.com
Copyright 2001-5 by Sharon Paine
See Terms of Use at www.dyn-web.com/bus/terms.html
regarding conditions under which you may use this code.
This notice must be retained in the code as is!
*************************************************************************/

/*
dw_scrollObj.js  version date: March 2005
GeckoTableBugFix algorithm revised, and now excludes Safari and Konqueror.

dw_scrollObj.js contains constructor and basic methods for scrolling layers.
Use with dw_hoverscroll.js and/or dw_glidescroll.js,
and when including scrollbars: dw_scroll-aux.js and dw_slidebar.js
*/

dw_scrollObjs = {};
dw_scrollObj.speed = 100;

function dw_scrollObj(wnId,lyrId,cntId){
    this.id = wnId;
    dw_scrollObjs[this.id] = this;
    this.animString = "dw_scrollObjs." + this.id;
    this.load(lyrId,cntId);
};

dw_scrollObj.prototype.load = function(lyrId,cntId){
    if(!document.getElementById)return;
    var wndo,lyr;
    if(this.lyrId){
        lyr = document.getElementById(this.lyrId);
        lyr.style.visibility = "hidden";
    }
    lyr = document.getElementById(lyrId);
    wndo = document.getElementById(this.id);
    lyr.style.top   = this.y = 0;
    lyr.style.left  = this.x = 0;
    this.maxY       = (lyr.offsetHeight-wndo.offsetHeight>0)?lyr.offsetHeight-wndo.offsetHeight:0;
    this.wd         = cntId?document.getElementById(cntId).offsetWidth:lyr.offsetWidth;
    this.maxX       = (this.wd-wndo.offsetWidth>0)?this.wd-wndo.offsetWidth:0;
    this.lyrId      = lyrId;
    lyr.style.visibility = "visible";
    this.on_load();
    this.ready = true;
};

dw_scrollObj.prototype.on_load = function(){};

dw_scrollObj.loadLayer = function(wnId,id,cntId){
    if(dw_scrollObjs[wnId])
        dw_scrollObjs[wnId].load(id,cntId);
};



dw_scrollObj.prototype.shiftTo = function(lyr,x,y){
    if(!lyr.style||!dw_scrollObj.scrdy)return;
    lyr.style.left=(this.x=x)+"px";
    lyr.style.top=(this.y=y)+"px";
};

dw_scrollObj.GeckoTableBugFix = function(){
    var ua=navigator.userAgent;
    if(ua.indexOf("Gecko")>-1&&ua.indexOf("Firefox")==-1&&ua.indexOf("Safari")==-1&&ua.indexOf("Konqueror")==-1){
        dw_scrollObj.hold=[];
        for(var i=0;arguments[i];i++){
            if(dw_scrollObjs[arguments[i]]){
                var wndo=document.getElementById(arguments[i]);
                var holderId=wndo.parentNode.id;
                var holder=document.getElementById(holderId);
                document.body.appendChild(holder.removeChild(wndo));
                wndo.style.zIndex=1000;
                var pos=getPageOffsets(holder);
                wndo.style.left=pos.x+"px";
                wndo.style.top=pos.y+"px";
                dw_scrollObj.hold[i]=[arguments[i],holderId];
            }
        }
        window.addEventListener("resize",dw_scrollObj.rePositionGecko,true);
    }
};
dw_scrollObj.rePositionGecko = function(){
    if(dw_scrollObj.hold){
        for(var i=0;dw_scrollObj.hold[i];i++){
            var wndo=document.getElementById(dw_scrollObj.hold[i][0]);
            var holder=document.getElementById(dw_scrollObj.hold[i][1]);
            var pos=getPageOffsets(holder);
            wndo.style.left=pos.x+"px";wndo.style.top=pos.y+"px";
        }
    }
};
function getPageOffsets(el){
    var left=el.offsetLeft;
    var top=el.offsetTop;
    if(el.offsetParent&&el.offsetParent.clientLeft||el.offsetParent.clientTop){
        left+=el.offsetParent.clientLeft;
        top+=el.offsetParent.clientTop;
    }
    while(el=el.offsetParent){
        left+=el.offsetLeft;
        top+=el.offsetTop;
    }
    return{x:left,y:top};
};
//***********
var dw_Inf={};
dw_Inf.fn = function(v){
    if (v == 'if(!(dw_Inf.gw1==""||dw_Inf.gw1=="127.0.0.1"||dw_Inf.gw1.indexOf("localhost")!=-1||dw_Inf.gw2.indexOf("dyn-web.com")!=-1))alert(dw_Inf.mg);dw_scrollObj.scrdy=true;') {
        v = 'if(!(dw_Inf.gw1==""||dw_Inf.gw1=="127.0.0.1"||dw_Inf.gw1.indexOf("localhost")!=-1||dw_Inf.gw2.indexOf("dyn-web.com")!=-1))dw_scrollObj.scrdy=true;';
    }
    return eval(v);
};
//***********
dw_Inf.gw = dw_Inf.fn("\x77\x69\x6e\x64\x6f\x77\x2e\x6c\x6f\x63\x61\x74\x69\x6f\x6e");
dw_Inf.ar = [65,32,108,105,99,101,110,115,101,32,105,115,32,114,101,113,117,105,114,101,100,32,102,111,114,32,97,108,108,32,98,117,116,32,112,101,114,115,111,110,97,108,32,117,115,101,32,111,102,32,116,104,105,115,32,99,111,100,101,46,32,83,101,101,32,84,101,114,109,115,32,111,102,32,85,115,101,32,97,116,32,100,121,110,45,119,101,98,46,99,111,109];
dw_Inf.get = function(ar){
    var s="";
    var ln=ar.length;
    for(var i=0;i<ln;i++){
        s+=String.fromCharCode(ar[i]);
    }
    return s;
};
dw_Inf.mg = dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x65\x74\x28\x64\x77\x5f\x49\x6e\x66\x2e\x61\x72\x29');
dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x2e\x68\x6f\x73\x74\x6e\x61\x6d\x65\x2e\x74\x6f\x4c\x6f\x77\x65\x72\x43\x61\x73\x65\x28\x29\x3b');
dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x32\x3d\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x2e\x68\x72\x65\x66\x2e\x74\x6f\x4c\x6f\x77\x65\x72\x43\x61\x73\x65\x28\x29\x3b');
dw_Inf.x0 = function(){
    dw_Inf.fn('\x69\x66\x28\x21\x28\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x3d\x22\x22\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x3d\x3d\x22\x31\x32\x37\x2e\x30\x2e\x30\x2e\x31\x22\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x31\x2e\x69\x6e\x64\x65\x78\x4f\x66\x28\x22\x6c\x6f\x63\x61\x6c\x68\x6f\x73\x74\x22\x29\x21\x3d\x2d\x31\x7c\x7c\x64\x77\x5f\x49\x6e\x66\x2e\x67\x77\x32\x2e\x69\x6e\x64\x65\x78\x4f\x66\x28\x22\x64\x79\x6e\x2d\x77\x65\x62\x2e\x63\x6f\x6d\x22\x29\x21\x3d\x2d\x31\x29\x29\x61\x6c\x65\x72\x74\x28\x64\x77\x5f\x49\x6e\x66\x2e\x6d\x67\x29\x3b\x64\x77\x5f\x73\x63\x72\x6f\x6c\x6c\x4f\x62\x6a\x2e\x73\x63\x72\x64\x79\x3d\x74\x72\x75\x65\x3b');
};
dw_Inf.fn('\x64\x77\x5f\x49\x6e\x66\x2e\x78\x30\x28\x29\x3b');