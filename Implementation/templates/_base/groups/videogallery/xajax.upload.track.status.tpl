{*popup_item*}
<p>{t}{tparam value=$gallery->getTitle()|escape:html}Add videos to %s{/t}</p>
<p class="prInnerTop">
        {t}Checking status{/t}
</p>

<div id="trackStatusContent">        
</div>
<div class="prInnerTop">
    <span class="prIndentLeftSmall">
        <a class="prButton" id="btnCancel1" href="#null" onClick="clearTimeout(timeout);PGEApplication.hideUploadPanel(); return false;">
            <span>{t}Close{/t}</span>
        </a>
    </span>
</div>
{*popup_item*}