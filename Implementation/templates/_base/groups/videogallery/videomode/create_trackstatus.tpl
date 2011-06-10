{literal}
<script>
    function sendStatusRequest()
    {
        var callback = {
            success: receiveStatusResponse
        }
        action = "{/literal}{$currentGroup->getGroupPath('videogallerytrackstatus')}gallery/{$gallery->getId()}/}{literal}";
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
        <h2 class="prInnerBottom">{t}{tparam value=$currentGroup->getName()}Videos on %s{/t}</h2>
    {/if}
<a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Videos{/t}</a>
    <h2 class="prInnerTop">{t}Upload Videos{/t}</h2>
    <div class="prInnerTop prClr3">    
        <table class="prForm">
            <col width="20%" />
            <col width="80%" />
            <tr>
                <td class="prTRight"></td>
                <td>                    
                    <p class="prInnerTop">
                        {t}Checking status{/t}
                    </p>
                </td>
            </tr>
        </table>
        <div id="trackStatusContent">
        </div>        
    </div>