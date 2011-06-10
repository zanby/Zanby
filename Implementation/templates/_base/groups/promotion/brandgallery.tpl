<script src="/js/mouse_coord.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfobject.js"></script>
<script src="/js/SWFUpload/swfuploadcode.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>
{literal}
<script>
function uploadBrandImage() {
    var callback = {
        upload: uploadhandle
    }
    var oForm = YAHOO.util.Dom.get('form0');
    YAHOO.util.Connect.setForm(oForm, true);
    try {
        var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
    } catch (ex) {
        alert("Incorrect file name");
    }
}

function uploadhandle(oResponse) {
    //alert(oResponse.responseXML);
    xajax.processResponse(oResponse.responseXML);
}

function setSWFUploadParams()
{
    setUploadURL("{/literal}{$currentGroup->getGroupPath('brandgalleryuploadsave/swf/1')}{literal}");
    setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "group" : '{$currentGroup->getId()}'{literal}});
    setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
    setFileSizeLimit("{/literal}{$IMAGES_SIZE_LIMIT/1024}{literal}");
    setQueuedLimit(1);
}
</script>
{/literal}
<div id="mainpopupcontent1"></div>
<div id="mainpopuptitleposion1"></div>
{*if $currentGroup->getGroupType() == "family"}
<div class="prInnerSmall prFloatRight">
    <a class="prButton" href="{$currentGroup->getGroupPath('exportmembers')}"><span>{t}Export members list{/t}</span></a>
</div>
{/if*}

<!-- tabs2 slave area begin -->
<div class="prClr3">
    <div class="webbadges-left prIndentTop">
        <ul class="prVerticalNav">
            <li class="active"><a href="{$currentGroup->getGroupPath('brandgallery')}">{t}Brand Images{/t}</a></li>
            <li><a href="{$currentGroup->getGroupPath('webbadges')}">{t}Web Badges{/t}</a></li>
        </ul>
    </div>

    <div class="webbadges-right">
        <h3>{t}Brand Gallery{/t}</h3>
        <p class="prInnerTop prText2">{t}Upload and store the brand images you wish to make available to your family members.{/t}</p>
        <p class="prInnerTop">{t}These promotion badges can be used by placing the associated code on your website.{/t}</p>
        {if !$brandPhotosList}
            <p class="prInnerTop prText2">{t}You have not created your brand Image gallery{/t}</p>
        {/if}
        <div class="prInnerTop prInnerSmallBottom">
        {t var="in_button"}Upload Photos{/t}
        {linkbutton name=$in_button color="orange" link="#" onclick="xajax_brand_gallery_upload_image(); return false;"}
        </div>

        {if $brandPhotosList}
        <h4 class="prInnerTop">{t}Group family brand gallery{/t}</h4>
        <div class="prClr3">
            {foreach item=p name='photos' from=$brandPhotosList}
            <div class="prFloatLeft prInnerSmallRight">
               <a href="#null" onclick="xajax_loadbrandimage({$p->getId()});">
                    <img src="{$p->setWidth(37)->setHeight(37)->getImage()}" />
                </a>
            </div>
            {/foreach}
        </div>
        {/if}

        {if $currentBrandPhoto}
        <div class="prTCenter prInnerTop" name="test">
            <div class="prIndentBottomSmall">{t}Image{/t}</div>
            <img id="xa_branditem_path" src="{$currentBrandPhoto->setWidth(300)->setHeight(300)->getImage()}" alt="" title="" />
            <div id="deletelink" class="prIndentTopSmall">
                <a href="#" onclick="xajax_branditemDelete({$currentBrandPhoto->getId()}); return false;">{t}delete{/t}</a>
            </div>
        </div>
        <code class="prWebBadges-code prIndentTop">
            &lt;div&gt;
            &lt;a href=&quot;{$currentGroup->getGroupPath('summary')}&quot;&gt;
            &lt;img src=&quot;{$currentBrandPhoto->setWidth(300)->setHeight(300)->getImage()}&quot;&gt;
            &lt;/a&gt;
            &lt;/div&gt;
        </code>
        {/if}
    </div>
</div>
