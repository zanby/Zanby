<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="{$KEYWORDS|escape:html}" />
<meta name="description" content="{$DESCRIPTION|escape:html}" />
{if $GEOPOSITION}<meta name="geo.position" content="{$GEOPOSITION}" />{/if}
{if $GEOPLACENAME}<meta name="geo.placename" content="{$GEOPLACENAME}" />{/if}
{if $GEOREGION}<meta name="geo.region" content="{$GEOREGION}" />{/if}
<title>{$TITLE}</title>

<link rel="stylesheet" type="text/css" href="/css/default.css" />
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui_container.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/yui-menu.css" media="screen" />
<link rel="shortcut icon" type="image/x-icon" href="{$AppTheme->images}/favicon.ico">

<script type="text/javascript" src="/js/common.js" ></script>
<script type="text/javascript" src="/js/yui/yahoo/yahoo-min.js" ></script>
<script type="text/javascript" src="/js/yui/event/event-min.js" ></script>
<script type="text/javascript" src="/js/yui/dom/dom-min.js" ></script>
<script type="text/javascript" src="/js/yui/animation/animation.js" ></script>
<script type="text/javascript" src="/js/yui/connection/connection-min.js" ></script>
<script type="text/javascript" src="/js/yui/container/container_core-min.js" ></script>
<script type="text/javascript" src="/js/yui/container/container-min.js" ></script>
<script type="text/javascript" src="/js/yui/menu/menu-min.js" ></script>
<script type="text/javascript" src="/js/json/json.js" ></script>
<script type="text/javascript" src="/js/geometry.js" ></script>
<script type="text/javascript" src="/js/content.js" ></script>
<script type="text/javascript" src="/js/BrowserDetect.js" ></script>
<script type="text/javascript" src="/js/MainApplication.js" ></script>
{$XajaxJavascript}
</head>
<body onLoad="window.print();" {$body_attributes}>
<!-- cover begin -->
<div>
    <div>
        <!-- left column begin -->
        <!-- left column end -->
        <!-- right column begin -->
        <div class="prTRightColumn" style="background-color:#FFFFFF">
            <!-- header begin -->
            <div id="znbLogo" style="background-color:#FFFFFF; padding:0; width:100%;"> <a href="http://{$BASE_HTTP_HOST}">&nbsp;</a> </div>            
            <!-- header end -->
            <!-- ==================== ============================== -->
            <div>
                
                
                <div>
                	{include file=$bodyContent}
                </div>
                <div><span /></div>
            </div>
            <!-- ==================== ============================== -->
        </div>
        <!-- right column end -->
        <div><span /></div>
        <!-- footer begin -->
        <!-- footer end -->
        <div><span /></div>
    </div>
</div>
<!-- cover end -->


{if $GOOGLE_ANALYTICS=='on' && $GOOGLE_ANALYTICS_ID}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ?"https://ssl." :
"http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost +
"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("{$GOOGLE_ANALYTICS_ID}");
pageTracker._initData();
pageTracker._trackPageview();
</script>
{/if}
{if $TRACER_CODE}
<script type="text/javascript">
{$TRACER_CODE}
</script>
{/if}
</body>
</html>
