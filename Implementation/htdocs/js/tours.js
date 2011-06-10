    function showTour(classname)
    {
        if (classname == undefined) classname = 'prbTourBoxNoBg';
        document.getElementById('tourbox').className=classname; 
        document.getElementById('flash').style.display='';
        document.getElementById('tour_link').style.display='none';
        document.getElementById('close_link').style.display='';    
    }
    
    function hideTour(classname)
    {
        if (classname == undefined) classname = 'prbTourBox';
        document.getElementById('tourbox').className=classname;
        document.getElementById('flash').style.display='none';
        document.getElementById('close_link').style.display='none';
        document.getElementById('tour_link').style.display='';
        //document.getElementById('znbIndexContent-more').innerHTML = '';
    }
    function showLearnMore (flashSRC)
    {
        document.getElementById('znbIndexContent-general').style.display = 'none';
        document.getElementById('znbIndexContent-more').style.display = '';
        document.getElementById('znbIndexContent-more').innerHTML = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="400"><param name="movie" value="' + flashSRC + '" id="znbLearnMore-flash"><!--<param name="quality" value="high"><param name="scale" value="exactfit"><param name="bgcolor" value="#ffffff">--><embed  src="' + flashSRC + '" width="400" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" id="znbLearnMore-embed"></embed></object><br /><a href="#null" onclick="hideLearnMore();">close</a>';
    }
    function hideLearnMore ()
    {
        document.getElementById('znbIndexContent-general').style.display = '';
        document.getElementById('znbIndexContent-more').style.display = 'none';
        document.getElementById('znbIndexContent-more').innerHTML = '';
    }
    
