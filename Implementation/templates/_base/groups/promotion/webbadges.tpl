<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfobject.js"></script>
<script src="/js/SWFUpload/swfuploadcode.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>

{literal}
<script>
function uploadBadgeImage() {
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
    setUploadURL("{/literal}{$currentGroup->getGroupPath('custombadgeuploadsave/swf/1')}{literal}");
    setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "group" : '{$currentGroup->getId()}'{literal}});
    setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
    setFileSizeLimit("{/literal}{$IMAGES_SIZE_LIMIT/1024}{literal}");
    setQueuedLimit(1);
}
</script>
{/literal}
<script src="/js/mouse_coord.js"></script>
<div id="mainpopupcontent1"></div>
<div id="mainpopuptitleposion1"></div>
<div class="prClr3">
    <div class="webbadges-left"> {if $currentGroup->getGroupType() == "family"}
        <ul class="prVerticalNav">
            <li><a href="{$currentGroup->getGroupPath('brandgallery')}">{t}Brand Images{/t}</a></li>
            <li class="active"><a href="{$currentGroup->getGroupPath('webbadges')}">{t}Web Badges{/t}</a></li>
        </ul>
        {/if}
        <h4>{t}Web Badges{/t}</h4>
        <p>{t}Use the web badges at right to promote your group.  Simply copy the snippet of code underneath the badge and place it in the code of your website. You can also upload custom badges if you wish.{/t} </p>
    </div>
    <div class="webbadges-right">
        <h3>{t}Brand Gallery{/t}</h3>
        <p> {t}These promotion badges can be used by placing the associated code on your website.{/t} </p>
        <img src="{$AppTheme->images}/decorators/groups/group_bar_fake.gif" alt="" title="" class="prIndentTop" />
        <h4>{t}Place this code on your website:{/t} </h4>
        <code class="prWebBadges-code"> &lt;div&gt;
        &lt;a href=&quot;{$currentGroup->getGroupPath('summary')}&quot;&gt;
        &lt;img src=&quot;{$AppTheme->images}/decorators/groups/group_bar_fake.gif&quot;&gt;
        &lt;/a&gt;
        &lt;/div&gt; </code> <img src="{$AppTheme->images}/decorators/groups/badge_base_big.gif" alt="" title="" class="prIndentTop" />
        <h4>{t}Place this code on your website:{/t} </h4>
        <code class="prWebBadges-code"> &lt;div&gt;
        &lt;a href=&quot;{$currentGroup->getGroupPath('summary')}&quot;&gt;
        &lt;img src=&quot;{$AppTheme->images}/decorators/groups/badge_base_big.gif&quot;&gt;
        &lt;/a&gt;
        &lt;/div&gt; </code> {foreach item=p name='photos' from=$webBadgesList}
        <div class="prIndentTop"> <img src="{$p->setWidth(280)->setHeight(60)->getImage()}" alt="" class="prIndentTop" title="" align="middle" /> <a href="#null" onclick="xajax_webbadgeDelete({$p->getId()}); return false;" class="prIndentLeft">{t}delete{/t}</a> </div>
        <h4>{t}Place this code on your website:{/t} </h4>
        <code class="prWebBadges-code"> &lt;div&gt;
        &lt;a href=&quot;{$currentGroup->getGroupPath('summary')}&quot;&gt;
        &lt;img src=&quot;{$p->setWidth(280)->setHeight(60)->getImage()}&quot;&gt;
        &lt;/a&gt;
        &lt;/div&gt; </code> {/foreach}
        <div class="prIndentTop prInnerSmallBottom"> {t var="in_button"}Custom Badge{/t}{linkbutton name=$in_button  link="#" onclick="xajax_custom_badge_upload_image(); return false;"} </div>
    </div>
</div>
