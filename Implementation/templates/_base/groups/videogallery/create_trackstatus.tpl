{literal}
<script>
    function sendStatusRequest()
    {
        var callback = {
            success: receiveStatusResponse
        }
        action = "{/literal}{$currentGroup->getGroupPath('videogallerytrackstatus')}gallery/{$gallery->getId()}/{literal}";        
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

    {if IS_GLOBAL_GROUP}
        <h2 class="prInnerBottom">{t}Stories on {/t} {$currentGroup->getName()}</h2>
    {/if}
    <ul class="prClr3">
        <li class="prFloatLeft"><a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Story Collections{/t}</a></li>
        <li>{t}Upload Stories{/t}</li>
    </ul>
    <h2 class="prInnerTop">{t}Upload Stories{/t}</h2>
    <div class="prInnerTop prClr3">    
        <table class="prForm">
            <col width="20%" />
            <col width="80%" />
            <tr>
                <td class="prTRight"><label for"gallery_title">{t}Collection Title:{/t}</label></td>
                <td>
                    <h3>{$gallery->getTitle()|escape:"html"}</h3>
                    <p class="prInnerTop">
                        {t}Checking status{/t}
                    </p>
                </td>
            </tr>
        </table>
        <div id="trackStatusContent">
        </div>        
    </div>