{literal}
<script>
    function sendStatusRequest()
    {
        var callback = {
            success: receiveStatusResponse
        }
        action = "{/literal}{$currentUser->getUserPath('videogallerytrackstatus')}gallery/{$gallery->getId()}/{literal}";        
        var cObj = YAHOO.util.Connect.asyncRequest('GET', action, callback);    
    }
    
    function receiveStatusResponse(oResponse)
    {        
        xajax.processResponse(oResponse.responseXML);
        timeout = setTimeout(sendStatusRequest, 4000);        
    }
    
     YAHOO.util.Event.onDOMReady(sendStatusRequest);
</script>
{/literal}
<a href="{$currentUser->getUserPath('videos')}">{t}Back to Video Collections{/t}</a>
<h2 class="prInnerSmallTop">{t}Upload Videos{/t}</h2>    
<div class="prInnerSmallTop ">    
	<table class="prForm">
		<col width="20%" />
		<col width="80%" />
		<tr>
			<td class="prTRight"><label for"gallery_title">{t}Collection Title:{/t}</label></td>
			<td>
				 <h3>{$gallery->getTitle()|escape:"html"}</h3>
			 <p class="prInnerSmallTop">
				 {t}Checking status{/t}
			</p>
			</td>
		</tr>                
	</table>
	<div id="trackStatusContent">
	</div>
</div>
