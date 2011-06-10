<h1 class="znTColor17 znGmapHeadline znGmapHeadlineIEfix">{$gmapItem->getLogin()|escape:html}</h1>
<div class="znWidgetInner4 znGmapDescFix">{$gmapItem->getCity()->name}, {$gmapItem->getState()->name}</div>
<div class="znWidgetInner4"><a style="color:#00f;" target="_parent" href="{$gmapMarker->getUrl()}">{t}go to user{/t}</a></div>